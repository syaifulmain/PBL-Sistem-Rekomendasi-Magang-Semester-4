<?php

use App\Helpers\RoleHelper;
use App\Helpers\StatusHelper;

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

if (!function_exists('get_pengajuan_status_badge')) {
    function get_pengajuan_status_badge($status): array
    {
        return StatusHelper::getPengajuanStatusBadge($status);
    }
}

if (!function_exists('get_magang_status_badge')) {
    function get_magang_status_badge($status): array
    {
        return StatusHelper::getMagangStatusBadge($status);
    }
}

if (!function_exists('get_user_name')) {
    function get_user_name($user)
    {
        if (has_role('MAHASISWA')) {
            return $user->mahasiswa->nama;
        } elseif (has_role('DOSEN')) {
            return $user->dosen->nama;
        } elseif (has_role('ADMIN')) {
            return $user->admin->nama;
        }
        return $user->username;
    }
}

    