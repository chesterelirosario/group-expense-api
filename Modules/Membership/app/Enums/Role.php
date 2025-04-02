<?php

namespace Modules\Membership\Enums;

enum Role: string
{
    case Owner = 'Owner';
    case Administrator = 'Administrator';
    case Member = 'Member';
}
