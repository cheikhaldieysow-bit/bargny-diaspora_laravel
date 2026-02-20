<?php

namespace App\Exceptions\Auth;

use Exception;

class PhoneAlreadyUsedException extends Exception
{
    public function __construct(string $message = 'Un compte existe déjà avec ce téléphone.')
    {
        parent::__construct(409, $message);
    }

}
