<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $announcements = [
            [
                'title' => 'Buguey Poised to Become Bangus Capital',
                'content' => 'Following the successful harvest of 1.93 metric tons of marketable-sized milkfish from a Circular High-Density Polyethylene (HDPE) fish cage, Buguey is on track to earn another distinction—Bangus Capital of Cagayan. This initiative marks a significant step forward in the town\'s aquaculture industry.',
                'category' => 'LGU Memorandum',
                'target_barangay' => 'Minanga Este',
                'posted_at' => Carbon::now()->subHours(2),
                'is_active' => true,
            ],
            [
                'title' => 'Malaga and Guraman Festival Success',
                'content' => 'The Cagayan Tourism Office congratulates the Municipality of Buguey for a highly successful festival! We celebrated our rich coastal heritage by serving over 2,000 kilos of our famous Malaga to locals and tourists alike.',
                'category' => 'General Update',
                'target_barangay' => null, // Null means it shows to ALL barangays
                'posted_at' => Carbon::now()->subDays(1),
                'is_active' => true,
            ],
            [
                'title' => 'Launch of Oplan CLEOPATRA',
                'content' => 'The local government has formally launched Oplan CLEOPATRA (Crops and Livestock Enhancement to provide greater Opportunities and Programs to Accelerate Technology Transfer). The program aims to boost agricultural productivity and ensure food security for our 32,148 residents.',
                'category' => 'Barangay News',
                'target_barangay' => 'Centro',
                'posted_at' => Carbon::now()->subDays(2),
                'is_active' => true,
            ],
            [
                'title' => 'St. Anne Church Tourism Drive',
                'content' => 'As part of the Cagayan Tourism initiative, we invite all residents to support the preservation of our iconic 17th-century St. Anne Church. Having undergone substantial restoration of its baroque-style wooden retablo, it remains the centerpiece of Buguey\'s historical pride.',
                'category' => 'General Update',
                'target_barangay' => null,
                'posted_at' => Carbon::now()->subDays(4),
                'is_active' => true,
            ],
            [
                'title' => 'Oyster Farming Program Expansion',
                'content' => 'In partnership with DA-BFAR Region 2, the Buguey Brackishwater Technology Outreach Station is expanding the raft-hanging method for Oyster production in the Buguey Lagoon. Interested fisherfolk may register at the Municipal Agriculture Office.',
                'category' => 'Health Service', // Using this category for Agriculture/Livelihood support
                'target_barangay' => 'San Lorenzo', 
                'posted_at' => Carbon::now()->subDays(5),
                'is_active' => true,
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }
    }
}
