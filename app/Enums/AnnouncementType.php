<?php

namespace App\Enums;

enum AnnouncementType: string
{
    case Event = 'event';
    case Activity = 'activity';
    case News = 'news';
    case Promos = 'promos';

    public function label(): string
    {
        return match ($this) {
            self::Event => 'Event',
            self::Activity => 'Activity',
            self::News => 'News',
            self::Promos => 'Promos',
        };
    }
}
