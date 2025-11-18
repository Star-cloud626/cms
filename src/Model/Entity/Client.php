<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Entity\User;

/**
 * Client Entity
 * 
 * Clients extend User functionality
 */
class Client extends User
{
    // Client is essentially a User with group_id = 3
    // All User properties and methods are inherited
}

