<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreatePasswordForgetUsers extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('password_forget_users');
        $table->addColumn('user_id', 'integer', [
            'default' => null,
            'null' => false,
            'signed' => false,
        ])
        ->addColumn('token', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ])
        ->addColumn('expired', 'datetime', [
            'default' => null,
            'limit' => null,
            'null' => false,
        ])
        ->addColumn('created', 'datetime', [
            'default' => null,
            'limit' => null,
            'null' => true,
        ])
        ->addIndex(
            ['user_id'],
            ['name' => 'user_id']
        )
        ->addForeignKey('user_id', 'users', 'id');
        $table->create();
    }
}
