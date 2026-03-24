<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceStep;
use App\Models\ServiceRequirement;
use Illuminate\Database\Seeder;

class ServiceSeederComplete extends Seeder
{
    public function run(): void
    {
        $services = [
            // ========================================
            // MUNICIPAL HEALTH OFFICE (10 services)
            // ========================================
            
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
                    ['step_number' => 1, 'step_type' => 'client', 'description' => 'Fill-up and submit Service Request Form to PACD Officer', 'processing_time_minutes' => 5, 'responsible_person' => 'PACD Officer', 'fee' => 0.00],
                    ['step_number' => 2, 'step_type' => 'agency', 'description' => 'Register client and take vital signs', 'processing_time_minutes' => 10, 'responsible_person' => 'Nurse OnDuty', 'fee' => 0.00],
                    ['step_number' => 3, 'step_type' => 'agency', 'description' => 'Conduct examination and evaluation', 'processing_time_minutes' => 12, 'responsible_person' => 'Municipal Health Officer', 'fee' => 0.00],
                    ['step_number' => 4, 'step_type' => 'agency', 'description' => 'Administer anti-rabies vaccine and provide follow-up schedule', 'processing_time_minutes' => 12, 'responsible_person' => 'Anti-Rabies Coordinator', 'fee' => 0.00],
                ],
            ],

            [
                'name' => 'Availing of Immunization Services',
                'slug' => 'immunization-services',
                'department' => 'Municipal Health Office',
                'description' => 'Vaccination services for infants, children, and adults including BCG, DPT, Measles, and others',
                'classification' => 'Simple',
                'type' => 'G2C',
                'who_may_avail' => 'All residents requiring vaccinations',
                'fee' => 0.00,
                'fee_description' => 'FREE',
                'processing_time_minutes' => 30,
                'icon' => '💉',
                'color' => 'sea-green',
                'requirements' => [
                    ['requirement' => 'Immunization Card/Record', 'where_to_secure' => 'Municipal Health Office or Barangay Health Station'],
                    ['requirement' => 'Birth Certificate (for first-time)', 'where_to_secure' => 'Municipal Civil Registrar'],
                ],
                'steps' => [
                    ['step_number' => 1, 'step_type' => 'client', 'description' => 'Present immunization card and proceed to registration', 'processing_time_minutes' => 5, 'responsible_person' => 'Midwife/Nurse', 'fee' => 0.00],
                    ['step_number' => 2, 'step_type' => 'agency', 'description' => 'Check immunization history and schedule', 'processing_time_minutes' => 10, 'responsible_person' => 'Midwife/Nurse', 'fee' => 0.00],
                    ['step_number' => 3, 'step_type' => 'agency', 'description' => 'Administer required vaccines and update immunization card', 'processing_time_minutes' => 15, 'responsible_person' => 'Midwife/Nurse', 'fee' => 0.00],
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
                'fee_description' => '₱40-₱110 depending on test',
                'processing_time_minutes' => 74,
                'icon' => '🔬',
                'color' => 'sea-green',
                'requirements' => [
                    ['requirement' => 'Request Form from physician (if applicable)', 'where_to_secure' => 'Attending Physician'],
                ],
                'steps' => [
                    ['step_number' => 1, 'step_type' => 'client', 'description' => 'Submit Service Request Form and proceed to laboratory', 'processing_time_minutes' => 5, 'responsible_person' => 'PACD Officer', 'fee' => 0.00],
                    ['step_number' => 2, 'step_type' => 'agency', 'description' => 'Register and assess laboratory request', 'processing_time_minutes' => 10, 'responsible_person' => 'Medical Technologist', 'fee' => 0.00],
                    ['step_number' => 3, 'step_type' => 'client', 'description' => 'Pay laboratory fees at Treasury Office', 'processing_time_minutes' => 15, 'responsible_person' => 'Treasurer', 'fee' => 40.00],
                    ['step_number' => 4, 'step_type' => 'agency', 'description' => 'Conduct laboratory examination and process results', 'processing_time_minutes' => 30, 'responsible_person' => 'Medical Technologist', 'fee' => 0.00],
                    ['step_number' => 5, 'step_type' => 'agency', 'description' => 'Release laboratory results', 'processing_time_minutes' => 14, 'responsible_person' => 'Medical Technologist', 'fee' => 0.00],
                ],
            ],

            [
                'name' => 'Maternal and Child Health Care',
                'slug' => 'maternal-child-health',
                'department' => 'Municipal Health Office',
                'description' => 'Pre-natal, post-natal care, family planning, and child health services',
                'classification' => 'Simple',
                'type' => 'G2C',
                'who_may_avail' => 'Pregnant women, nursing mothers, and children 0-5 years old',
                'fee' => 0.00,
                'fee_description' => 'FREE',
                'processing_time_minutes' => 45,
                'icon' => '👶',
                'color' => 'sea-green',
                'requirements' => [
                    ['requirement' => 'Pregnancy test result (for prenatal)', 'where_to_secure' => 'Municipal Health Office or Private Clinic'],
                    ['requirement' => 'Mother & Child Record Book', 'where_to_secure' => 'Municipal Health Office'],
                ],
                'steps' => [
                    ['step_number' => 1, 'step_type' => 'client', 'description' => 'Register and present required documents', 'processing_time_minutes' => 10, 'responsible_person' => 'Midwife', 'fee' => 0.00],
                    ['step_number' => 2, 'step_type' => 'agency', 'description' => 'Conduct health assessment, vital signs check, and counseling', 'processing_time_minutes' => 25, 'responsible_person' => 'Midwife/Nurse', 'fee' => 0.00],
                    ['step_number' => 3, 'step_type' => 'agency', 'description' => 'Provide vitamins, supplements, and schedule next visit', 'processing_time_minutes' => 10, 'responsible_person' => 'Midwife/Nurse', 'fee' => 0.00],
                ],
            ],

            [
                'name' => 'Anti-Tuberculosis Drugs/Medicines',
                'slug' => 'anti-tuberculosis-drugs',
                'department' => 'Municipal Health Office',
                'description' => 'Provision of anti-TB medication and directly observed treatment short-course (DOTS)',
                'classification' => 'Simple',
                'type' => 'G2C',
                'who_may_avail' => 'Confirmed TB patients',
                'fee' => 0.00,
                'fee_description' => 'FREE',
                'processing_time_minutes' => 35,
                'icon' => '💊',
                'color' => 'sea-green',
                'requirements' => [
                    ['requirement' => 'Medical certificate/TB diagnosis', 'where_to_secure' => 'Municipal Health Officer or Private Doctor'],
                    ['requirement' => 'Chest X-ray result', 'where_to_secure' => 'Hospital or X-ray facility'],
                    ['requirement' => 'Sputum examination result', 'where_to_secure' => 'Municipal Health Office Laboratory'],
                ],
                'steps' => [
                    ['step_number' => 1, 'step_type' => 'client', 'description' => 'Submit medical documents and register for DOTS program', 'processing_time_minutes' => 10, 'responsible_person' => 'TB-DOTS Coordinator', 'fee' => 0.00],
                    ['step_number' => 2, 'step_type' => 'agency', 'description' => 'Assess patient condition and prepare treatment plan', 'processing_time_minutes' => 15, 'responsible_person' => 'TB-DOTS Coordinator', 'fee' => 0.00],
                    ['step_number' => 3, 'step_type' => 'agency', 'description' => 'Dispense anti-TB medications and provide health education', 'processing_time_minutes' => 10, 'responsible_person' => 'TB-DOTS Coordinator', 'fee' => 0.00],
                ],
            ],

            [
                'name' => 'Availing of Dental Services',
                'slug' => 'dental-services',
                'department' => 'Municipal Health Office',
                'description' => 'Dental services including tooth extraction, cleaning, restoration, and oral health education',
                'classification' => 'Simple',
                'type' => 'G2C',
                'who_may_avail' => 'All',
                'fee' => 50.00,
                'fee_description' => '₱50-₱150 depending on procedure',
                'processing_time_minutes' => 60,
                'icon' => '🦷',
                'color' => 'sea-green',
                'requirements' => [
                    ['requirement' => 'Referral slip (if from other health facility)', 'where_to_secure' => 'Referring Facility'],
                ],
                'steps' => [
                    ['step_number' => 1, 'step_type' => 'client', 'description' => 'Fill-up Service Request Form and proceed to dental clinic', 'processing_time_minutes' => 5, 'responsible_person' => 'PACD Officer', 'fee' => 0.00],
                    ['step_number' => 2, 'step_type' => 'agency', 'description' => 'Register patient and conduct dental examination', 'processing_time_minutes' => 15, 'responsible_person' => 'Municipal Dentist', 'fee' => 0.00],
                    ['step_number' => 3, 'step_type' => 'client', 'description' => 'Pay dental service fee at Treasury Office', 'processing_time_minutes' => 10, 'responsible_person' => 'Treasurer', 'fee' => 50.00],
                    ['step_number' => 4, 'step_type' => 'agency', 'description' => 'Perform dental procedure and provide aftercare instructions', 'processing_time_minutes' => 30, 'responsible_person' => 'Municipal Dentist', 'fee' => 0.00],
                ],
            ],

            [
                'name' => 'Availing STD/STI Services',
                'slug' => 'std-sti-services',
                'department' => 'Municipal Health Office',
                'description' => 'Testing, treatment, and counseling for sexually transmitted diseases and infections',
                'classification' => 'Simple',
                'type' => 'G2C',
                'who_may_avail' => 'All',
                'fee' => 0.00,
                'fee_description' => 'FREE',
                'processing_time_minutes' => 50,
                'icon' => '🏥',
                'color' => 'sea-green',
                'requirements' => [
                    ['requirement' => 'Referral slip (if applicable)', 'where_to_secure' => 'Barangay Health Worker or Physician'],
                ],
                'steps' => [
                    ['step_number' => 1, 'step_type' => 'client', 'description' => 'Register and undergo confidential interview', 'processing_time_minutes' => 15, 'responsible_person' => 'Public Health Nurse', 'fee' => 0.00],
                    ['step_number' => 2, 'step_type' => 'agency', 'description' => 'Conduct medical examination and request laboratory tests', 'processing_time_minutes' => 20, 'responsible_person' => 'Municipal Health Officer', 'fee' => 0.00],
                    ['step_number' => 3, 'step_type' => 'agency', 'description' => 'Provide treatment, counseling, and health education', 'processing_time_minutes' => 15, 'responsible_person' => 'Municipal Health Officer', 'fee' => 0.00],
                ],
            ],

            [
                'name' => 'Securing Medical/Death Certificate',
                'slug' => 'medical-death-certificate',
                'department' => 'Municipal Health Office',
                'description' => 'Issuance of medical certificates and death certificates',
                'classification' => 'Simple',
                'type' => 'G2C',
                'who_may_avail' => 'Patients or relatives of deceased',
                'fee' => 50.00,
                'fee_description' => '₱50',
                'processing_time_minutes' => 40,
                'icon' => '📋',
                'color' => 'sea-green',
                'requirements' => [
                    ['requirement' => 'Valid ID', 'where_to_secure' => 'Government or Private Institution'],
                    ['requirement' => 'Medical records (for medical certificate)', 'where_to_secure' => 'Municipal Health Office'],
                    ['requirement' => 'Death report (for death certificate)', 'where_to_secure' => 'Attending Physician or Hospital'],
                ],
                'steps' => [
                    ['step_number' => 1, 'step_type' => 'client', 'description' => 'Submit request and present valid ID and supporting documents', 'processing_time_minutes' => 10, 'responsible_person' => 'Municipal Health Office Staff', 'fee' => 0.00],
                    ['step_number' => 2, 'step_type' => 'client', 'description' => 'Pay certificate fee at Treasury Office', 'processing_time_minutes' => 10, 'responsible_person' => 'Treasurer', 'fee' => 50.00],
                    ['step_number' => 3, 'step_type' => 'agency', 'description' => 'Prepare and review certificate', 'processing_time_minutes' => 15, 'responsible_person' => 'Municipal Health Officer', 'fee' => 0.00],
                    ['step_number' => 4, 'step_type' => 'agency', 'description' => 'Release signed certificate', 'processing_time_minutes' => 5, 'responsible_person' => 'Municipal Health Office Staff', 'fee' => 0.00],
                ],
            ],

            [
                'name' => 'Out-Patient Department',
                'slug' => 'out-patient-department',
                'department' => 'Municipal Health Office',
                'description' => 'General medical consultation and treatment for non-emergency cases',
                'classification' => 'Simple',
                'type' => 'G2C',
                'who_may_avail' => 'All',
                'fee' => 0.00,
                'fee_description' => 'FREE',
                'processing_time_minutes' => 45,
                'icon' => '🩺',
                'color' => 'sea-green',
                'requirements' => [
                    ['requirement' => 'Previous medical records (if available)', 'where_to_secure' => 'Municipal Health Office or Private Clinic'],
                ],
                'steps' => [
                    ['step_number' => 1, 'step_type' => 'client', 'description' => 'Register at OPD and fill-up patient information sheet', 'processing_time_minutes' => 10, 'responsible_person' => 'Nurse/Midwife', 'fee' => 0.00],
                    ['step_number' => 2, 'step_type' => 'agency', 'description' => 'Take vital signs and conduct triage', 'processing_time_minutes' => 10, 'responsible_person' => 'Nurse', 'fee' => 0.00],
                    ['step_number' => 3, 'step_type' => 'agency', 'description' => 'Conduct medical consultation and examination', 'processing_time_minutes' => 20, 'responsible_person' => 'Municipal Health Officer', 'fee' => 0.00],
                    ['step_number' => 4, 'step_type' => 'agency', 'description' => 'Prescribe medication and provide health education', 'processing_time_minutes' => 5, 'responsible_person' => 'Municipal Health Officer', 'fee' => 0.00],
                ],
            ],

            [
                'name' => 'Issuance of Sanitary Permit',
                'slug' => 'sanitary-permit',
                'department' => 'Municipal Health Office',
                'description' => 'Permit required for food establishments, industrial establishments, parlors, and water refilling stations',
                'classification' => 'Simple',
                'type' => 'G2B',
                'who_may_avail' => 'All business establishments requiring health permits',
                'fee' => 50.00,
                'fee_description' => '₱50 per employee',
                'processing_time_minutes' => 180,
                'icon' => '🏥',
                'color' => 'sea-green',
                'requirements' => [
                    ['requirement' => 'Chest X-ray (all)', 'where_to_secure' => 'Private Hospital/Clinic'],
                    ['requirement' => 'Urinalysis (food handlers, water refilling)', 'where_to_secure' => 'Municipal Health Office or Private Lab'],
                    ['requirement' => 'Fecalysis (food handlers)', 'where_to_secure' => 'Municipal Health Office or Private Lab'],
                    ['requirement' => 'HEPA-A test (cooks and servers)', 'where_to_secure' => 'Private Laboratory'],
                ],
                'steps' => [
                    ['step_number' => 1, 'step_type' => 'client', 'description' => 'Submit request and present required laboratory results', 'processing_time_minutes' => 10, 'responsible_person' => 'Sanitary Inspector', 'fee' => 0.00],
                    ['step_number' => 2, 'step_type' => 'agency', 'description' => 'Review documents and conduct sanitary inspection of establishment', 'processing_time_minutes' => 60, 'responsible_person' => 'Sanitary Inspector', 'fee' => 0.00],
                    ['step_number' => 3, 'step_type' => 'client', 'description' => 'Pay health certificate fees at Treasury Office', 'processing_time_minutes' => 15, 'responsible_person' => 'Treasurer', 'fee' => 50.00],
                    ['step_number' => 4, 'step_type' => 'agency', 'description' => 'Process sanitary permit and health IDs', 'processing_time_minutes' => 60, 'responsible_person' => 'Sanitary Inspector', 'fee' => 0.00],
                    ['step_number' => 5, 'step_type' => 'agency', 'description' => 'Release sanitary permit and health IDs', 'processing_time_minutes' => 35, 'responsible_person' => 'Sanitary Inspector', 'fee' => 0.00],
                ],
            ],

            // ========================================
            // MUNICIPAL CIVIL REGISTRAR (7 services)
            // ========================================

            [
                'name' => 'Registration of Birth (Timely/Delayed, Legitimate/Illegitimate, Out-of-Town)',
                'slug' => 'birth-registration',
                'department' => 'Municipal Civil Registrar',
                'description' => 'Registration of birth certificates for newborns and delayed registration',
                'classification' => 'Simple',
                'type' => 'G2C',
                'who_may_avail' => 'Parents, guardians, or authorized representatives',
                'fee' => 0.00,
                'fee_description' => 'FREE for timely registration',
                'processing_time_minutes' => 45,
                'icon' => '👶',
                'color' => 'tiger-orange',
                'requirements' => [
                    ['requirement' => 'Certificate of Live Birth (duly accomplished)', 'where_to_secure' => 'Hospital or Midwife'],
                    ['requirement' => 'Valid ID of informant', 'where_to_secure' => 'Government or Private Institution'],
                    ['requirement' => 'Marriage Certificate of parents (if married)', 'where_to_secure' => 'Municipal Civil Registrar'],
                    ['requirement' => 'Affidavit of Acknowledgment (for illegitimate children)', 'where_to_secure' => 'Notary Public'],
                ],
                'steps' => [
                    ['step_number' => 1, 'step_type' => 'client', 'description' => 'Submit Certificate of Live Birth and required documents', 'processing_time_minutes' => 10, 'responsible_person' => 'Registration Officer', 'fee' => 0.00],
                    ['step_number' => 2, 'step_type' => 'agency', 'description' => 'Review documents for completeness and accuracy', 'processing_time_minutes' => 15, 'responsible_person' => 'Registration Officer', 'fee' => 0.00],
                    ['step_number' => 3, 'step_type' => 'agency', 'description' => 'Encode and process birth registration', 'processing_time_minutes' => 15, 'responsible_person' => 'Registration Officer', 'fee' => 0.00],
                    ['step_number' => 4, 'step_type' => 'agency', 'description' => 'Issue acknowledgment receipt', 'processing_time_minutes' => 5, 'responsible_person' => 'Registration Officer', 'fee' => 0.00],
                ],
            ],

            [
                'name' => 'Registration of Marriage (Timely/Delayed) & License Application',
                'slug' => 'marriage-license',
                'department' => 'Municipal Civil Registrar',
                'description' => 'Application for marriage license and registration of marriage certificate',
                'classification' => 'Simple',
                'type' => 'G2C',
                'who_may_avail' => 'Filipino citizens and foreigners intending to get married',
                'fee' => 72.00,
                'fee_description' => '₱72 per license',
                'processing_time_minutes' => 25,
                'icon' => '💍',
                'color' => 'tiger-orange',
                'requirements' => [
                    ['requirement' => 'Birth Certificate (PSA Authenticated)', 'where_to_secure' => 'PSA Office'],
                    ['requirement' => 'CENOMAR (Certificate of No Marriage)', 'where_to_secure' => 'PSA Office'],
                    ['requirement' => 'Valid ID (both parties)', 'where_to_secure' => 'Government or Private Institution'],
                    ['requirement' => 'Marriage Counseling Certificate', 'where_to_secure' => 'Municipal Civil Registrar'],
                    ['requirement' => 'Passport for foreigners', 'where_to_secure' => 'DFA'],
                ],
                'steps' => [
                    ['step_number' => 1, 'step_type' => 'client', 'description' => 'Submit marriage license application and required documents', 'processing_time_minutes' => 10, 'responsible_person' => 'Registration Officer', 'fee' => 0.00],
                    ['step_number' => 2, 'step_type' => 'agency', 'description' => 'Review documents and interview applicants', 'processing_time_minutes' => 5, 'responsible_person' => 'Registration Officer', 'fee' => 0.00],
                    ['step_number' => 3, 'step_type' => 'client', 'description' => 'Pay marriage license fee at Treasury Office', 'processing_time_minutes' => 5, 'responsible_person' => 'Treasurer', 'fee' => 72.00],
                    ['step_number' => 4, 'step_type' => 'agency', 'description' => 'Process and post marriage license for 10-day publication period', 'processing_time_minutes' => 2, 'responsible_person' => 'Registration Officer', 'fee' => 0.00],
                    ['step_number' => 5, 'step_type' => 'agency', 'description' => 'Issue marriage license after publication period', 'processing_time_minutes' => 3, 'responsible_person' => 'Registration Officer', 'fee' => 0.00],
                ],
            ],

            [
                'name' => 'Registration of Death (Timely/Delayed)',
                'slug' => 'death-registration',
                'department' => 'Municipal Civil Registrar',
                'description' => 'Registration of death certificates',
                'classification' => 'Simple',
                'type' => 'G2C',
                'who_may_avail' => 'Relatives or authorized representatives of the deceased',
                'fee' => 0.00,
                'fee_description' => 'FREE',
                'processing_time_minutes' => 30,
                'icon' => '🕊️',
                'color' => 'tiger-orange',
                'requirements' => [
                    ['requirement' => 'Certificate of Death (duly accomplished)', 'where_to_secure' => 'Attending Physician or Hospital'],
                    ['requirement' => 'Valid ID of informant', 'where_to_secure' => 'Government or Private Institution'],
                    ['requirement' => 'Sworn statement for delayed registration', 'where_to_secure' => 'Notary Public'],
                ],
                'steps' => [
                    ['step_number' => 1, 'step_type' => 'client', 'description' => 'Submit Certificate of Death and required documents', 'processing_time_minutes' => 10, 'responsible_person' => 'Registration Officer', 'fee' => 0.00],
                    ['step_number' => 2, 'step_type' => 'agency', 'description' => 'Review and verify documents', 'processing_time_minutes' => 10, 'responsible_person' => 'Registration Officer', 'fee' => 0.00],
                    ['step_number' => 3, 'step_type' => 'agency', 'description' => 'Process death registration and issue burial permit', 'processing_time_minutes' => 10, 'responsible_person' => 'Registration Officer', 'fee' => 0.00],
                ],
            ],

            [
                'name' => 'Issuance of Forms (1A, 1B, 1C, 2A, 2B, 2C, 3A, 3B, CTC)',
                'slug' => 'civil-registry-forms',
                'department' => 'Municipal Civil Registrar',
                'description' => 'Issuance of various civil registry forms for birth, marriage, and death',
                'classification' => 'Simple',
                'type' => 'G2C',
                'who_may_avail' => 'All',
                'fee' => 20.00,
                'fee_description' => '₱20 per form',
                'processing_time_minutes' => 15,
                'icon' => '📄',
                'color' => 'tiger-orange',
                'requirements' => [
                    ['requirement' => 'Valid ID', 'where_to_secure' => 'Government or Private Institution'],
                ],
                'steps' => [
                    ['step_number' => 1, 'step_type' => 'client', 'description' => 'Request specific form at Civil Registrar Office', 'processing_time_minutes' => 5, 'responsible_person' => 'Registration Officer', 'fee' => 0.00],
                    ['step_number' => 2, 'step_type' => 'client', 'description' => 'Pay form fee at Treasury Office', 'processing_time_minutes' => 5, 'responsible_person' => 'Treasurer', 'fee' => 20.00],
                    ['step_number' => 3, 'step_type' => 'agency', 'description' => 'Issue requested form', 'processing_time_minutes' => 5, 'responsible_person' => 'Registration Officer', 'fee' => 0.00],
                ],
            ],

            [
                'name' => 'Petition for Clerical Error/Change of Name (RA9048 & 10172)',
                'slug' => 'clerical-error-correction',
                'department' => 'Municipal Civil Registrar',
                'description' => 'Administrative correction of clerical or typographical errors in civil registry documents',
                'classification' => 'Complex',
                'type' => 'G2C',
                'who_may_avail' => 'Owner of the document or authorized representative',
                'fee' => 500.00,
                'fee_description' => '₱500',
                'processing_time_minutes' => 10080, // 7 days
                'icon' => '✏️',
                'color' => 'tiger-orange',
                'requirements' => [
                    ['requirement' => 'Petition Form (duly accomplished)', 'where_to_secure' => 'Municipal Civil Registrar'],
                    ['requirement' => 'Original/Certified True Copy of document to be corrected', 'where_to_secure' => 'Municipal Civil Registrar or PSA'],
                    ['requirement' => 'Supporting documents (e.g., birth certificate, school records, baptismal certificate)', 'where_to_secure' => 'Various institutions'],
                    ['requirement' => 'Valid ID', 'where_to_secure' => 'Government or Private Institution'],
                    ['requirement' => 'Affidavit of Two Disinterested Persons', 'where_to_secure' => 'Notary Public'],
                ],
                'steps' => [
                    ['step_number' => 1, 'step_type' => 'client', 'description' => 'Submit petition and all required documents', 'processing_time_minutes' => 30, 'responsible_person' => 'Registration Officer', 'fee' => 0.00],
                    ['step_number' => 2, 'step_type' => 'agency', 'description' => 'Review petition and verify documents', 'processing_time_minutes' => 240, 'responsible_person' => 'Municipal Civil Registrar', 'fee' => 0.00],
                    ['step_number' => 3, 'step_type' => 'client', 'description' => 'Pay processing fee at Treasury Office', 'processing_time_minutes' => 15, 'responsible_person' => 'Treasurer', 'fee' => 500.00],
                    ['step_number' => 4, 'step_type' => 'agency', 'description' => 'Process correction and publish in bulletin board for 10 consecutive days', 'processing_time_minutes' => 14400, 'responsible_person' => 'Registration Officer', 'fee' => 0.00],
                    ['step_number' => 5, 'step_type' => 'agency', 'description' => 'Issue corrected certificate', 'processing_time_minutes' => 30, 'responsible_person' => 'Municipal Civil Registrar', 'fee' => 0.00],
                ],
            ],

            [
                'name' => 'Registration of Court Orders & Legal Instruments',
                'slug' => 'court-orders-registration',
                'department' => 'Municipal Civil Registrar',
                'description' => 'Registration of judicial decrees, adoption orders, legitimation, and other court-issued documents affecting civil registry',
                'classification' => 'Simple',
                'type' => 'G2C',
                'who_may_avail' => 'Individuals with court-issued orders',
                'fee' => 100.00,
                'fee_description' => '₱100',
                'processing_time_minutes' => 60,
                'icon' => '⚖️',
                'color' => 'tiger-orange',
                'requirements' => [
                    ['requirement' => 'Certified True Copy of Court Order/Decree', 'where_to_secure' => 'Issuing Court'],
                    ['requirement' => 'Original/Certified True Copy of affected civil registry document', 'where_to_secure' => 'Municipal Civil Registrar'],
                    ['requirement' => 'Valid ID', 'where_to_secure' => 'Government or Private Institution'],
                ],
                'steps' => [
                    ['step_number' => 1, 'step_type' => 'client', 'description' => 'Submit court order and required documents', 'processing_time_minutes' => 10, 'responsible_person' => 'Registration Officer', 'fee' => 0.00],
                    ['step_number' => 2, 'step_type' => 'agency', 'description' => 'Verify authenticity of court order and review documents', 'processing_time_minutes' => 20, 'responsible_person' => 'Municipal Civil Registrar', 'fee' => 0.00],
                    ['step_number' => 3, 'step_type' => 'client', 'description' => 'Pay registration fee at Treasury Office', 'processing_time_minutes' => 10, 'responsible_person' => 'Treasurer', 'fee' => 100.00],
                    ['step_number' => 4, 'step_type' => 'agency', 'description' => 'Annotate civil registry document and process registration', 'processing_time_minutes' => 20, 'responsible_person' => 'Registration Officer', 'fee' => 0.00],
                ],
            ],

            [
                'name' => 'Supplemental Report',
                'slug' => 'supplemental-report',
                'department' => 'Municipal Civil Registrar',
                'description' => 'Filing of supplemental reports to add missing information in civil registry documents',
                'classification' => 'Simple',
                'type' => 'G2C',
                'who_may_avail' => 'Owner of the document or authorized representative',
                'fee' => 100.00,
                'fee_description' => '₱100',
                'processing_time_minutes' => 90,
                'icon' => '📝',
                'color' => 'tiger-orange',
                'requirements' => [
                    ['requirement' => 'Affidavit explaining the missing information', 'where_to_secure' => 'Notary Public'],
                    ['requirement' => 'Supporting documents (e.g., school records, baptismal certificate)', 'where_to_secure' => 'Various institutions'],
                    ['requirement' => 'Original/Certified True Copy of civil registry document', 'where_to_secure' => 'Municipal Civil Registrar'],
                    ['requirement' => 'Valid ID', 'where_to_secure' => 'Government or Private Institution'],
                ],
                'steps' => [
                    ['step_number' => 1, 'step_type' => 'client', 'description' => 'Submit affidavit and all supporting documents', 'processing_time_minutes' => 15, 'responsible_person' => 'Registration Officer', 'fee' => 0.00],
                    ['step_number' => 2, 'step_type' => 'agency', 'description' => 'Review and evaluate completeness of documents', 'processing_time_minutes' => 30, 'responsible_person' => 'Municipal Civil Registrar', 'fee' => 0.00],
                    ['step_number' => 3, 'step_type' => 'client', 'description' => 'Pay filing fee at Treasury Office', 'processing_time_minutes' => 10, 'responsible_person' => 'Treasurer', 'fee' => 100.00],
                    ['step_number' => 4, 'step_type' => 'agency', 'description' => 'Process and attach supplemental report to civil registry', 'processing_time_minutes' => 30, 'responsible_person' => 'Registration Officer', 'fee' => 0.00],
                    ['step_number' => 5, 'step_type' => 'agency', 'description' => 'Issue acknowledgment receipt', 'processing_time_minutes' => 5, 'responsible_person' => 'Registration Officer', 'fee' => 0.00],
                ],
            ],

            // ========================================
            // MAYOR'S OFFICE (4 services)
            // ========================================

            [
                'name' => "Issuance of Mayor's Clearance",
                'slug' => 'mayors-clearance',
                'department' => "Mayor's Office",
                'description' => "Clearance issued by the Mayor's Office for various purposes (employment, business, travel, etc.)",
                'classification' => 'Simple',
                'type' => 'G2C',
                'who_may_avail' => 'All residents',
                'fee' => 80.00,
                'fee_description' => '₱80',
                'processing_time_minutes' => 45,
                'icon' => '🏛️',
                'color' => 'golden-glow',
                'requirements' => [
                    ['requirement' => 'Barangay Clearance', 'where_to_secure' => 'Barangay Hall'],
                    ['requirement' => 'Valid ID', 'where_to_secure' => 'Government or Private Institution'],
                    ['requirement' => 'Cedula (Community Tax Certificate)', 'where_to_secure' => 'Treasury Office'],
                    ['requirement' => '1x1 ID Picture (2 pcs)', 'where_to_secure' => 'Photo Studio'],
                ],
                'steps' => [
                    ['step_number' => 1, 'step_type' => 'client', 'description' => 'Submit application form and required documents', 'processing_time_minutes' => 10, 'responsible_person' => "Mayor's Office Staff", 'fee' => 0.00],
                    ['step_number' => 2, 'step_type' => 'agency', 'description' => 'Verify documents and check for derogatory records', 'processing_time_minutes' => 15, 'responsible_person' => "Mayor's Office Staff", 'fee' => 0.00],
                    ['step_number' => 3, 'step_type' => 'client', 'description' => 'Pay clearance fee at Treasury Office', 'processing_time_minutes' => 10, 'responsible_person' => 'Treasurer', 'fee' => 80.00],
                    ['step_number' => 4, 'step_type' => 'agency', 'description' => "Prepare Mayor's Clearance for signature", 'processing_time_minutes' => 5, 'responsible_person' => "Mayor's Office Staff", 'fee' => 0.00],
                    ['step_number' => 5, 'step_type' => 'agency', 'description' => "Release signed Mayor's Clearance", 'processing_time_minutes' => 5, 'responsible_person' => "Mayor's Office Staff", 'fee' => 0.00],
                ],
            ],

            [
                'name' => 'Issuance of Business Permit',
                'slug' => 'business-permit',
                'department' => "Mayor's Office",
                'description' => 'Annual permit required for operating businesses within the municipality',
                'classification' => 'Complex',
                'type' => 'G2B',
                'who_may_avail' => 'Business owners and entrepreneurs',
                'fee' => 500.00,
                'fee_description' => '₱500+ (varies by business type and capital)',
                'processing_time_minutes' => 240, // 4 hours
                'icon' => '🏢',
                'color' => 'golden-glow',
                'requirements' => [
                    ['requirement' => 'DTI/SEC/CDA Registration', 'where_to_secure' => 'DTI/SEC/CDA Office'],
                    ['requirement' => 'Barangay Business Clearance', 'where_to_secure' => 'Barangay Hall'],
                    ['requirement' => 'Sanitary Permit', 'where_to_secure' => 'Municipal Health Office'],
                    ['requirement' => 'Fire Safety Inspection Certificate', 'where_to_secure' => 'Bureau of Fire Protection'],
                    ['requirement' => 'Zoning Clearance', 'where_to_secure' => 'Municipal Planning Office'],
                    ['requirement' => 'Community Tax Certificate (Cedula)', 'where_to_secure' => 'Treasury Office'],
                    ['requirement' => 'Proof of business location (contract/deed)', 'where_to_secure' => 'Property owner'],
                ],
                'steps' => [
                    ['step_number' => 1, 'step_type' => 'client', 'description' => 'Submit application and all required clearances', 'processing_time_minutes' => 20, 'responsible_person' => 'Business Permits & Licensing Office', 'fee' => 0.00],
                    ['step_number' => 2, 'step_type' => 'agency', 'description' => 'Evaluate application and assess fees', 'processing_time_minutes' => 60, 'responsible_person' => 'Business Permits & Licensing Officer', 'fee' => 0.00],
                    ['step_number' => 3, 'step_type' => 'client', 'description' => 'Pay assessed fees at Treasury Office', 'processing_time_minutes' => 20, 'responsible_person' => 'Treasury Officer', 'fee' => 500.00],
                    ['step_number' => 4, 'step_type' => 'agency', 'description' => 'Process business permit and prepare for approval', 'processing_time_minutes' => 120, 'responsible_person' => 'Business Permits & Licensing Office', 'fee' => 0.00],
                    ['step_number' => 5, 'step_type' => 'agency', 'description' => 'Release approved business permit', 'processing_time_minutes' => 20, 'responsible_person' => 'Business Permits & Licensing Officer', 'fee' => 0.00],
                ],
            ],

            [
                'name' => 'Issuance of Working Permit',
                'slug' => 'working-permit',
                'department' => "Mayor's Office",
                'description' => 'Permit for minors (15-17 years old) seeking employment',
                'classification' => 'Simple',
                'type' => 'G2C',
                'who_may_avail' => 'Minors aged 15-17 years old and their parents/guardians',
                'fee' => 50.00,
                'fee_description' => '₱50',
                'processing_time_minutes' => 60,
                'icon' => '👷',
                'color' => 'golden-glow',
                'requirements' => [
                    ['requirement' => 'Birth Certificate (PSA)', 'where_to_secure' => 'PSA Office'],
                    ['requirement' => 'Medical Certificate', 'where_to_secure' => 'Municipal Health Office or Private Clinic'],
                    ['requirement' => 'School ID or Certificate of Enrollment', 'where_to_secure' => 'School'],
                    ['requirement' => 'Written consent from parents/guardians', 'where_to_secure' => 'Parent/Guardian'],
                    ['requirement' => 'Valid ID of parent/guardian', 'where_to_secure' => 'Government or Private Institution'],
                    ['requirement' => '2x2 ID picture (2 pcs)', 'where_to_secure' => 'Photo Studio'],
                ],
                'steps' => [
                    ['step_number' => 1, 'step_type' => 'client', 'description' => 'Submit application with parent/guardian and required documents', 'processing_time_minutes' => 15, 'responsible_person' => "Mayor's Office Staff", 'fee' => 0.00],
                    ['step_number' => 2, 'step_type' => 'agency', 'description' => 'Interview minor and parent/guardian, verify documents', 'processing_time_minutes' => 20, 'responsible_person' => 'Social Welfare Officer', 'fee' => 0.00],
                    ['step_number' => 3, 'step_type' => 'client', 'description' => 'Pay permit fee at Treasury Office', 'processing_time_minutes' => 10, 'responsible_person' => 'Treasurer', 'fee' => 50.00],
                    ['step_number' => 4, 'step_type' => 'agency', 'description' => 'Process and prepare working permit', 'processing_time_minutes' => 10, 'responsible_person' => "Mayor's Office Staff", 'fee' => 0.00],
                    ['step_number' => 5, 'step_type' => 'agency', 'description' => 'Release approved working permit', 'processing_time_minutes' => 5, 'responsible_person' => "Mayor's Office Staff", 'fee' => 0.00],
                ],
            ],

            [
                'name' => "Motorized Tricycle Operator's Permit",
                'slug' => 'tricycle-operators-permit',
                'department' => "Mayor's Office",
                'description' => 'Annual franchise permit for tricycle operators within the municipality',
                'classification' => 'Simple',
                'type' => 'G2C',
                'who_may_avail' => 'Motorized tricycle owners and operators',
                'fee' => 300.00,
                'fee_description' => '₱300 per unit annually',
                'processing_time_minutes' => 120,
                'icon' => '🛺',
                'color' => 'golden-glow',
                'requirements' => [
                    ['requirement' => "Driver's License (valid and appropriate restriction)", 'where_to_secure' => 'LTO'],
                    ['requirement' => 'Certificate of Registration (OR) of Tricycle', 'where_to_secure' => 'LTO'],
                    ['requirement' => 'Official Receipt of current registration', 'where_to_secure' => 'LTO'],
                    ['requirement' => 'Barangay Clearance', 'where_to_secure' => 'Barangay Hall'],
                    ['requirement' => 'Community Tax Certificate (Cedula)', 'where_to_secure' => 'Treasury Office'],
                    ['requirement' => '2x2 ID picture (3 pcs)', 'where_to_secure' => 'Photo Studio'],
                    ['requirement' => 'TODA Membership Certificate (if applicable)', 'where_to_secure' => 'Tricycle Operators & Drivers Association'],
                ],
                'steps' => [
                    ['step_number' => 1, 'step_type' => 'client', 'description' => 'Submit application and all required documents', 'processing_time_minutes' => 15, 'responsible_person' => 'Traffic & Transport Office', 'fee' => 0.00],
                    ['step_number' => 2, 'step_type' => 'agency', 'description' => 'Inspect tricycle unit for roadworthiness and compliance', 'processing_time_minutes' => 30, 'responsible_person' => 'Traffic Officer', 'fee' => 0.00],
                    ['step_number' => 3, 'step_type' => 'agency', 'description' => 'Verify documents and assess permit fees', 'processing_time_minutes' => 20, 'responsible_person' => 'Traffic & Transport Officer', 'fee' => 0.00],
                    ['step_number' => 4, 'step_type' => 'client', 'description' => 'Pay franchise permit fee at Treasury Office', 'processing_time_minutes' => 15, 'responsible_person' => 'Treasurer', 'fee' => 300.00],
                    ['step_number' => 5, 'step_type' => 'agency', 'description' => 'Process and print operator permit and plate', 'processing_time_minutes' => 30, 'responsible_person' => 'Traffic & Transport Office', 'fee' => 0.00],
                    ['step_number' => 6, 'step_type' => 'agency', 'description' => 'Release MTOP and plate', 'processing_time_minutes' => 10, 'responsible_person' => 'Traffic & Transport Officer', 'fee' => 0.00],
                ],
            ],

            // ========================================
            // PLANNING AND DEVELOPMENT (2 services)
            // ========================================

            [
                'name' => 'Zoning Certification/Land Issuance',
                'slug' => 'zoning-certification',
                'department' => 'Municipal Planning and Development Office',
                'description' => 'Certification of land use classification and zoning in accordance with Comprehensive Land Use Plan',
                'classification' => 'Simple',
                'type' => 'G2C',
                'who_may_avail' => 'Property owners and authorized representatives',
                'fee' => 150.00,
                'fee_description' => '₱150',
                'processing_time_minutes' => 180, // 3 hours
                'icon' => '🗺️',
                'color' => 'burnt-tangerine',
                'requirements' => [
                    ['requirement' => 'Tax Declaration of property', 'where_to_secure' => 'Municipal Assessor Office'],
                    ['requirement' => 'Location Map/Vicinity Map', 'where_to_secure' => 'Property owner or Geodetic Engineer'],
                    ['requirement' => 'Valid ID', 'where_to_secure' => 'Government or Private Institution'],
                    ['requirement' => 'Authorization letter (if representative)', 'where_to_secure' => 'Property owner'],
                ],
                'steps' => [
                    ['step_number' => 1, 'step_type' => 'client', 'description' => 'Submit request for zoning certification and required documents', 'processing_time_minutes' => 10, 'responsible_person' => 'Planning Officer', 'fee' => 0.00],
                    ['step_number' => 2, 'step_type' => 'agency', 'description' => 'Verify property location against Comprehensive Land Use Plan and zoning ordinance', 'processing_time_minutes' => 60, 'responsible_person' => 'Planning Officer', 'fee' => 0.00],
                    ['step_number' => 3, 'step_type' => 'client', 'description' => 'Pay certification fee at Treasury Office', 'processing_time_minutes' => 15, 'responsible_person' => 'Treasurer', 'fee' => 150.00],
                    ['step_number' => 4, 'step_type' => 'agency', 'description' => 'Prepare zoning certification for approval', 'processing_time_minutes' => 60, 'responsible_person' => 'Municipal Planning Officer', 'fee' => 0.00],
                    ['step_number' => 5, 'step_type' => 'agency', 'description' => 'Release approved zoning certification', 'processing_time_minutes' => 35, 'responsible_person' => 'Municipal Planning Office Staff', 'fee' => 0.00],
                ],
            ],

            [
                'name' => 'Locational Clearance for Business Permit',
                'slug' => 'locational-clearance',
                'department' => 'Municipal Planning and Development Office',
                'description' => 'Clearance certifying that proposed business location complies with zoning ordinance',
                'classification' => 'Simple',
                'type' => 'G2B',
                'who_may_avail' => 'Business owners and entrepreneurs',
                'fee' => 200.00,
                'fee_description' => '₱200',
                'processing_time_minutes' => 240, // 4 hours
                'icon' => '📍',
                'color' => 'burnt-tangerine',
                'requirements' => [
                    ['requirement' => 'DTI/SEC/CDA Registration', 'where_to_secure' => 'DTI/SEC/CDA Office'],
                    ['requirement' => 'Tax Declaration of business location', 'where_to_secure' => 'Municipal Assessor Office'],
                    ['requirement' => 'Contract of Lease or Deed of Sale', 'where_to_secure' => 'Property owner'],
                    ['requirement' => 'Location/Vicinity Map', 'where_to_secure' => 'Geodetic Engineer or Property owner'],
                    ['requirement' => 'Barangay Clearance', 'where_to_secure' => 'Barangay Hall'],
                    ['requirement' => 'Valid ID', 'where_to_secure' => 'Government or Private Institution'],
                ],
                'steps' => [
                    ['step_number' => 1, 'step_type' => 'client', 'description' => 'Submit application for locational clearance with required documents', 'processing_time_minutes' => 15, 'responsible_person' => 'Planning Officer', 'fee' => 0.00],
                    ['step_number' => 2, 'step_type' => 'agency', 'description' => 'Conduct ocular inspection of business site', 'processing_time_minutes' => 60, 'responsible_person' => 'Planning Officer', 'fee' => 0.00],
                    ['step_number' => 3, 'step_type' => 'agency', 'description' => 'Evaluate compliance with zoning ordinance and land use plan', 'processing_time_minutes' => 90, 'responsible_person' => 'Municipal Planning Officer', 'fee' => 0.00],
                    ['step_number' => 4, 'step_type' => 'client', 'description' => 'Pay clearance fee at Treasury Office', 'processing_time_minutes' => 15, 'responsible_person' => 'Treasurer', 'fee' => 200.00],
                    ['step_number' => 5, 'step_type' => 'agency', 'description' => 'Prepare and approve locational clearance', 'processing_time_minutes' => 45, 'responsible_person' => 'Municipal Planning Officer', 'fee' => 0.00],
                    ['step_number' => 6, 'step_type' => 'agency', 'description' => 'Release locational clearance', 'processing_time_minutes' => 15, 'responsible_person' => 'Planning Office Staff', 'fee' => 0.00],
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
