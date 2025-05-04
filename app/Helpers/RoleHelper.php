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

        return $user->level->value === (string) $role;
    }

    public static function isAny(UserRole|array ...$roles): bool
    {
        $user = Auth::user();
        if (!$user) return false;

        return in_array($user->level, $roles, true);
    }
}
