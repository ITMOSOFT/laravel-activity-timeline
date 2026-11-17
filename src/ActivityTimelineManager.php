<?php

namespace ItmoSoft\ActivityTimeline;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use ItmoSoft\ActivityTimeline\Models\Activity;

class ActivityTimelineManager
{
    public function record(
        Model $subject,
        string $event,
        string $description,
        ?Model $causer = null,
        array $properties = [],
        ?DateTimeInterface $occurredAt = null,
    ): Activity {
        if (trim($event) === '') {
            throw new InvalidArgumentException('Activity event key cannot be empty.');
        }
        if (trim($description) === '') {
            throw new InvalidArgumentException('Activity description cannot be empty.');
        }

        $activity = new Activity();
        $activity->subject()->associate($subject);
        if ($causer !== null) {
            $activity->causer()->associate($causer);
        }
        $activity->event = $event;
        $activity->description = $description;
        $activity->properties = $properties;
        $activity->occurred_at = $occurredAt ?? now();
        $activity->save();

        return $activity;
    }

    public function forSubject(Model $subject): Builder
    {
        return Activity::query()
            ->where('subject_type', $subject->getMorphClass())
            ->where('subject_id', (string) $subject->getKey());
    }

    public function forCauser(Model $causer): Builder
    {
        return Activity::query()
            ->where('causer_type', $causer->getMorphClass())
            ->where('causer_id', (string) $causer->getKey());
    }

    public function latest(): Builder
    {
        return Activity::query()->orderByDesc('occurred_at')->orderByDesc('id');
    }

    public function oldest(): Builder
    {
        return Activity::query()->orderBy('occurred_at')->orderBy('id');
    }
}
