<?php

namespace App\Enum;

enum Location: string
{
    case A = 'A';
    case B = 'B';
    case C = 'C';
    case D = 'D';

    /**
     * Obtener todos los valores del enum.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getAllCasesAsArray(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
