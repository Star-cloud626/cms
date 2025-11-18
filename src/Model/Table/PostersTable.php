<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Posters Model
 *
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\ImagesTable $Images
 * @property \App\Model\Table\CommentsTable $Comments
 * @property \App\Model\Table\AccessTable $Access
 * @property \App\Model\Table\ClientsTable $Clients
 * @property \App\Model\Table\TagsTable $Tags
 */
class PostersTable extends Table
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

        $this->setTable('posters');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);

        $this->hasMany('Images', [
            'foreignKey' => 'poster_id',
            'dependent' => true,
            'sort' => ['Images.position' => 'ASC'],
        ]);

        $this->hasMany('Comments', [
            'foreignKey' => 'poster_id',
            'dependent' => true,
        ]);

        $this->hasMany('Access', [
            'foreignKey' => 'poster_id',
            'dependent' => true,
        ]);

        $this->belongsToMany('Clients', [
            'foreignKey' => 'poster_id',
            'targetForeignKey' => 'client_id',
            'joinTable' => 'posters_clients',
        ]);

        $this->belongsToMany('Tags', [
            'foreignKey' => 'poster_id',
            'targetForeignKey' => 'tag_id',
            'joinTable' => 'posters_tags',
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
            ->integer('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmptyString('user_id');

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

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
        $rules->add($rules->existsIn('user_id', 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }

    /**
     * Before save callback
     */
    public function beforeSave($event, $entity, $options)
    {
        if (!empty($entity->title)) {
            $entity->title = trim($entity->title);
        }
        if (empty($entity->authenticity)) {
            $entity->authenticity = null;
        }
    }
}

