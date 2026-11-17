<?php

namespace ItmoSoft\ActivityTimeline;

use Illuminate\Support\ServiceProvider;

class ActivityTimelineServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/activity-timeline.php', 'activity-timeline');
        $this->app->singleton(ActivityTimelineManager::class, fn () => new ActivityTimelineManager());
        $this->app->alias(ActivityTimelineManager::class, 'activity-timeline');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/activity-timeline.php' => config_path('activity-timeline.php'),
        ], 'itmosoft-activity-timeline-config');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
