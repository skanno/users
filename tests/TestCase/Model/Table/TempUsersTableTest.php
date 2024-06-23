<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TempUsersTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TempUsersTable Test Case
 */
class TempUsersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TempUsersTable
     */
    protected $TempUsers;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.TempUsers',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('TempUsers') ? [] : ['className' => TempUsersTable::class];
        $this->TempUsers = $this->getTableLocator()->get('TempUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->TempUsers);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\TempUsersTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
