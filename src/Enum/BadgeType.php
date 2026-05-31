<?php

namespace App\Enum;

enum BadgeType: string
{
    case NEW_DROP  = 'NEW DROP';
    case NEW       = 'NEW';
    case JUST_IN   = 'JUST IN';
    case LOW_STOCK = '+ STOCK BAS';
    case COLLAB    = 'COLLAB';
    case SALE      = 'SOLDES';

    public function cssClass(): string
    {
        return match($this) {
            self::NEW_DROP  => 'drop__badge--coral',
            self::NEW       => 'drop__badge--klein',
            self::JUST_IN   => '',
            self::LOW_STOCK => 'drop__badge--coral',
            self::COLLAB    => 'drop__badge--klein',
            self::SALE      => 'drop__badge--coral',
        };
    }
}
