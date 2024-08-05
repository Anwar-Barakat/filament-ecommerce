<?php

namespace App\Enum;

enum OrderStatusEnum: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case DECLINED = 'declined';
    case CANCELLED = 'cancelled';


    public static function getValues(): array
    {
        return array_map(fn($status) => $status->value, self::cases());
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($status) => [$status->value => ucfirst(strtolower($status->value))])
            ->toArray();
    }
}
