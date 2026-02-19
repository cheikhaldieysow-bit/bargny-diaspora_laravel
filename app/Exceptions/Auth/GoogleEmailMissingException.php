<?php

namespace App\Exceptions\Auth;

use Symfony\Component\HttpKernel\Exception\HttpException;

class GoogleEmailMissingException extends HttpException
{
    public function __construct(string $message = "Google n'a pas fourni d'email.")
    {
        parent::__construct(422, $message);
    }
}
