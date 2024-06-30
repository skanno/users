<?php
declare(strict_types=1);

namespace App\Test\TestCase\Command;

use App\Command\CleanupTempUsersCommand;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Command\CleanupTempUsersCommand Test Case
 *
 * @uses \App\Command\CleanupTempUsersCommand
 */
class CleanupTempUsersCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;

    /**
     * Test buildOptionParser method
     *
     * @return void
     * @uses \App\Command\CleanupTempUsersCommand::buildOptionParser()
     */
    public function testBuildOptionParser(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test execute method
     *
     * @return void
     * @uses \App\Command\CleanupTempUsersCommand::execute()
     */
    public function testExecute(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
