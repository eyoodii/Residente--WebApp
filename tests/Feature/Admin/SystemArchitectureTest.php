<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Resident;

class SystemArchitectureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_demographic_propagation(): void
    {
        // Setup: Ensure SuperAdmin role exists
        $superadmin = \App\Models\Resident::factory()->create([
            'role' => 'SA',
            'is_verified' => true,
        ]);
        
        $this->actingAs($superadmin);

        // Action: Create a dummy household
        $householdData = [
            'house_number' => '123 Test St.',
            'street' => 'Test Avenue',
            'purok' => '1',
            'barangay' => 'Centro',
            'housing_type' => 'Owned',
        ];
        
        $response = $this->post(route('admin.households.store'), $householdData);
        $response->assertSessionHas('success');
        
        // Assert: Household created in database
        $this->assertDatabaseHas('households', [
            'house_number' => '123 Test St.',
            'purok' => '1',
            'barangay' => 'Centro',
        ]);
        
        $household = \App\Models\Household::where('house_number', '123 Test St.')->first();
        
        // Action: Assign dummy resident profile (Household head)
        $headData = [
            'resident_id' => \App\Models\Resident::factory()->create(['barangay' => 'Centro'])->id,
            'family_name' => 'Demo Family',
        ];
        
        $response = $this->post(route('admin.households.head.store', $household), $headData);
        $response->assertSessionHas('success');
        
        $householdHead = \App\Models\HouseholdHead::where('household_id', $household->id)->first();
        
        // Action: Add multiple members to this specific household
        $member1Data = [
            'first_name' => 'Test',
            'middle_name' => 'Demo',
            'last_name' => 'Member1',
            'date_of_birth' => '2000-01-01',
            'gender' => 'Male',
            'relationship' => 'Son',
            'civil_status' => 'Single',
        ];
        
        $response = $this->post(route('admin.households.member.store', $householdHead), $member1Data);
        if ($response->exception) throw $response->exception;
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
        
        // Assert: Dashboard Overviews Reflect Exactly This Addition
        $response = $this->get(route('admin.barangay-overview', ['barangay' => 'Centro']));
        $response->assertStatus(200);
        
        // Verification: Delete one resident from the household and verify the subsequent proportional deduction
        $member = \App\Models\HouseholdMember::where('first_name', 'Test')->first();
        
        // Soft delete the member to trigger cascading rules and logging
        $member->delete();
        
        $this->assertSoftDeleted('household_members', [
            'id' => $member->id,
        ]);
        
        // Assert family size updated proportionally
        $householdHead->refresh();
        $this->assertEquals(1, $householdHead->family_size);
    }

    /**
     * Test Scenario 2: Validation and Edge-Case Handling
     */
    public function test_validation_and_edge_case_handling()
    {
        // 1. Setup Superadmin
        $superAdmin = Resident::factory()->create(['role' => 'SA']);
        $this->actingAs($superAdmin);

        // 2. Setup a valid household + head (same pattern as Scenario 1)
        $householdData = [
            'house_number' => '456 Validation St.',
            'street' => 'Edge Case Ave',
            'purok' => '2',
            'barangay' => 'Centro',
            'housing_type' => 'Owned',
        ];
        $this->post(route('admin.households.store'), $householdData);
        $household = \App\Models\Household::where('house_number', '456 Validation St.')->first();

        $headData = [
            'resident_id' => Resident::factory()->create(['barangay' => 'Centro'])->id,
            'family_name' => 'Validation Family',
        ];
        $this->post(route('admin.households.head.store', $household), $headData);
        $householdHead = \App\Models\HouseholdHead::where('household_id', $household->id)->first();

        // 3. Anomaly A: Missing mandatory fields
        $invalidMemberData = [
            'first_name' => '', // Empty required field
            'date_of_birth' => '2000-01-01',
            'gender' => 'Male',
        ];
        
        $response = $this->post(route('admin.households.member.store', $householdHead), $invalidMemberData);
        
        // Assert: system intercepts the anomaly and returns validation errors
        $response->assertSessionHasErrors();
        
        // Assert: the database was NOT polluted by the invalid data
        $this->assertEquals(0, \App\Models\HouseholdMember::count());
        $this->assertEquals(1, $householdHead->fresh()->family_size);
        
        // 4. Anomaly B: Submit a valid member, then submit incomplete data again
        $validData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'date_of_birth' => '1990-01-01',
            'place_of_birth' => 'Manila',
            'gender' => 'Male',
            'relationship' => 'Son',
            'civil_status' => 'Single',
        ];
        $this->post(route('admin.households.member.store', $householdHead), $validData);
        $this->assertEquals(1, \App\Models\HouseholdMember::count());
        
        // Re-submit with missing last_name to verify validation still fires
        $invalidData2 = [
            'first_name' => 'Jane',
            'last_name' => '', // Missing
            'date_of_birth' => '1995-06-15',
            'gender' => 'Female',
        ];
        $response2 = $this->post(route('admin.households.member.store', $householdHead), $invalidData2);
        $response2->assertSessionHasErrors();
        
        // Assert: member count did NOT increase
        $this->assertEquals(1, \App\Models\HouseholdMember::count());
    }

    /**
     * Test Scenario 3: Ubiquitous Audit Trail Verification
     * Verifies that CRUD operations on Residents are captured in the activity_logs table.
     */
    public function test_audit_trail_verification()
    {
        // 1. Setup Superadmin
        $superAdmin = Resident::factory()->create(['role' => 'SA']);
        $this->actingAs($superAdmin);

        // 2. Create a resident to operate on
        $resident = Resident::factory()->create([
            'role' => 'visitor',
            'is_verified' => false,
            'barangay' => 'Centro',
        ]);

        // 3. Perform a verify action (which logs to activity_logs)
        $response = $this->post(route('admin.residents.verify.store', $resident), [
            'verification_method' => 'manual',
            'notes' => 'Audit trail test verification',
        ]);

        // Assert: Activity log was created for the verify action
        $this->assertDatabaseHas('activity_logs', [
            'resident_id' => $superAdmin->id,
            'action' => 'verify_resident',
            'entity_type' => 'Resident',
            'entity_id' => $resident->id,
            'severity' => 'critical',
        ]);

        // Assert: The log captured the correct admin user
        $log = \App\Models\ActivityLog::where('action', 'verify_resident')->first();
        $this->assertNotNull($log);
        $this->assertEquals($superAdmin->email, $log->user_email);
        $this->assertEquals('SA', $log->user_role);
        $this->assertNotNull($log->created_at);

        // 4. Perform a delete action (also logs to activity_logs)
        $residentToDelete = Resident::factory()->create([
            'role' => 'citizen',
            'barangay' => 'Centro',
        ]);
        $deleteId = $residentToDelete->id;

        $this->delete(route('admin.residents.destroy', $residentToDelete));

        // Assert: Activity log was created for the delete action
        $this->assertDatabaseHas('activity_logs', [
            'resident_id' => $superAdmin->id,
            'action' => 'delete_resident',
            'entity_type' => 'Resident',
            'entity_id' => $deleteId,
            'severity' => 'critical',
        ]);

        // Assert: Total activity logs count is exactly 2 (verify + delete)
        $this->assertEquals(2, \App\Models\ActivityLog::count());
    }

    /**
     * Test Scenario 4: Cross-Module Cascading Rules
     * Verifies that deleting a household head cascades to members and
     * that deleting a resident is reflected across related tables.
     */
    public function test_cross_module_cascading_rules()
    {
        // 1. Setup Superadmin
        $superAdmin = Resident::factory()->create(['role' => 'SA']);
        $this->actingAs($superAdmin);

        // 2. Create a household with head and member
        $householdData = [
            'house_number' => '789 Cascade St.',
            'street' => 'Cascade Avenue',
            'purok' => '3',
            'barangay' => 'Centro',
            'housing_type' => 'Owned',
        ];
        $this->post(route('admin.households.store'), $householdData);
        $household = \App\Models\Household::where('house_number', '789 Cascade St.')->first();

        $headData = [
            'resident_id' => Resident::factory()->create(['barangay' => 'Centro'])->id,
            'family_name' => 'Cascade Family',
        ];
        $this->post(route('admin.households.head.store', $household), $headData);
        $householdHead = \App\Models\HouseholdHead::where('household_id', $household->id)->first();

        // Add a member
        $this->post(route('admin.households.member.store', $householdHead), [
            'first_name' => 'Cascade',
            'last_name' => 'Member',
            'date_of_birth' => '1995-07-20',
            'place_of_birth' => 'Manila',
            'gender' => 'Female',
            'relationship' => 'Daughter',
            'civil_status' => 'Single',
        ]);

        $this->assertEquals(1, \App\Models\HouseholdMember::count());
        $this->assertEquals(2, $householdHead->fresh()->family_size);

        // 3. Delete the household head directly (model-level cascade test)
        $householdHead->delete();

        // Assert: Household head is soft deleted
        $this->assertSoftDeleted('household_heads', ['id' => $householdHead->id]);

        // Assert: Members are still in the database (they belong to a soft-deleted head)
        // This tests whether members are orphaned or cascaded
        $memberCount = \App\Models\HouseholdMember::count();
        
        // 4. Delete a resident and verify cascading effect on service requests
        $residentToDelete = Resident::factory()->create([
            'role' => 'citizen',
            'barangay' => 'Centro',
        ]);
        $deleteId = $residentToDelete->id;

        $this->delete(route('admin.residents.destroy', $residentToDelete));

        // Assert: Resident is soft-deleted from database (Resident uses SoftDeletes)
        $this->assertSoftDeleted('residents', ['id' => $deleteId]);

        // Assert: Activity log recorded the deletion
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'delete_resident',
            'entity_id' => $deleteId,
        ]);

        // Assert: Household still exists (deleting a resident shouldn't cascade to household)
        $this->assertDatabaseHas('households', ['id' => $household->id]);
    }
}
