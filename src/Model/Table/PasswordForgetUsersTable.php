<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\TempUser;
use App\Util\Token;
use ArrayObject;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\I18n\DateTime;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * PasswordForgetUsers Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @method \App\Model\Entity\PasswordForgetUser newEmptyEntity()
 * @method \App\Model\Entity\PasswordForgetUser newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\PasswordForgetUser> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PasswordForgetUser get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\PasswordForgetUser findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\PasswordForgetUser patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\PasswordForgetUser> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\PasswordForgetUser|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\PasswordForgetUser saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\PasswordForgetUser>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PasswordForgetUser>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PasswordForgetUser>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PasswordForgetUser> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PasswordForgetUser>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PasswordForgetUser>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PasswordForgetUser>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PasswordForgetUser> deleteManyOrFail(iterable $entities, array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PasswordForgetUsersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('password_forget_users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Undocumented function
     *
     * @param \Cake\Event\EventInterface $event
     * @param \Cake\Datasource\EntityInterface $entity
     * @param \ArrayObject $options
     * @return void
     */
    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        $token = new Token();
        $entity->token = $token->get();

        $now = new DateTime();
        $expired = $now->addSeconds(Configure::read('user.token.expire', 0));
        $entity->expired = $expired;
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->nonNegativeInteger('user_id')
            ->notEmptyString('user_id');

        $validator
            ->scalar('token')
            ->maxLength('token', 255)
            ->requirePresence('token', 'create')
            ->notEmptyString('token');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
}
