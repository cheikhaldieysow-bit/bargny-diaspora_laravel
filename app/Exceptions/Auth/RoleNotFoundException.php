<?php

namespace App\Exceptions\Auth;

use Exception;

class RoleNotFoundException extends Exception
{
     public function __construct(string $message = 'Le rôle Owner est introuvable.')
    {
        parent::__construct(500, $message);
    }
}
