<?php

// config/chatbot_flows/barangay_clearance.php

return [
    'flow_id' => 'barangay_clearance',
    'title'   => 'Barangay Clearance',
    'steps'   => [
        [
            'step'     => 1,
            'question' => 'Para saan ang Barangay Clearance? / What is the clearance for?',
            'type'     => 'choice',
            'choices'  => [
                'Employment / Work',
                'Business',
                'Travel / Visa',
                'Scholarship / School',
                'Other / Iba pa',
            ],
            'saves_to' => 'purpose',
        ],
    ],
    'completion' => 'based_on_answers',
];
