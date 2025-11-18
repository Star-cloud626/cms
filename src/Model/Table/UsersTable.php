<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;

/**
 * Users Model
 *
 * @property \App\Model\Table\GroupsTable $Groups
 * @property \App\Model\Table\PostersTable $Posters
 * @property \App\Model\Table\AccessTable $Access
 * @property \App\Model\Table\CommentsTable $Comments
 */
class UsersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('username');
        $this->setPrimaryKey('id');

        $this->belongsTo('Groups', [
            'foreignKey' => 'group_id',
            'joinType' => 'INNER',
        ]);

        $this->hasMany('Posters', [
            'foreignKey' => 'user_id',
        ]);

        $this->hasMany('Access', [
            'foreignKey' => 'user_id',
        ]);

        $this->hasMany('Comments', [
            'foreignKey' => 'user_id',
        ]);
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
            ->scalar('username')
            ->maxLength('username', 50)
            ->requirePresence('username', 'create')
            ->notEmptyString('username')
            ->add('username', 'unique', ['rule' => 'validateUnique', 'provider' => 'table'])
            ->alphaNumeric('username', 'Letters and numbers only')
            ->lengthBetween('username', [4, 15], 'Must be between 4 and 15 characters');

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmptyString('password')
            ->minLength('password', 5, 'Minimum 5 characters long');

        $validator
            ->integer('group_id')
            ->requirePresence('group_id', 'create')
            ->notEmptyString('group_id');

        $validator
            ->email('email')
            ->allowEmptyString('email');

        $validator
            ->boolean('activated')
            ->allowEmptyString('activated');

        $validator
            ->scalar('activation_key')
            ->maxLength('activation_key', 255)
            ->allowEmptyString('activation_key');

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
        $rules->add($rules->isUnique(['username']), ['errorField' => 'username']);
        $rules->add($rules->existsIn('group_id', 'Groups'), ['errorField' => 'group_id']);

        return $rules;
    }

    /**
     * Hash password before saving
     */
    protected function _setPassword(string $password): ?string
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher())->hash($password);
        }
        return null;
    }

    /**
     * Finder for authentication
     * 
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param array $options Options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findAuth(SelectQuery $query, array $options): SelectQuery
    {
        $query
            ->contain(['Groups'])
            ->where(['Users.activated' => 1]);

        return $query;
    }
}

