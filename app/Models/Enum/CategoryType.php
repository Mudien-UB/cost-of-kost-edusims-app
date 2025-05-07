<?php

namespace App\Models\Enum;

enum CategoryType: string
{
    case INCOME = 'income';
    case EXPENSE = 'expense';

    public function label(): string
    {
        return match ($this) {
            self::INCOME => 'income',
            self::EXPENSE => 'expense',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::INCOME => '#28a745',
            self::EXPENSE => '#dc3545',
        };
    }
}
