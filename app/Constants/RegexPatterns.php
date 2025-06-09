<?php

namespace App\Constants;

class RegexPatterns
{
    public const ALPHANUMERIC = '^[a-zA-Z0-9]+$';
    public const SAFE_INPUT = '/^[a-zA-Z0-9\s_\-\.\,\\\']+$/';

    public const NEGATIVE_INPUT = '/^[^!@#$%^&*()+=\[\]{}|\\:;"<>?\/~`]+$/';
}
