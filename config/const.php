<?php
declare(strict_types=1);

use Cake\I18n\DateTime;

return [
    'user' => [
        'token' => [
            'length' => 6,
            'expire' => DateTime::SECONDS_PER_MINUTE * 10,
        ],
        'password' => [
            'minLength' => 6,
            'maxLength' => 100,
        ],
    ],
];
