<?php

namespace App\Exceptions\Auth;

use Symfony\Component\HttpKernel\Exception\HttpException;

class GoogleAccountNotFoundException extends HttpException
{
    public function __construct(string $message = 'Aucun compte associé à ce Google.')
    {
        parent::__construct(404, $message);
    }
}
