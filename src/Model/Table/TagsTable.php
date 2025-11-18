<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Tags Model
 *
 * @property \App\Model\Table\PostersTable $Posters
 */
class TagsTable extends Table
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

        $this->setTable('tags');
        $this->setDisplayField('tag');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Posters', [
            'foreignKey' => 'tag_id',
            'targetForeignKey' => 'poster_id',
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
            ->scalar('tag')
            ->maxLength('tag', 255)
            ->requirePresence('tag', 'create')
            ->notEmptyString('tag', 'This field cannot be left blank')
            ->add('tag', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => 'Tag already exists']);

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
        $rules->add($rules->isUnique(['tag']), ['errorField' => 'tag']);

        return $rules;
    }
}

