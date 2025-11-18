<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property int $group_id
 * @property string $username
 * @property string $password
 * @property string|null $email
 * @property bool|null $activated
 * @property string|null $activation_key
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Group $group
 * @property \App\Model\Entity\Poster[] $posters
 * @property \App\Model\Entity\Access[] $access
 * @property \App\Model\Entity\Comment[] $comments
 */
class User extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'group_id' => true,
        'username' => true,
        'password' => true,
        'email' => true,
        'activated' => true,
        'activation_key' => true,
        'created' => true,
        'modified' => true,
        'group' => true,
        'posters' => true,
        'access' => true,
        'comments' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected array $_hidden = [
        'password',
    ];
}

