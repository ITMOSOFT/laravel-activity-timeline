<?php

namespace ItmoSoft\ActivityTimeline\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Activity extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'properties' => 'array',
            'occurred_at' => 'immutable_datetime',
        ];
    }

    public function getTable(): string
    {
        return config('activity-timeline.table', 'activity_timeline');
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function causer(): MorphTo
    {
        return $this->morphTo();
    }
}
