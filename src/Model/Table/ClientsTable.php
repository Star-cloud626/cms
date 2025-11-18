<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Clients Model
 * 
 * Clients are essentially Users with group_id = 3 (CLIENT)
 * This table uses the same users table but filters by group_id
 */
class ClientsTable extends Table
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

        // Add belongsToMany for Posters
        $this->belongsToMany('Posters', [
            'foreignKey' => 'client_id',
            'targetForeignKey' => 'poster_id',
            'joinTable' => 'posters_clients',
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
        // Use same validation as Users
        $usersTable = $this->getTableLocator()->get('Users');
        return $usersTable->validationDefault($validator);
    }

    /**
     * Find method to filter by client group
     */
    public function findClient(SelectQuery $query, array $options): SelectQuery
    {
        return $query->where(['Clients.group_id' => GroupsTable::CLIENT]);
    }

    /**
     * Before save callback
     */
    public function beforeSave($event, $entity, $options)
    {
        $entity->group_id = GroupsTable::CLIENT;
        $entity->activated = 1;
        return true;
    }
}

