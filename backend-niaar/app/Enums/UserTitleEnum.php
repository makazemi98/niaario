<?php

namespace App\Enums;

use App\Traits\EnumToArrayTrait;

enum UserTitleEnum: string
{
    use EnumToArrayTrait;

    case MR = 'Mr';
    case Miss = 'Miss';
    case Mrs = 'Mrs';
    case MS = 'Ms';
}
