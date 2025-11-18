<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Poster Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $title
 * @property string|null $authenticity
 * @property int|null $images_count
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Image[] $images
 * @property \App\Model\Entity\Comment[] $comments
 * @property \App\Model\Entity\Access[] $access
 * @property \App\Model\Entity\Client[] $clients
 * @property \App\Model\Entity\Tag[] $tags
 */
class Poster extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'user_id' => true,
        'title' => true,
        'authenticity' => true,
        'images_count' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
        'images' => true,
        'comments' => true,
        'access' => true,
        'clients' => true,
        'tags' => true,
    ];
}

