<?php

// config/chatbot_flows/business_permit_new.php

return [
    'flow_id' => 'business_permit_new',
    'title'   => 'New / Renewal Business Permit Application',
    'steps'   => [
        [
            'step'     => 1,
            'question' => 'Ilang taon na ang iyong negosyo? / How old is your business?',
            'type'     => 'choice',
            'choices'  => ['Brand new (0–1 year)', 'Existing (1+ years)'],
            'saves_to' => 'business_age',
        ],
        [
            'step'     => 2,
            'question' => 'Anong uri ng negosyo? / What is the business type?',
            'type'     => 'choice',
            'choices'  => ['Sole Proprietor', 'Partnership', 'Corporation'],
            'saves_to' => 'business_type',
        ],
        [
            'step'     => 3,
            'question' => 'Nasa loob ba ng residential area ang negosyo? / Is the business inside a residential zone?',
            'type'     => 'choice',
            'choices'  => ['Yes', 'No'],
            'saves_to' => 'is_residential',
        ],
    ],
    'completion' => 'based_on_answers',
];
