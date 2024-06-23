<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TempUsersFixture
 */
class TempUsersFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'email' => 'Lorem ipsum dolor sit amet',
                'onetime_token' => 'Lorem ip',
                'created' => '2024-06-22 09:19:13',
            ],
        ];
        parent::init();
    }
}
