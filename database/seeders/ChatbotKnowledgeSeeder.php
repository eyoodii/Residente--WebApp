<?php

namespace Database\Seeders;

use App\Models\ChatbotKnowledge;
use Illuminate\Database\Seeder;

class ChatbotKnowledgeSeeder extends Seeder
{
    public function run(): void
    {
        $intents = [
            // ── PERMITS ────────────────────────────────────────────────────
            [
                'intent_name'          => 'business_permit_new',
                'category'             => 'Permits',
                'trigger_keywords_en'  => ['business permit', 'mayor permit', 'new business', 'start business', 'open business', 'business renewal', 'renew business', 'business license', 'business requirements', 'BPLO', 'DTI registration', 'SEC registration', 'business application'],
                'trigger_keywords_fil' => ['negosyo', 'permiso ng negosyo', 'bagong negosyo', 'buksan negosyo', 'renewal ng negosyo', 'lisensya ng negosyo', 'mayor permit', 'pagnenegosyo', 'magtayo ng negosyo', 'magtinda'],
                'official_response'    => "Upang makakuha ng Business/Mayor's Permit, kailangan ang mga sumusunod:\n\n✅ Duly accomplished Business Permit Application Form\n✅ Barangay Business Clearance\n✅ DTI/SEC/CDA Registration Certificate\n✅ Lease Contract o Tax Declaration (proof of location)\n✅ Valid government-issued ID\n\n📍 **BPLO Office**, Municipal Hall, Buguey\n🕐 Lunes–Biyernes, 8AM–5PM\n💰 Processing fee: ₱500 minimum (new), varies for renewal\n⏱️ Processing time: 3–5 working days\n\nPara sa mas tumpak na listahan batay sa iyong situasyon, subukan ang aming guided form.",
                'response_type'        => 'guided_form',
                'linked_form_flow'     => 'business_permit_new',
            ],
            [
                'intent_name'          => 'barangay_clearance',
                'category'             => 'Permits',
                'trigger_keywords_en'  => ['barangay clearance', 'brgy clearance', 'community clearance', 'barangay certificate', 'clearance for employment', 'clearance for work', 'barangay requirement'],
                'trigger_keywords_fil' => ['barangay clearance', 'sertipiko ng barangay', 'clearance sa barangay', 'brgy clearance', 'kapitan', 'barangay hall', 'kliyarans'],
                'official_response'    => "Ang Barangay Clearance ay karaniwang nangangailangan ng:\n\n✅ Valid government-issued ID\n✅ Proof of residency (utility bill o barangay certificate)\n✅ Accomplished application form (available sa barangay hall)\n✅ Bayad: ₱50–₱150 (depende sa layunin)\n\n📍 Pumunta sa iyong **Barangay Hall**\n🕐 Lunes–Biyernes, 8AM–5PM\n⏱️ Processing time: Same day (1–2 oras)",
                'response_type'        => 'guided_form',
                'linked_form_flow'     => 'barangay_clearance',
            ],
            [
                'intent_name'          => 'building_permit',
                'category'             => 'Permits',
                'trigger_keywords_en'  => ['building permit', 'construction permit', 'build house', 'renovation permit', 'fence permit', 'structural plan', 'engineering office', 'construction requirements', 'construct', 'build', 'house construction'],
                'trigger_keywords_fil' => ['permit ng gusali', 'konstruksiyon', 'magtayo ng bahay', 'renovasiyon', 'bakod', 'pagtayo', 'engineering', 'pagpapatayo', 'patayo ng bahay', 'magpatayo', 'bahay', 'gusali', 'itayo'],
                'official_response'    => "Para sa Building Permit sa Municipality of Buguey:\n\n✅ Accomplished Building Permit Application Form\n✅ Site Development Plan (approved by MPDC)\n✅ Structural design plans (signed by licensed engineer)\n✅ Tax Declaration / Land Title (proof of ownership)\n✅ Barangay Clearance\n✅ Locational Clearance (mula sa MPDC)\n✅ Fire Safety Evaluation Clearance (BFP)\n\n📍 **Engineering Office**, Municipal Hall\n🕐 Lunes–Biyernes, 8AM–5PM\n⏱️ Processing: 5–10 araw ng trabaho\n💰 Fee: Depende sa laki at uri ng construction",
                'response_type'        => 'text',
            ],
            [
                'intent_name'          => 'locational_clearance',
                'category'             => 'Permits',
                'trigger_keywords_en'  => ['locational clearance', 'zoning clearance', 'zoning permit', 'land use', 'location clearance', 'MPDC'],
                'trigger_keywords_fil' => ['locational clearance', 'zoning', 'gamit ng lupa', 'MPDC', 'puwesto', 'lokasyon'],
                'official_response'    => "Para sa Locational Clearance:\n\n✅ Accomplished application form\n✅ Site/location sketch map\n✅ Tax Declaration ng lupa\n✅ Letter of intent / purpose\n✅ Barangay Clearance\n\n📍 **MPDC Office**, Municipal Hall\n🕐 Lunes–Biyernes, 8AM–5PM\n⏱️ Processing: 3–5 araw\n💰 Fee: Depende sa laki ng property",
                'response_type'        => 'text',
            ],
            [
                'intent_name'          => 'fire_safety_clearance',
                'category'             => 'Permits',
                'trigger_keywords_en'  => ['fire safety', 'fire clearance', 'BFP', 'fire certificate', 'fire inspection', 'fire permit'],
                'trigger_keywords_fil' => ['fire safety', 'sunog', 'BFP', 'fire clearance', 'inspeksyon ng sunog', 'fire permit'],
                'official_response'    => "Para sa Fire Safety Inspection Certificate (FSIC):\n\n✅ Accomplished application form\n✅ Building Permit o Occupancy Permit\n✅ Fire insurance policy (kung applicable)\n✅ Valid ID ng owner/applicant\n✅ Bayad: Depende sa floor area at occupancy type\n\n📍 **Bureau of Fire Protection (BFP)**, Buguey Fire Station\n🕐 Lunes–Biyernes, 8AM–5PM\n⏱️ Processing: 3–5 araw (may inspection)",
                'response_type'        => 'text',
            ],

            // ── CIVIL REGISTRY ─────────────────────────────────────────────
            [
                'intent_name'          => 'birth_certificate',
                'category'             => 'Civil Registry',
                'trigger_keywords_en'  => ['birth certificate', 'birth cert', 'NSO', 'PSA birth', 'baptismal', 'certificate of live birth', 'COLB', 'late registration of birth', 'civil registrar birth'],
                'trigger_keywords_fil' => ['sertipiko ng kapanganakan', 'birth cert', 'NSO', 'PSA', 'kapanganakan', 'ipinanganak', 'rehistro ng kapanganakan', 'civil registrar'],
                'official_response'    => "Para sa kopya ng Birth Certificate:\n\n**Sa Lokal (Civil Registrar, Municipal Hall):**\n✅ Request form (available sa Civil Registrar)\n✅ Valid government-issued ID\n✅ Bayad: ₱100 per copy\n⏱️ Same day (kung may record sa local registry)\n\n**Para sa PSA-certified copy:**\n✅ Online: serbilis.psa.gov.ph\n✅ Walk-in: PSA Office, Tuguegarao City\n✅ Bayad: ₱155 (walk-in) / ₱155 + delivery fee (online)\n\n**Para sa Late Registration of Birth:**\n✅ Pumunta sa Civil Registrar para sa requirements\n\n📍 **Civil Registrar Office**, Ground Floor, Municipal Hall",
                'response_type'        => 'text',
            ],
            [
                'intent_name'          => 'death_certificate',
                'category'             => 'Civil Registry',
                'trigger_keywords_en'  => ['death certificate', 'death cert', 'deceased', 'death record', 'report death', 'registration of death'],
                'trigger_keywords_fil' => ['sertipiko ng kamatayan', 'patay', 'namatay', 'pumanaw', 'kamatayan', 'pagkamatay'],
                'official_response'    => "Para sa Death Certificate:\n\n✅ Request form (available sa Civil Registrar)\n✅ Valid ID ng nagre-request\n✅ Proof ng relasyon sa namatay (kung kamag-anak)\n✅ Bayad: ₱100 per copy\n\n**Para sa Registration of Death (newly deceased):**\n✅ Medical Certificate of Death (signed by attending physician)\n✅ Burial Permit Application\n\n📍 **Civil Registrar Office**, Municipal Hall\n🕐 Lunes–Biyernes, 8AM–5PM\n⏱️ Same day (kung may record)",
                'response_type'        => 'text',
            ],
            [
                'intent_name'          => 'marriage_certificate',
                'category'             => 'Civil Registry',
                'trigger_keywords_en'  => ['marriage certificate', 'marriage cert', 'marriage license', 'getting married', 'wedding', 'civil wedding', 'CENOMAR', 'marriage requirements'],
                'trigger_keywords_fil' => ['kasal', 'sertipiko ng kasal', 'marriage license', 'ikakasal', 'ligal na kasal', 'pakasal', 'civil wedding', 'CENOMAR'],
                'official_response'    => "Para sa Marriage License / Certificate:\n\n**Requirements para sa Marriage License:**\n✅ Accomplished application form (both parties)\n✅ Birth Certificate ng dalawang magkasintahan (PSA)\n✅ Certificate of No Marriage (CENOMAR) mula PSA\n✅ Barangay Clearance ng dalawang party\n✅ 2 valid IDs bawat isa\n✅ Pre-marriage counseling certificate (PMCC)\n✅ Community Tax Certificate (Cedula)\n✅ Parental consent (kung 18-20 years old)\n✅ Parental advice (kung 21-24 years old)\n✅ Bayad: ₱200\n\n📍 **Civil Registrar**, Municipal Hall\n🕐 Lunes–Biyernes, 8AM–5PM\n⏱️ 10 araw posting period bago valid ang license",
                'response_type'        => 'text',
            ],

            // ── HEALTH ─────────────────────────────────────────────────────
            [
                'intent_name'          => 'health_certificate',
                'category'             => 'Health',
                'trigger_keywords_en'  => ['health certificate', 'health cert', 'medical certificate', 'fit to work', 'health card', 'sanitary permit', 'food handler'],
                'trigger_keywords_fil' => ['health cert', 'sertipiko ng kalusugan', 'medical', 'malusog', 'fit to work', 'health card', 'sanitary permit'],
                'official_response'    => "Para sa Health Certificate / Sanitary Permit:\n\n✅ Valid ID\n✅ Accomplished health certificate form\n✅ Physical examination sa MHO\n✅ Drug test (kung para sa employment)\n✅ Bayad: ₱50–₱200\n\n📍 **Municipal Health Office (MHO)**, Municipal Hall\n🕐 Lunes–Biyernes, 8AM–5PM\n⏱️ Same day (after examination)",
                'response_type'        => 'text',
            ],
            [
                'intent_name'          => 'anti_rabies',
                'category'             => 'Health',
                'trigger_keywords_en'  => ['anti-rabies', 'rabies', 'dog bite', 'animal bite', 'rabies vaccine', 'vaccination dog', 'bitten by dog', 'cat bite', 'bitten by animal', 'dog attack'],
                'trigger_keywords_fil' => ['kagat ng aso', 'anti-rabies', 'bakuna', 'bakunahan ng aso', 'kinagat ng aso', 'kinagat ako', 'kagat ng pusa', 'hayop na nakakagat', 'nakagat', 'kinakagat', 'aso kagat', 'kagat aso'],
                'official_response'    => "Para sa Anti-Rabies Vaccine / Animal Bite Treatment:\n\n✅ Pumunta **AGAD** sa Municipal Health Office (MHO)\n✅ Magdala ng valid ID\n✅ **LIBRE ang anti-rabies vaccine** para sa lahat ng mamamayan\n✅ Hindi kailangang magdala ng hayop\n\n⚠️ **IMPORTANT:** Huwag antayin — pumunta agad pagkatapos ng kagat!\n✅ Hugasan agad ang sugat ng sabon at tubig ng 15 minuto\n\n📍 **MHO**, Municipal Hall\n🕐 Lunes–Biyernes, 8AM–5PM\n💰 LIBRE",
                'response_type'        => 'text',
            ],
            [
                'intent_name'          => 'prenatal_checkup',
                'category'             => 'Health',
                'trigger_keywords_en'  => ['prenatal', 'pregnancy', 'pregnant', 'maternity', 'checkup pregnancy', 'expecting', 'ob-gyn', 'baby checkup', 'prenatal care'],
                'trigger_keywords_fil' => ['prenatal', 'buntis', 'pagbubuntis', 'maternity', 'tsekap ng buntis', 'nagdadalantao', 'buntis check up', 'buntis tsekap', 'buntis ako', 'magpa check up buntis'],
                'official_response'    => "Para sa Prenatal Checkup at Maternity Services:\n\n✅ Pumunta sa **Municipal Health Office (MHO)** para sa free prenatal checkup\n✅ Dalhin ang valid ID at previous medical records (kung mayroon)\n✅ Regular na checkup ang inirerekumenda (monthly hanggang sa panganganak)\n✅ **LIBRE ang prenatal services** sa MHO\n\n📍 **MHO**, Municipal Hall\n🕐 Lunes–Biyernes, 8AM–5PM\n💰 LIBRE para sa lahat ng buntis na mamamayan ng Buguey",
                'response_type'        => 'text',
            ],

            // ── SOCIAL SERVICES ────────────────────────────────────────────
            [
                'intent_name'          => 'indigency_certificate',
                'category'             => 'Social Services',
                'trigger_keywords_en'  => ['indigency', 'indigent', 'social welfare', 'MSWDO', 'financial assistance', 'certificate of indigency', 'free hospital', 'medical assistance'],
                'trigger_keywords_fil' => ['indigensya', 'sertipiko ng kahirapan', 'mahirap', 'tulong pinansyal', 'MSWDO', 'ayuda', 'patunay ng kahirapan', 'libre sa ospital', 'tulong medikal'],
                'official_response'    => "Para sa Certificate of Indigency:\n\n✅ Valid government-issued ID\n✅ Proof of residency sa Buguey\n✅ Barangay Certificate of Indigency (mula sa barangay hall)\n✅ Accomplished MSWDO application form\n✅ Identification ng intended use (medical, burial, scholarship, legal, etc.)\n\n📍 **MSWDO Office**, Municipal Hall\n🕐 Lunes–Biyernes, 8AM–5PM\n⏱️ 1–2 araw ng trabaho\n💰 **LIBRE** (walang bayad)",
                'response_type'        => 'guided_form',
                'linked_form_flow'     => 'indigency_certificate',
            ],
            [
                'intent_name'          => 'senior_citizen',
                'category'             => 'Social Services',
                'trigger_keywords_en'  => ['senior citizen', 'senior ID', 'senior discount', 'OSCA', 'elderly', 'senior benefits', 'pension', '60 years old'],
                'trigger_keywords_fil' => ['senior citizen', 'senior ID', 'diskwento ng senior', 'OSCA', 'matatanda', 'nakatatanda', 'pensyon', 'edad 60'],
                'official_response'    => "Para sa Senior Citizen ID at Benefits:\n\n**Requirements para sa Senior Citizen ID:**\n✅ Birth Certificate o valid ID na may birthdate (60 years old pataas)\n✅ Proof of residency sa Buguey\n✅ 2 pcs. 1x1 ID photo\n✅ Barangay Certificate\n\n**Benefits ng Senior Citizen ID:**\n✅ 20% discount sa medicines, restaurants, hotels, at iba pa\n✅ Exempted sa 12% VAT\n✅ Priority lanes sa government offices at establishments\n\n📍 **OSCA Office**, Municipal Hall\n🕐 Lunes–Biyernes, 8AM–5PM\n💰 LIBRE ang pagkuha ng ID",
                'response_type'        => 'text',
            ],
            [
                'intent_name'          => 'pwd_id',
                'category'             => 'Social Services',
                'trigger_keywords_en'  => ['PWD', 'PWD ID', 'disability', 'disabled', 'person with disability', 'PWD discount', 'PWD benefits', 'handicapped'],
                'trigger_keywords_fil' => ['PWD', 'PWD ID', 'kapansanan', 'may kapansanan', 'diskwento ng PWD', 'disabled', 'lumpuhan'],
                'official_response'    => "Para sa PWD ID at Benefits:\n\n**Requirements para sa PWD ID:**\n✅ Medical Certificate mula sa attending physician\n✅ Barangay Certificate\n✅ 2 pcs. 1x1 ID photo\n✅ Valid ID ng applicant o guardian\n✅ Proof of residency\n\n**Benefits ng PWD ID:**\n✅ 20% discount sa medicines, restaurants, transportation, at iba pa\n✅ Exempted sa 12% VAT\n✅ Priority lanes sa government offices\n\n📍 **MSWDO / PDAO Office**, Municipal Hall\n🕐 Lunes–Biyernes, 8AM–5PM\n💰 LIBRE ang pagkuha ng ID",
                'response_type'        => 'text',
            ],
            [
                'intent_name'          => 'solo_parent',
                'category'             => 'Social Services',
                'trigger_keywords_en'  => ['solo parent', 'single parent', 'solo parent ID', 'solo parent benefits'],
                'trigger_keywords_fil' => ['solo parent', 'single parent', 'solo parent ID', 'nag-iisang magulang', 'magulang na nag-iisa'],
                'official_response'    => "Para sa Solo Parent ID at Benefits:\n\n**Requirements:**\n✅ Barangay Certification na ikaw ay solo parent\n✅ Birth Certificate ng anak/mga anak\n✅ Valid ID\n✅ 2 pcs. 1x1 ID photo\n✅ Proof of solo parenting (death cert ng spouse, annulment decree, etc.)\n\n**Benefits:**\n✅ 10% discount sa selected establishments\n✅ Flexible work schedule (sa employer)\n✅ Parental leave benefits\n\n📍 **MSWDO Office**, Municipal Hall\n🕐 Lunes–Biyernes, 8AM–5PM\n💰 LIBRE",
                'response_type'        => 'text',
            ],

            // ── REAL PROPERTY & TAXES ──────────────────────────────────────
            [
                'intent_name'          => 'real_property_tax',
                'category'             => 'Real Property',
                'trigger_keywords_en'  => ['real property tax', 'RPT', 'land tax', 'property tax', 'amilyar', 'tax payment', 'tax declaration', 'assessor', 'tax assessment', 'pay tax', 'pay property'],
                'trigger_keywords_fil' => ['amilyar', 'buwis sa lupa', 'property tax', 'RPT', 'pagbabayad ng buwis', 'tax declaration', 'buwis ng bahay', 'assessor', 'bayad buwis', 'magbayad ng buwis', 'magbayad ng amilyar', 'buwis', 'bayad amilyar'],
                'official_response'    => "Para sa Real Property Tax (Amilyar) Payment:\n\n**Para sa Pagbabayad:**\n✅ Tax Declaration Number o Property Index Number\n✅ Previous Official Receipt (kung mayroon)\n✅ Valid ID ng may-ari\n\n**Discount:**\n✅ 10% discount kung babayaran nang buo bago mag-January 31\n✅ Quarterly payment available (March 31, June 30, Sept 30, Dec 31)\n\n📍 **Municipal Treasurer's Office**, Municipal Hall\n🕐 Lunes–Biyernes, 8AM–5PM\n\n**Para sa Tax Declaration / Assessment:**\n📍 **Municipal Assessor's Office**, Municipal Hall",
                'response_type'        => 'text',
            ],
            [
                'intent_name'          => 'cedula',
                'category'             => 'Real Property',
                'trigger_keywords_en'  => ['cedula', 'community tax', 'CTC', 'community tax certificate', 'cedula requirement'],
                'trigger_keywords_fil' => ['cedula', 'community tax', 'CTC', 'sedula', 'buwis ng pamayanan'],
                'official_response'    => "Para sa Community Tax Certificate (Cedula/CTC):\n\n✅ Valid government-issued ID\n✅ Basic personal information (pangalan, address, kita)\n✅ Bayad: ₱5 (basic) + additional amount based on income/property\n\n📍 **Municipal Treasurer's Office**, Municipal Hall\n🕐 Lunes–Biyernes, 8AM–5PM\n⏱️ Same day (ilang minuto lang)\n\n📝 Kailangan ang cedula para sa maraming government transactions (marriage license, mga notaryo, at iba pa).",
                'response_type'        => 'text',
            ],

            // ── AGRICULTURE ────────────────────────────────────────────────
            [
                'intent_name'          => 'agriculture_services',
                'category'             => 'Agriculture',
                'trigger_keywords_en'  => ['agriculture', 'farming', 'crop', 'rice', 'corn', 'livestock', 'fishery', 'MAO', 'agricultural assistance', 'seeds', 'fertilizer'],
                'trigger_keywords_fil' => ['agrikultura', 'pagsasaka', 'tanim', 'palay', 'mais', 'hayupan', 'pangisdaan', 'MAO', 'binhi', 'pataba', 'magsasaka'],
                'official_response'    => "Para sa Agricultural Services sa Buguey:\n\n**Available na Serbisyo:**\n✅ Free technical assistance at training para sa mga magsasaka\n✅ Seed distribution program (palay, mais, gulay)\n✅ Livestock at fishery production assistance\n✅ Farm input subsidy at equipment lending\n✅ Crop insurance assistance\n\n📍 **Municipal Agriculture Office (MAO)**, Municipal Hall\n🕐 Lunes–Biyernes, 8AM–5PM\n💰 Karamihan ng serbisyo ay LIBRE",
                'response_type'        => 'text',
            ],

            // ── DISASTER & EMERGENCY ───────────────────────────────────────
            [
                'intent_name'          => 'disaster_emergency',
                'category'             => 'Emergency',
                'trigger_keywords_en'  => ['disaster', 'emergency', 'flood', 'typhoon', 'earthquake', 'evacuation', 'MDRRMO', 'calamity', 'rescue', 'storm', 'evacuation center', 'relief', 'disaster risk'],
                'trigger_keywords_fil' => ['sakuna', 'emergency', 'baha', 'bagyo', 'lindol', 'bakwit', 'MDRRMO', 'kalamidad', 'rescue', 'unos', 'likas', 'evacuation center', 'lumikas', 'nasaan evacuation', 'relief goods'],
                'official_response'    => "Para sa Disaster at Emergency Services:\n\n**Sa oras ng emergency:**\n✅ Tumawag sa **MDRRMO Hotline** o pumunta sa pinakamalapit na evacuation center\n✅ Sundin ang advisory ng MDRRMO\n\n**Mga Serbisyo:**\n✅ Evacuation assistance at shelter\n✅ Relief goods distribution\n✅ Search and rescue operations\n✅ Damage assessment at reporting\n✅ Post-disaster assistance\n\n📍 **MDRRMO Office**, Municipal Hall\n🕐 Available 24/7 sa oras ng emergency\n⚠️ Kung may imminent danger, bakwitin agad ang pamilya sa ligtas na lugar!",
                'response_type'        => 'text',
            ],

            // ── SERVICE TRACKING ───────────────────────────────────────────
            [
                'intent_name'          => 'track_request',
                'category'             => 'General',
                'trigger_keywords_en'  => ['track', 'tracking', 'status', 'where is my', 'check request', 'my request', 'request number', 'follow up', 'tracking code', 'request status'],
                'trigger_keywords_fil' => ['track', 'lagay', 'status', 'nasaan', 'aking kahilingan', 'tracking', 'sundan', 'follow up', 'tracking code', 'request ko'],
                'official_response'    => "Para ma-track ang iyong dokumento o request:\n\n📝 I-type ang iyong **tracking code** (format: **SR-YYYYMMDD-XXXXXX**)\n\nMakakahanap ka ng tracking code sa:\n📧 Email confirmation na natanggap mo pagkatapos mag-submit\n📱 Iyong citizen dashboard sa RESIDENTE App\n📋 Resibo/receipt na ibinigay sa iyo\n\nKung wala kang tracking code, maaari mong i-check ang status sa iyong dashboard o pumunta personally sa Municipal Hall.",
                'response_type'        => 'text',
            ],

            // ── GENERAL ────────────────────────────────────────────────────
            [
                'intent_name'          => 'office_hours',
                'category'             => 'General',
                'trigger_keywords_en'  => ['office hours', 'schedule', 'what time open', 'when open', 'operating hours', 'working hours', 'municipal hall hours', 'holiday', 'what time', 'opening time', 'closing time'],
                'trigger_keywords_fil' => ['oras ng opisina', 'schedule', 'anong oras bukas', 'kailan bukas', 'oras ng tanggapan', 'bukas ba', 'sarado ba', 'anong oras', 'oras bukas', 'oras municipal', 'oras munisipyo', 'bukas ang municipal'],
                'official_response'    => "🕐 **Oras ng Opisina ng Municipal Hall ng Buguey:**\n\n📅 **Lunes – Biyernes:** 8:00 AM – 5:00 PM\n(Walang lunch break cutoff para sa mga frontline services)\n\n📅 **Sarado:** Sabado, Linggo, at pambansang araw ng pahinga\n\n📍 Municipal Hall, Buguey, Cagayan\n\n**Mga Departments na available:**\n• Mayor's Office\n• BPLO (Permits)\n• Civil Registrar\n• Municipal Treasurer\n• Municipal Assessor\n• MHO (Health)\n• MSWDO (Social Services)\n• Engineering Office\n• MPDC\n• MAO (Agriculture)\n• MDRRMO",
                'response_type'        => 'text',
            ],
            [
                'intent_name'          => 'contact_info',
                'category'             => 'General',
                'trigger_keywords_en'  => ['contact', 'phone number', 'email address', 'how to reach', 'address', 'location', 'where is municipal hall', 'directions'],
                'trigger_keywords_fil' => ['contact', 'numero ng telepono', 'email', 'address', 'saan ang munisipyo', 'paano pumunta', 'makausap', 'makipag-ugnayan'],
                'official_response'    => "📞 **Makipag-ugnayan sa Municipality of Buguey:**\n\n📍 **Address:** Municipal Hall, Poblacion, Buguey, Cagayan 3511\n🕐 **Office Hours:** Lunes–Biyernes, 8AM–5PM\n\n**Mga Paraan ng Pag-contact:**\n✅ Pumunta personally sa Municipal Hall\n✅ Gamitin ang RESIDENTE App (citizen dashboard)\n✅ Gamitin ang **Live Handoff** feature ng chatbot na ito\n\nPara sa urgent na concern, maaari kang pumunta personally sa Municipal Hall.",
                'response_type'        => 'text',
            ],
            [
                'intent_name'          => 'elected_officials',
                'category'             => 'General',
                'trigger_keywords_en'  => ['mayor', 'vice mayor', 'councilor', 'elected officials', 'sanggunian', 'municipal council', 'government officials'],
                'trigger_keywords_fil' => ['mayor', 'vice mayor', 'konsehal', 'mga opisyal', 'sanggunian', 'konseho', 'mga pinuno'],
                'official_response'    => "Para sa impormasyon tungkol sa Elected Officials ng Municipality of Buguey:\n\n📍 Pumunta sa **Mayor's Office** o **Sangguniang Bayan Office** sa Municipal Hall\n🕐 Lunes–Biyernes, 8AM–5PM\n\nPara sa mga concern na kailangang idulog sa opisyales:\n✅ Mag-submit ng letter/petition sa Mayor's Office\n✅ Dumalo sa regular session ng Sangguniang Bayan (tuwing ika-2 at ika-4 na Lunes ng buwan)",
                'response_type'        => 'text',
            ],
            [
                'intent_name'          => 'complaint_feedback',
                'category'             => 'General',
                'trigger_keywords_en'  => ['complaint', 'feedback', 'report', 'problem', 'issue', 'concern', 'suggestion', 'file complaint'],
                'trigger_keywords_fil' => ['reklamo', 'feedback', 'sumbong', 'problema', 'isyu', 'concern', 'suhestiyon', 'mag-reklamo'],
                'official_response'    => "Para sa mga Reklamo, Feedback, o Suggestion:\n\n**Mga Paraan ng Pag-report:**\n✅ Personal visit sa **Mayor's Office** o relevant department\n✅ Written complaint/feedback (sulat o email)\n✅ Sa pamamagitan ng RESIDENTE App\n✅ I-report sa iyong Barangay Chairman\n\n**Para sa service-related complaints:**\n✅ Pumunta sa **Public Assistance and Complaints Desk**, Municipal Hall\n\n📍 Municipal Hall, Buguey, Cagayan\n🕐 Lunes–Biyernes, 8AM–5PM",
                'response_type'        => 'text',
            ],
            [
                'intent_name'          => 'free_services',
                'category'             => 'General',
                'trigger_keywords_en'  => ['free services', 'free', 'no fee', 'no charge', 'without payment', 'free of charge', 'what is free', 'which services are free', 'complimentary', 'gratis'],
                'trigger_keywords_fil' => ['libre', 'libreng serbisyo', 'walang bayad', 'free', 'gratis', 'ano ang libre', 'mga libreng services', 'libreng tulong', 'walang singil', 'serbisyong libre', 'ano libre', 'anong libre'],
                'official_response'    => "Narito ang mga **LIBRENG SERBISYO** na available sa Municipality of Buguey:\n\n🏥 **Health Services:**\n✅ Anti-Rabies Vaccine — Municipal Health Office (MHO)\n✅ Prenatal Checkup — MHO (para sa lahat ng buntis)\n\n🤝 **Social Services:**\n✅ Certificate of Indigency — MSWDO\n✅ Senior Citizen ID — OSCA Office\n✅ PWD ID — MSWDO / PDAO Office\n✅ Solo Parent ID — MSWDO\n\n🌾 **Agriculture:**\n✅ Technical assistance at training — MAO\n✅ Seed distribution program — MAO\n✅ Farm input subsidy at equipment lending — MAO\n\n🚨 **Emergency:**\n✅ Disaster response at evacuation — MDRRMO (24/7)\n✅ Relief goods distribution — MDRRMO\n\n📍 Lahat ng offices ay nasa **Municipal Hall, Buguey**\n🕐 Lunes–Biyernes, 8AM–5PM",
                'response_type'        => 'text',
            ],
            [
                'intent_name'          => 'how_to_use_app',
                'category'             => 'General',
                'trigger_keywords_en'  => ['how to use', 'how does this work', 'what can you do', 'features', 'app features', 'register', 'sign up', 'create account', 'login', 'how to register'],
                'trigger_keywords_fil' => ['paano gamitin', 'paano to', 'ano magagawa mo', 'features', 'paano mag-register', 'paano gumawa ng account', 'mag-sign up', 'gumawa ng account'],
                'official_response'    => "**Paano Gamitin ang RESIDENTE App:**\n\n📱 **Para sa Bagong Users:**\n✅ Mag-register gamit ang valid email at personal information\n✅ I-verify ang iyong email address\n✅ Kumpletuhin ang profile setup\n\n**Mga Features:**\n📋 Online service request (clearances, permits, certificates)\n📦 Track ang status ng mga request\n📢 Mga announcements mula sa municipality\n🤖 24/7 Chatbot assistant (ito!)\n📞 Live handoff sa staff para sa complex concerns\n\n**Kailangan ng tulong?** I-type lang ang iyong tanong dito!",
                'response_type'        => 'text',
            ],
        ];

        foreach ($intents as $intent) {
            ChatbotKnowledge::updateOrCreate(
                ['intent_name' => $intent['intent_name']],
                array_merge($intent, ['last_verified_at' => now()])
            );
        }

        $this->command->info('ChatbotKnowledge seeder: ' . count($intents) . ' intents seeded.');
    }
}
