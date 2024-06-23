<?php
declare(strict_types=1);

use Cake\I18n\DateTime;

return [
    'user' => [
        'onetime_token' => [
            'length' => 6,
            'expire' => DateTime::SECONDS_PER_MINUTE * 10,
        ],
    ],
];
