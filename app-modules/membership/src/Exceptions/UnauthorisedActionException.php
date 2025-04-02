<?php

namespace Modules\Membership\Exceptions;

use Exception;

class UnauthorisedActionException extends Exception
{
    public function __construct($message = "You are not authorised to perform this action.")
    {
        parent::__construct($message);
    }
}
