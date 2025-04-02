<?php

namespace Modules\Membership\Exceptions;

use Exception;

class MemberAlreadyAMemberException extends Exception
{
    public function __construct($message = "This member already has a member role.")
    {
        parent::__construct($message);
    }
}
