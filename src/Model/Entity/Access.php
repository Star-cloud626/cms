<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Access Entity
 *
 * @property int $id
 * @property int $poster_id
 * @property int $user_id
 * @property bool|null $comment
 *
 * @property \App\Model\Entity\Poster $poster
 * @property \App\Model\Entity\User $user
 */
class Access extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'poster_id' => true,
        'user_id' => true,
        'comment' => true,
        'poster' => true,
        'user' => true,
    ];
}

