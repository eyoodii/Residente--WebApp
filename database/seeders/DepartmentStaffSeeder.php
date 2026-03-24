<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resident;

/**
 * DepartmentStaffSeeder
 *
 * Creates one sample LGU department staff account per department role.
 * All accounts use role='admin' + a department_role code for RBAC.
 * Default password for all: Dept@2026
 */
class DepartmentStaffSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['code' => 'MAYOR',  'label' => 'Municipal Mayor',                          'email' => 'mayor@buguey.gov.ph',       'first' => 'Eduardo',  'last' => 'Reyes'],
            ['code' => 'VMYOR',  'label' => 'Vice Mayor',                               'email' => 'vmyor@buguey.gov.ph',       'first' => 'Roberto',  'last' => 'Santos'],
            ['code' => 'MPDC',   'label' => 'MPDC',                                     'email' => 'mpdc@buguey.gov.ph',        'first' => 'Lourdes',  'last' => 'Dela Cruz'],
            ['code' => 'ENGR',   'label' => 'Municipal Engineer',                       'email' => 'engr@buguey.gov.ph',        'first' => 'Manuel',   'last' => 'Garcia'],
            ['code' => 'ASSOR',  'label' => 'Municipal Assessor',                       'email' => 'assessor@buguey.gov.ph',    'first' => 'Cynthia',  'last' => 'Lopez'],
            ['code' => 'TRESR',  'label' => 'Municipal Treasurer',                      'email' => 'treasurer@buguey.gov.ph',   'first' => 'Maricel',  'last' => 'Tanaka'],
            ['code' => 'ACCT',   'label' => 'Municipal Accountant',                     'email' => 'accountant@buguey.gov.ph',  'first' => 'Antonio',  'last' => 'Villanueva'],
            ['code' => 'BUDGT',  'label' => 'Municipal Budget Officer',                 'email' => 'budget@buguey.gov.ph',      'first' => 'Gloria',   'last' => 'Macaraeg'],
            ['code' => 'MSWDO',  'label' => 'MSWDO',                                    'email' => 'mswdo@buguey.gov.ph',       'first' => 'Teresita', 'last' => 'Bautista'],
            ['code' => 'MHO',    'label' => 'Municipal Health Officer',                 'email' => 'mho@buguey.gov.ph',         'first' => 'Rodrigo',  'last' => 'Aquino'],
            ['code' => 'DRRMO',  'label' => 'DRRMO',                                    'email' => 'drrmo@buguey.gov.ph',       'first' => 'Vicente',  'last' => 'Magsaysay'],
            ['code' => 'AGRI',   'label' => 'Municipal Agriculturist',                  'email' => 'agri@buguey.gov.ph',        'first' => 'Celestino','last' => 'Pascual'],
            ['code' => 'BPLO',   'label' => 'BPLO Officer-in-Charge',                   'email' => 'bplo@buguey.gov.ph',        'first' => 'Josefina', 'last' => 'Hernandez'],
            ['code' => 'REGST',  'label' => 'Municipal Civil Registrar',                'email' => 'registrar@buguey.gov.ph',   'first' => 'Amado',    'last' => 'Ocampo'],
            ['code' => 'SEPD',   'label' => 'SEPD OIC',                                 'email' => 'sepd@buguey.gov.ph',        'first' => 'Ernesto',  'last' => 'Policarpio'],
            ['code' => 'SBSEC',  'label' => 'Sangguniang Bayan Secretary',              'email' => 'sbsec@buguey.gov.ph',       'first' => 'Milagros', 'last' => 'Evangelista'],
            ['code' => 'HRMO',   'label' => 'Human Resource Management Officer',        'email' => 'hrmo@buguey.gov.ph',        'first' => 'Remedios', 'last' => 'Castillo'],
            // Sangguniang Bayan Committee Chairs
            ['code' => 'SBFIN',  'label' => 'SB Chair on Finance, Budget & Comprehensive Affairs',    'email' => 'sbfin@buguey.gov.ph',       'first' => 'Alfredo',  'last' => 'Domingo'],
            ['code' => 'SBHLT',  'label' => 'SB Chair on Health, Sanitation & Ecology',               'email' => 'sbhlt@buguey.gov.ph',       'first' => 'Corazon',  'last' => 'Mercado'],
            ['code' => 'SBWMN',  'label' => 'SB Chair on Women, Family, Trade Commerce & Industry',   'email' => 'sbwmn@buguey.gov.ph',       'first' => 'Estrella', 'last' => 'Ramos'],
            ['code' => 'SBRLS',  'label' => 'SB Chair on Rules, Privileges & Legislative Oversight',  'email' => 'sbrls@buguey.gov.ph',       'first' => 'Bonifacio','last' => 'Aguilar'],
            ['code' => 'SBPIC',  'label' => 'SB Chair on Public Information & Communication',         'email' => 'sbpic@buguey.gov.ph',       'first' => 'Rosario',  'last' => 'Navarro'],
            ['code' => 'SBTSP',  'label' => 'SB Chair on Transportation',                             'email' => 'sbtsp@buguey.gov.ph',       'first' => 'Victorino','last' => 'Salazar'],
            ['code' => 'SBPWK',  'label' => 'SB Chair on Public Works, Infrastructure & Housing',     'email' => 'sbpwk@buguey.gov.ph',       'first' => 'Renato',   'last' => 'Medina'],
            ['code' => 'SBAGR',  'label' => 'SB Chair on Agriculture & Farmers Association',          'email' => 'sbagr@buguey.gov.ph',       'first' => 'Conrado',  'last' => 'Magno'],
            ['code' => 'SBBGA',  'label' => 'SB Chair on Barangay Affairs',                           'email' => 'sbbga@buguey.gov.ph',       'first' => 'Felicitas','last' => 'Valdez'],
            // SK Federation
            ['code' => 'SKPRS',  'label' => 'SK Federation President',                               'email' => 'skpres@buguey.gov.ph',      'first' => 'Joshua',   'last' => 'Dela Torre'],
        ];

        $this->command->info('');
        $this->command->info('╔══════════════════════════════════════════════════════════╗');
        $this->command->info('║         DEPARTMENT STAFF ACCOUNTS CREATED                ║');
        $this->command->info('╚══════════════════════════════════════════════════════════╝');
        $this->command->info('');

        foreach ($departments as $dept) {
            Resident::updateOrCreate(
                ['email' => $dept['email']],
                [
                    'first_name'      => $dept['first'],
                    'last_name'       => $dept['last'],
                    'middle_name'     => 'G',
                    'email'           => $dept['email'],
                    'password'        => 'Dept@2026',
                    'role'            => 'admin',
                    'department_role' => $dept['code'],
                    'is_verified'     => true,
                    'date_of_birth'   => '1980-01-01',
                    'place_of_birth'  => 'Buguey, Cagayan',
                    'gender'          => 'Male',
                    'civil_status'    => 'Single',
                    'purok'           => '1',
                    'barangay'        => 'Centro',
                    'municipality'    => 'Buguey',
                    'province'        => 'Cagayan',
                ]
            );

            $this->command->info("  [{$dept['code']}] {$dept['label']}");
            $this->command->line("       Email: {$dept['email']}");
            $this->command->line("       Password: Dept@2026");
            $this->command->info('');
        }

        $this->command->warn('⚠️  SECURITY: Change all department passwords after first login!');
        $this->command->info('');
    }
}
