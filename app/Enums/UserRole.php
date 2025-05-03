<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'ADMIN';
    case DOSEN = 'DOSEN';
    case MAHASISWA = 'MAHASISWA';
}
