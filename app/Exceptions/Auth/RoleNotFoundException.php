<?php

namespace App\Exceptions\Auth;

use Exception;
// ✅ CORRECT
class RoleNotFoundException extends \Exception
{
    public function __construct(string $message = "Rôle introuvable", int $code = 0)
    {
        parent::__construct($message, $code);
    }
}