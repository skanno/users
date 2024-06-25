<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\TempUser;
use ArrayObject;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\I18n\DateTime;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TempUsers Model
 *
 * @method \App\Model\Entity\TempUser newEmptyEntity()
 * @method \App\Model\Entity\TempUser newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\TempUser> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TempUser get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\TempUser findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\TempUser patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\TempUser> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\TempUser|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\TempUser saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\TempUser>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\TempUser>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\TempUser>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\TempUser> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\TempUser>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\TempUser>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\TempUser>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\TempUser> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TempUsersTable extends Table
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

        $this->setTable('temp_users');
        $this->setDisplayField('email');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Undocumented function
     *
     * @param \Cake\Event\EventInterface $event
     * @param \Cake\Datasource\EntityInterface $entity
     * @param \ArrayObject $data
     * @param \ArrayObject $options
     * @return void
     */
    public function afterMarshal(EventInterface $event, EntityInterface $entity, ArrayObject $data, ArrayObject $options): void
    {
        $tokenLength = Configure::read('user.onetime_token.length', 999999);
        $max = pow(10, $tokenLength) - 1;
        $entity->onetime_token = sprintf("%0{$tokenLength}d", rand(0, $max));

        $now = new DateTime();
        $expired = $now->addSeconds(Configure::read('user.onetime_token.expire', 0));
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
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('onetime_token')
            ->maxLength('onetime_token', 10)
            ->requirePresence('onetime_token', 'create')
            ->notEmptyString('onetime_token');

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
        $rules->add($rules->isUnique(['email', 'onetime_token']), ['errorField' => 'email']);

        return $rules;
    }

    public function getTempUser(string $onetimeToken): ?TempUser
    {
        $tempUser = $this->find()
            ->where([
                'TempUsers.onetime_token' => $onetimeToken,
            ])
            ->first();

        if (empty($tempUser)) {
            return null;
        } else {
            $this->delete($tempUser);
        }

        if ($tempUser->expired->isPast()) {
            return null;
        }

        return $tempUser;
    }
}
