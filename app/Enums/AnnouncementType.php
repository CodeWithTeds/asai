<?php

namespace App\Enums;

enum AnnouncementType: string
{
    case General = 'general';
    case Event = 'event';
    case Activity = 'activity';
    case News = 'news';
    case Alert = 'alert';

    public function label(): string
    {
        return match ($this) {
            self::General => 'General',
            self::Event => 'Event',
            self::Activity => 'Activity',
            self::News => 'News',
            self::Alert => 'Alert',
        };
    }
}
