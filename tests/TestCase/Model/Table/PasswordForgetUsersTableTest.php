<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PasswordForgetUsersTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PasswordForgetUsersTable Test Case
 */
class PasswordForgetUsersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PasswordForgetUsersTable
     */
    protected $PasswordForgetUsers;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.PasswordForgetUsers',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('PasswordForgetUsers') ? [] : ['className' => PasswordForgetUsersTable::class];
        $this->PasswordForgetUsers = $this->getTableLocator()->get('PasswordForgetUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->PasswordForgetUsers);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\PasswordForgetUsersTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\PasswordForgetUsersTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
