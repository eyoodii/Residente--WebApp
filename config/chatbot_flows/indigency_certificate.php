<?php

// config/chatbot_flows/indigency_certificate.php

return [
    'flow_id' => 'indigency_certificate',
    'title'   => 'Indigency Certificate',
    'steps'   => [
        [
            'step'     => 1,
            'question' => 'Para saan ang Indigency Certificate? / What is the certificate for?',
            'type'     => 'choice',
            'choices'  => [
                'Medical / Ospital',
                'Burial / Libing',
                'Scholarship',
                'Legal Assistance',
                'Other / Iba pa',
            ],
            'saves_to' => 'purpose',
        ],
    ],
    'completion' => 'based_on_answers',
];
