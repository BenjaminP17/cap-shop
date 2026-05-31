<?php

namespace App\Enum;

enum Category: string
{
    case SNAPBACK  = 'snapback';
    case FITTED    = 'fitted';
    case DAD_CAP   = 'dad-cap';
    case TRUCKER   = 'trucker';
    case BUCKET    = 'bucket';
    case FIVE_PANEL = '5-panel';

    public function label(): string
    {
        return match($this) {
            self::SNAPBACK   => 'Snapback',
            self::FITTED     => 'Fitted',
            self::DAD_CAP    => 'Dad Cap',
            self::TRUCKER    => 'Trucker',
            self::BUCKET     => 'Bucket Hat',
            self::FIVE_PANEL => '5-Panel',
        };
    }

    public function svgId(): string
    {
        return match($this) {
            self::SNAPBACK   => 'cap-snap',
            self::FITTED     => 'cap-fit',
            self::DAD_CAP    => 'cap-dad',
            self::TRUCKER    => 'cap-trk',
            self::BUCKET     => 'cap-buc',
            self::FIVE_PANEL => 'cap-5p',
        };
    }

    public function count(): int
    {
        return match($this) {
            self::SNAPBACK   => 142,
            self::FITTED     => 88,
            self::DAD_CAP    => 66,
            self::TRUCKER    => 52,
            self::BUCKET     => 40,
            self::FIVE_PANEL => 24,
        };
    }
}
