<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Images Model
 *
 * @property \App\Model\Table\PostersTable $Posters
 */
class ImagesTable extends Table
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

        $this->setTable('images');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Posters', [
            'foreignKey' => 'poster_id',
            'joinType' => 'INNER',
        ]);
        
        $this->getEventManager()->on('Model.afterSave', [$this, 'updatePosterImageCount']);
        $this->getEventManager()->on('Model.afterDelete', [$this, 'updatePosterImageCount']);
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
            ->integer('poster_id')
            ->requirePresence('poster_id', 'create')
            ->notEmptyString('poster_id');

        $validator
            ->scalar('file')
            ->maxLength('file', 255)
            ->requirePresence('file', 'create')
            ->notEmptyString('file');

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
        $rules->add($rules->existsIn('poster_id', 'Posters'), ['errorField' => 'poster_id']);

        return $rules;
    }

    /**
     * After save/delete callback to update images_count
     */
    public function updatePosterImageCount($event, $entity, $options)
    {
        $posterId = $entity->poster_id ?? null;
        if ($posterId) {
            $count = $this->find()
                ->where(['poster_id' => $posterId])
                ->count();
            
            $postersTable = $this->getTableLocator()->get('Posters');
            $poster = $postersTable->get($posterId);
            if ($poster) {
                $poster->images_count = $count;
                $postersTable->save($poster);
            }
        }
    }
}

