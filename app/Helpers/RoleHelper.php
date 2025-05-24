<?php

namespace App\Helpers;

use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;

class RoleHelper
{
    public static function is(UserRole|string $role): bool
    {
        $user = Auth::user();
        if (!$user) return false;

        return $role instanceof UserRole
            ? $user->level === $role
            : $user->level->value === $role;
    }

    public static function isAny(UserRole|string ...$roles): bool
    {
        $user = Auth::user();
        if (!$user) return false;

        return $roles instanceof UserRole
            ? in_array($user->level, $roles, true)
            : in_array($user->level->value, $roles, true);
    }

    public static function getRoleName(): string|null
    {
        $user = Auth::user();
        if (!$user) return null;

        return strtolower($user->level->value);
    }
}
