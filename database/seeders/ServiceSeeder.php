<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceStep;
use App\Models\ServiceRequirement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Availing of Anti-Rabies',
                'slug' => 'anti-rabies',
                'department' => 'Municipal Health Office',
                'description' => 'Treatment and vaccination service for individuals exposed to animal bites',
                'classification' => 'Simple',
                'type' => 'G2C',
                'who_may_avail' => 'All residents who were bitten by animals',
                'fee' => 0.00,
                'fee_description' => 'FREE',
                'processing_time_minutes' => 39,
                'icon' => '💉',
                'color' => 'sea-green',
                'requirements' => [
                    ['requirement' => 'Referral Slip', 'where_to_secure' => 'Municipal Health Office'],
                ],
                'steps' => [
                    ['step_number' => 1, 'step_type' => 'client', 'description' => 'Fill-up and submit duly Service Request Form (SRF) to the Officer of the Day of the Public Assistance and Complaint Desk (PACD) for referral to Midwife/Nurse OnDuty and proceed to Consultation room.', 'processing_time_minutes' => 12, 'responsible_person' => 'Officer of the Day of the PACD Midwife/Nurse OnDuty', 'fee' => 0.00],
                    ['step_number' => 2, 'step_type' => 'agency', 'description' => 'Register name of clients in the logbook. Taking and recording history and vital signs. Refer to MHO for assessment.', 'processing_time_minutes' => 12, 'responsible_person' => 'Officer of the Day of the PACD Midwife/Nurse OnDuty', 'fee' => 0.00],
                    ['step_number' => 3, 'step_type' => 'client', 'description' => 'Proceed to the Municipal Health Officer.', 'processing_time_minutes' => 15, 'responsible_person' => 'Dr. Maria Rowena B. Guzman - Marantan', 'fee' => 0.00],
                    ['step_number' => 4, 'step_type' => 'agency', 'description' => 'Conduct examination and evaluation of medical condition. Refer to Anti-Rabies Coordinator.', 'processing_time_minutes' => 15, 'responsible_person' => 'Dr. Maria Rowena B. Guzman - Marantan', 'fee' => 0.00],
                    ['step_number' => 5, 'step_type' => 'client', 'description' => 'Proceed and present referral slip to the Anti-Rabies Coordinator.', 'processing_time_minutes' => 12, 'responsible_person' => 'Antonette A. Urdas, RN', 'fee' => 0.00],
                    ['step_number' => 6, 'step_type' => 'agency', 'description' => 'Administer anti-rabies vaccine. Giving follow-up schedules and health teachings on the prevention of animal bite.', 'processing_time_minutes' => 12, 'responsible_person' => 'Antonette A. Urdas, RN', 'fee' => 0.00],
                ],
            ],
            [
                'name' => 'Availing of Laboratory Services',
                'slug' => 'laboratory-services',
                'department' => 'Municipal Health Office',
                'description' => 'Various laboratory examinations including CBC, urinalysis, fecalysis, blood typing and more',
                'classification' => 'Simple',
                'type' => 'G2C',
                'who_may_avail' => 'All',
                'fee' => 40.00,
                'fee_description' => '₱40-₱110',
                'processing_time_minutes' => 74,
                'icon' => '🔬',
                'color' => 'sea-green',
                'requirements' => [
                    ['requirement' => 'Referral letter/slip', 'where_to_secure' => 'Municipal Health Officer/ Private Physician'],
                    ['requirement' => 'Official Receipt of Payment', 'where_to_secure' => 'Municipal Treasury Office'],
                ],
                'steps' => [
                    ['step_number' => 1, 'step_type' => 'client', 'description' => 'Fill-up and submit duly Service Request Form (SRF) to the Officer of the Day of the Public Assistance and Complaint Desk (PACD) for referral to Medical Technologists and secure order of payment.', 'processing_time_minutes' => 9, 'responsible_person' => 'Officer of the Day of the PACD', 'fee' => 0.00],
                    ['step_number' => 2, 'step_type' => 'agency', 'description' => 'Register name of clients in the logbook. Receive referral letter/slip. Prepare order of payment', 'processing_time_minutes' => 9, 'responsible_person' => 'Sheryll Joyce C.Sobrevilla, RMT / Denjie M. Coronel, RMT', 'fee' => 0.00],
                    ['step_number' => 3, 'step_type' => 'client', 'description' => 'Proceed to Treasury Office and pay the Laboratory services fee', 'processing_time_minutes' => 5, 'responsible_person' => 'Carmen Unciano / Thelma Napoles / Elpidio Arellano', 'fee' => 70.00],
                    ['step_number' => 4, 'step_type' => 'client', 'description' => 'Present Official Receipt of payment to the Medical Technologist and undergo laboratory examination', 'processing_time_minutes' => 45, 'responsible_person' => 'Sheryll Joyce C. Sobrevilla, RMT / Denjie M. Coronel, RMT', 'fee' => 0.00],
                    ['step_number' => 5, 'step_type' => 'agency', 'description' => 'Receive Official Receipt of Payment. Collects the specimen. Process the procedure', 'processing_time_minutes' => 45, 'responsible_person' => 'Sheryll Joyce C. Sobrevilla, RMT / Denjie M. Coronel, RMT', 'fee' => 0.00],
                ],
            ],
            [
                'name' => 'Availing of Dental Services',
                'slug' => 'dental-services',
                'department' => 'Municipal Health Office',
                'description' => 'Dental examination, tooth extraction, fillings, and oral prophylaxis services',
                'classification' => 'Simple',
                'type' => 'G2C',
                'who_may_avail' => 'All',
                'fee' => 75.00,
                'fee_description' => '₱50-₱150',
                'processing_time_minutes' => 60,
                'icon' => '🦷',
                'color' => 'sea-green',
                'requirements' => [
                    ['requirement' => 'Referral letter/slip', 'where_to_secure' => 'Municipal Health Office'],
                    ['requirement' => 'Vax Certificate', 'where_to_secure' => 'From the Client'],
                    ['requirement' => 'Official Receipt', 'where_to_secure' => 'Municipal Treasury Office'],
                ],
                'steps' => [
                    ['step_number' => 1, 'step_type' => 'client', 'description' => 'Fill-up and submit duly Service Request Form (SRF) to the Officer of the Day of the Public Assistance and Complaint Desk (PACD) for referral to Municipal Dentist and secure order of payment.', 'processing_time_minutes' => 15, 'responsible_person' => 'Officer of the Day of the PACD', 'fee' => 0.00],
                    ['step_number' => 2, 'step_type' => 'agency', 'description' => 'Register name of clients in the logbook. Takes and records blood pressure. Receive referral letter/slip and VaxCert. Prepare order of payment', 'processing_time_minutes' => 15, 'responsible_person' => 'Dr. Lyric F. Alias', 'fee' => 0.00],
                    ['step_number' => 3, 'step_type' => 'client', 'description' => 'Proceed to Treasury Office and pay the dental fee.', 'processing_time_minutes' => 5, 'responsible_person' => 'Carmen Unciano / Thelma Napoles / Elpidio Arellano', 'fee' => 75.00],
                    ['step_number' => 4, 'step_type' => 'client', 'description' => 'Proceed and present Official receipt to Dentist.', 'processing_time_minutes' => 35, 'responsible_person' => 'Dr. Lyric F. Alias', 'fee' => 0.00],
                    ['step_number' => 5, 'step_type' => 'agency', 'description' => 'Conduct dental examination or tooth extraction if necessary. Release medical prescription if medicine is needed.', 'processing_time_minutes' => 35, 'responsible_person' => 'Dr. Lyric F. Alias', 'fee' => 0.00],
                    ['step_number' => 6, 'step_type' => 'client', 'description' => 'Proceed and present Medical Prescription and receives the medicines to Municipal Health Officer.', 'processing_time_minutes' => 5, 'responsible_person' => 'Dr. Maria Rowena B. Guzman - Marantan', 'fee' => 0.00],
                    ['step_number' => 7, 'step_type' => 'agency', 'description' => 'Issuance and provisions of medicines', 'processing_time_minutes' => 5, 'responsible_person' => 'Dr. Maria Rowena B. Guzman - Marantan', 'fee' => 0.00],
                ],
            ],
            [
                'name' => "Issuance of Mayor's Clearance",
                'slug' => 'mayors-clearance',
                'department' => "Mayor's Office",
                'description' => 'Certificate issued by the Mayor confirming that the applicant has no pending case in the municipality',
                'classification' => 'Simple',
                'type' => 'G2C',
                'who_may_avail' => 'Residents Only',
                'fee' => 80.00,
                'fee_description' => '₱80',
                'processing_time_minutes' => 45,
                'icon' => '📋',
                'color' => 'golden-glow',
                'requirements' => [
                    ['requirement' => 'Barangay Clearance', 'where_to_secure' => 'From the Barangay'],
                    ['requirement' => 'Community Tax Certificate', 'where_to_secure' => 'Office of the Municipal Treasurer'],
                    ['requirement' => 'Police Clearance', 'where_to_secure' => 'Business Permit and Licensing Office'],
                    ['requirement' => 'Municipal Trial Court Clearance', 'where_to_secure' => 'Municipal Trial Court'],
                    ['requirement' => "Prosecutor's Clearance", 'where_to_secure' => "Prosecutor's Office"],
                ],
                'steps' => [
                    ['step_number' => 1, 'step_type' => 'client', 'description' => 'Present the requirements needed', 'processing_time_minutes' => 5, 'responsible_person' => 'Irene O. Alameda - Admin Aide I', 'fee' => 0.00],
                    ['step_number' => 2, 'step_type' => 'agency', 'description' => 'Receive and the review the requirements needed', 'processing_time_minutes' => 5, 'responsible_person' => 'Irene O. Alameda - Admin Aide I', 'fee' => 0.00],
                    ['step_number' => 3, 'step_type' => 'client', 'description' => 'Pay the corresponding fees at the Office of the Municipal Treasurer', 'processing_time_minutes' => 5, 'responsible_person' => 'Cherry Cabusi Admin Aide VI / BPLO', 'fee' => 80.00],
                    ['step_number' => 4, 'step_type' => 'agency', 'description' => 'Instruct the client to pay the corresponding fees at the office of the Municipal Treasurer. Receive the Official receipt and review the amount paid.', 'processing_time_minutes' => 10, 'responsible_person' => 'Cherry Cabusi Admin Aide VI / BPLO', 'fee' => 0.00],
                    ['step_number' => 5, 'step_type' => 'client', 'description' => 'Wait for the preparation and approval of documents', 'processing_time_minutes' => 5, 'responsible_person' => 'Local Chief Executive', 'fee' => 0.00],
                    ['step_number' => 6, 'step_type' => 'agency', 'description' => 'Prepare the documents. Approve the document', 'processing_time_minutes' => 10, 'responsible_person' => 'Local Chief Executive', 'fee' => 0.00],
                    ['step_number' => 7, 'step_type' => 'client', 'description' => "Claim the Mayor's Clearance", 'processing_time_minutes' => 5, 'responsible_person' => 'Irene O. Alameda Admin Aide I', 'fee' => 0.00],
                    ['step_number' => 8, 'step_type' => 'agency', 'description' => "Release the Mayor's Clearance", 'processing_time_minutes' => 5, 'responsible_person' => 'Irene O. Alameda Admin Aide I', 'fee' => 0.00],
                ],
            ],
            [
                'name' => 'Application for Marriage License',
                'slug' => 'marriage-license',
                'department' => 'Municipal Civil Registrar',
                'description' => 'Application for marriage license for residents intending to get married',
                'classification' => 'Simple',
                'type' => 'G2C',
                'who_may_avail' => 'Resident/s of the municipality who is/are party/ies can apply for marriage license',
                'fee' => 72.00,
                'fee_description' => '₱72',
                'processing_time_minutes' => 25,
                'icon' => '💍',
                'color' => 'tiger-orange',
                'requirements' => [
                    ['requirement' => 'Proof of age of the applicants (Birth certificate, baptismal certificate, etc.)', 'where_to_secure' => 'Office of the Municipal Civil Registrar/Religious Office'],
                    ['requirement' => 'Family Planning and Pre-Marriage Counseling Certificate', 'where_to_secure' => 'Office of the Municipal Health Officer'],
                    ['requirement' => 'Proof of previous marriage (if applicable)', 'where_to_secure' => 'Office of the Municipal Civil Registrar, Branch of Regional Trial Court, PSA'],
                    ['requirement' => 'Community Tax Certificate of the applicants and Tree Planting Cert.', 'where_to_secure' => 'Office of the Barangay Chairman/Office of the Municipal Treasurer'],
                ],
                'steps' => [
                    ['step_number' => 1, 'step_type' => 'client', 'description' => 'Applicants for a marriage license present the requirements for the application to the EIC.', 'processing_time_minutes' => 10, 'responsible_person' => 'Emilie P.Mingming RO1', 'fee' => 0.00],
                    ['step_number' => 2, 'step_type' => 'agency', 'description' => 'Determine whether one or both applicants are residents of your municipality. Check documents and verify ages.', 'processing_time_minutes' => 10, 'responsible_person' => 'Emilie P.Mingming RO1', 'fee' => 0.00],
                    ['step_number' => 3, 'step_type' => 'client', 'description' => 'Applicants sign the Application for Marriage License upon ensuring that the correct entries were given.', 'processing_time_minutes' => 5, 'responsible_person' => 'Emilie P. Mingming RO1', 'fee' => 0.00],
                    ['step_number' => 4, 'step_type' => 'agency', 'description' => 'Accomplish the Application for Marriage License. Record the application and post the notice for 10 consecutive days.', 'processing_time_minutes' => 9, 'responsible_person' => 'Emilie P. Mingming RO1', 'fee' => 0.00],
                    ['step_number' => 5, 'step_type' => 'client', 'description' => 'Payment of corresponding fees at the Office of the Municipal Treasurer.', 'processing_time_minutes' => 5, 'responsible_person' => 'Emilie P. Mingming RO1', 'fee' => 72.00],
                    ['step_number' => 6, 'step_type' => 'agency', 'description' => 'Issue the Marriage License to the contracting parties upon compliance of all the requirement', 'processing_time_minutes' => 5, 'responsible_person' => 'Emilie P. Mingming RO1', 'fee' => 0.00],
                ],
            ],
            [
                'name' => 'Issuance of Sanitary Permit',
                'slug' => 'sanitary-permit',
                'department' => 'Municipal Health Office',
                'description' => 'Permit required for food establishments, industrial establishments, parlors, and water refilling stations',
                'classification' => 'Simple',
                'type' => 'G2C',
                'who_may_avail' => 'All business establishments requiring health permits',
                'fee' => 50.00,
                'fee_description' => '₱50',
                'processing_time_minutes' => 1440, // 1 day or more
                'icon' => '🏥',
                'color' => 'sea-green',
                'requirements' => [
                    ['requirement' => 'For Food Establishments: Chest X-ray, Urinalysis, Fecalysis, HEPA-A (Cook and Servers)', 'where_to_secure' => 'Private Laboratory'],
                    ['requirement' => 'For Industrial Establishments/Parlor/Barber Shops: Chest X-ray', 'where_to_secure' => 'Private Laboratory'],
                    ['requirement' => 'For Water Refilling Stations: Chest X-ray, Urinalysis', 'where_to_secure' => 'Private Laboratory'],
                ],
                'steps' => [
                    ['step_number' => 1, 'step_type' => 'client', 'description' => 'Fill-up and submit duly Service Request Form (SRF) to the Officer of the Day of the PACD for referral to Sanitary Inspector.', 'processing_time_minutes' => 2, 'responsible_person' => 'Officer of the Day of the PACD', 'fee' => 0.00],
                    ['step_number' => 2, 'step_type' => 'agency', 'description' => 'Register name of clients in the logbook. Refer to Sanitary Inspector', 'processing_time_minutes' => 2, 'responsible_person' => 'Officer of the Day of the PACD', 'fee' => 0.00],
                    ['step_number' => 3, 'step_type' => 'client', 'description' => 'Proceed to the Sanitary Inspector and present required documents for assessment and Evaluation.', 'processing_time_minutes' => 1440, 'responsible_person' => 'Daniel G. Domingo / Jerlen S. Banastas', 'fee' => 0.00],
                    ['step_number' => 4, 'step_type' => 'agency', 'description' => 'Receive the documents and check for completeness. Prepare Order of Payment (Securing Health ID)', 'processing_time_minutes' => 1440, 'responsible_person' => 'Daniel G. Domingo / Jerlen S. Banastas', 'fee' => 0.00],
                    ['step_number' => 5, 'step_type' => 'client', 'description' => 'Proceed to Treasury Office and pay the Health ID fee.', 'processing_time_minutes' => 5, 'responsible_person' => 'Carmen Unciano / Thelma Napoles / Elpidio Arellano', 'fee' => 50.00],
                    ['step_number' => 6, 'step_type' => 'client', 'description' => 'Proceed and present official receipt to the Sanitary Inspector.', 'processing_time_minutes' => 1, 'responsible_person' => 'Daniel G. Domingo / Jerlen S. Banastas', 'fee' => 0.00],
                    ['step_number' => 7, 'step_type' => 'agency', 'description' => 'Recommend for Approval of the MHO. For Positive/Abnormal Results of Laboratory, immediate endorsement of client to MHO', 'processing_time_minutes' => 1, 'responsible_person' => 'Dr. Maria Rowena B. Guzman- Marantan', 'fee' => 0.00],
                    ['step_number' => 8, 'step_type' => 'client', 'description' => 'Receives Sanitary permit and Health ID.', 'processing_time_minutes' => 1, 'responsible_person' => 'Daniel G. Domingo / Jerlen S. Banastas', 'fee' => 0.00],
                    ['step_number' => 9, 'step_type' => 'agency', 'description' => 'Release Sanitary permit and Health ID', 'processing_time_minutes' => 1, 'responsible_person' => 'Daniel G. Domingo / Jerlen S. Banastas', 'fee' => 0.00],
                ],
            ],
        ];

        foreach ($services as $serviceData) {
            $steps = $serviceData['steps'];
            $requirements = $serviceData['requirements'];
            unset($serviceData['steps'], $serviceData['requirements']);

            $service = Service::create($serviceData);

            foreach ($steps as $step) {
                $service->steps()->create($step);
            }

            foreach ($requirements as $requirement) {
                $service->requirements()->create($requirement);
            }
        }
    }
}
