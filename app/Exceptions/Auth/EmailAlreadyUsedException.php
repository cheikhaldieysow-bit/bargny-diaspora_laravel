<?php

namespace App\Exceptions\Auth;

use Symfony\Component\HttpKernel\Exception\HttpException;

class EmailAlreadyUsedException extends HttpException
{
    public function __construct(string $message = 'Un compte existe déjà avec cet email.')
    {
        parent::__construct(409, $message);
    }
}
