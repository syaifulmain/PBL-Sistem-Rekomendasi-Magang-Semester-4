<?php

use App\Helpers\RoleHelper;

function has_role($role): bool
{
    return RoleHelper::is($role);
}

function has_any_role(...$roles): bool
{
    return RoleHelper::isAny(...$roles);
}
