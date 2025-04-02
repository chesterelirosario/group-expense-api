<?php

namespace Modules\Membership\Exceptions;

use Exception;

class MemberAlreadyExistsException extends Exception
{
    public function __construct($message = "User is already a member of this group.")
    {
        parent::__construct($message);
    }
}
