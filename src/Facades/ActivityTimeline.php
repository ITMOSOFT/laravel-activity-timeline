<?php

namespace ItmoSoft\ActivityTimeline\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \\ItmoSoft\\ActivityTimeline\\Models\\Activity record(\\Illuminate\\Database\\Eloquent\\Model $subject, string $event, string $description, ?\\Illuminate\\Database\\Eloquent\\Model $causer = null, array $properties = [], ?\\DateTimeInterface $occurredAt = null)
 * @method static \\Illuminate\\Database\\Eloquent\\Builder forSubject(\\Illuminate\\Database\\Eloquent\\Model $subject)
 * @method static \\Illuminate\\Database\\Eloquent\\Builder forCauser(\\Illuminate\\Database\\Eloquent\\Model $causer)
 * @method static \\Illuminate\\Database\\Eloquent\\Builder latest()
 * @method static \\Illuminate\\Database\\Eloquent\\Builder oldest()
 */
class ActivityTimeline extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'activity-timeline';
    }
}
