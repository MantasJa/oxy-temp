<?php

namespace App\Service\Exception;

class UserNotFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct("User does not exist or has recent activity");
    }
}
