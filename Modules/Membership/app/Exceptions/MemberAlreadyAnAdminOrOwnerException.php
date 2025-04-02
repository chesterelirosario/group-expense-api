<?php

namespace Modules\Membership\Exceptions;

use Exception;

class MemberAlreadyAnAdminOrOwnerException extends Exception
{
    public function __construct($message = "This member already has an admin or owner role.")
    {
        parent::__construct($message);
    }
}
