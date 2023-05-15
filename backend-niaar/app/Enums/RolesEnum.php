<?php
namespace App\Enums;

use App\Traits\EnumToArrayTrait;

enum RolesEnum: string
{
    use EnumToArrayTrait;

    case SUPER_ADMIN = "super-admin";
    case MANAGER = 'manager';
    case TEAM_LEADER = 'team-leader';
    case ACCOUNTANT = 'accountant';
    case PROCUREMENT = 'procurement';
    case CLIENT = 'client';
}
