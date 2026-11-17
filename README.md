# ITMOSOFT Laravel Activity Timeline

A lightweight polymorphic activity feed for Laravel 12 and 13. Record chronological events against Eloquent models, attribute events to actors, and store structured properties.

## Requirements
- PHP 8.3+
- Laravel 12 or 13

## Installation
```bash
composer require itmosoft/laravel-activity-timeline
php artisan vendor:publish --tag=itmosoft-activity-timeline-config
php artisan migrate
```

## Record an activity
```php
use ItmoSoft\ActivityTimeline\Facades\ActivityTimeline;

ActivityTimeline::record(
    subject: $order,
    event: 'status.changed',
    description: 'Order status changed to processing',
    causer: auth()->user(),
    properties: ['from' => 'pending', 'to' => 'processing'],
);
```

## Retrieve a timeline
```php
$activities = ActivityTimeline::forSubject($order)->latest()->limit(20)->get();
```

Add the relationship to a model:
```php
use ItmoSoft\ActivityTimeline\Concerns\HasActivityTimeline;

class Order extends Model
{
    use HasActivityTimeline;
}
```
Then query `$order->activities`.

## API
- `record(Model $subject, string $event, string $description, ?Model $causer = null, array $properties = [], ?DateTimeInterface $occurredAt = null): Activity`
- `forSubject(Model $subject): Builder`
- `forCauser(Model $causer): Builder`
- `latest(): Builder`
- `oldest(): Builder`

## Configuration
Publish `config/activity-timeline.php` to change the table name:
```php
return ['table' => 'activity_timeline'];
```

## Scope and limitations
- Explicit application events only; no automatic model observers.
- This is an activity feed, not a replacement for `itmosoft/laravel-audit-log`.
- No admin UI, HTTP endpoints, notifications, retention scheduler, or export tools.
- Properties must be JSON-serializable. Avoid storing secrets or unnecessary personal data.
- Applications must enforce their own authorization for viewing activities.

## Testing
```bash
composer install
composer test
```

MIT License. See LICENSE.
