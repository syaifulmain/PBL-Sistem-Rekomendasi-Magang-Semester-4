<?php

use App\Helpers\RoleHelper;

if (!function_exists('has_role')) {
    function has_role($role): bool
    {
        return RoleHelper::is($role);
    }
}

if (!function_exists('has_any_role')) {
    function has_any_role(...$roles): bool
    {
        return RoleHelper::isAny(...$roles);
    }
}

if (!function_exists('get_role_name')) {
    function get_role_name(): string|null
    {
        return RoleHelper::getRoleName();
    }
}