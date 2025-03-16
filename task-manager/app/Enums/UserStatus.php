<?php

namespace App\Enums;

enum UserStatus: string
{
    case ACTIVE = 'active';
    case VACATION = 'vacation';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}