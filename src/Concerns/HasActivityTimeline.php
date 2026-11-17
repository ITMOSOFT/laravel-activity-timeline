<?php

namespace ItmoSoft\ActivityTimeline\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use ItmoSoft\ActivityTimeline\Models\Activity;

trait HasActivityTimeline
{
    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'subject')
            ->orderByDesc('occurred_at')
            ->orderByDesc('id');
    }
}
