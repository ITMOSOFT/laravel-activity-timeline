<?php

namespace ItmoSoft\ActivityTimeline\Tests;

use DateTimeImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;
use ItmoSoft\ActivityTimeline\ActivityTimelineManager;
use ItmoSoft\ActivityTimeline\Concerns\HasActivityTimeline;
use ItmoSoft\ActivityTimeline\Models\Activity;
use PHPUnit\Framework\Attributes\Test;

class TimelineTestSubject extends Model
{
    use HasActivityTimeline;
    protected $table = 'timeline_test_subjects';
    protected $guarded = [];
    public $timestamps = false;
}

class TimelineTestCauser extends Model
{
    protected $table = 'timeline_test_causers';
    protected $guarded = [];
    public $timestamps = false;
}

class ActivityTimelineManagerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Schema::create('timeline_test_subjects', function (Blueprint $table): void {
            $table->id();
        });
        Schema::create('timeline_test_causers', function (Blueprint $table): void {
            $table->id();
        });
    }

    private function manager(): ActivityTimelineManager
    {
        return $this->app->make(ActivityTimelineManager::class);
    }

    #[Test]
    public function it_records_an_activity_with_subject_causer_and_properties(): void
    {
        $subject = TimelineTestSubject::query()->create();
        $causer = TimelineTestCauser::query()->create();

        $activity = $this->manager()->record(
            $subject, 'order.created', 'Order was created', $causer,
            ['order_number' => 'ORD-1001', 'total' => 125.50]
        );

        $this->assertInstanceOf(Activity::class, $activity);
        $this->assertSame('order.created', $activity->event);
        $this->assertSame(['order_number' => 'ORD-1001', 'total' => 125.50], $activity->properties);
        $this->assertSame($subject->getKey(), $activity->subject->getKey());
        $this->assertSame($causer->getKey(), $activity->causer->getKey());
    }

    #[Test]
    public function it_records_an_activity_without_a_causer(): void
    {
        $subject = TimelineTestSubject::query()->create();
        $activity = $this->manager()->record($subject, 'system.synced', 'System synchronization completed');
        $this->assertNull($activity->causer);
    }

    #[Test]
    public function it_rejects_an_empty_event_key(): void
    {
        $subject = TimelineTestSubject::query()->create();
        $this->expectException(InvalidArgumentException::class);
        $this->manager()->record($subject, ' ', 'Description');
    }

    #[Test]
    public function it_rejects_an_empty_description(): void
    {
        $subject = TimelineTestSubject::query()->create();
        $this->expectException(InvalidArgumentException::class);
        $this->manager()->record($subject, 'order.updated', ' ');
    }

    #[Test]
    public function it_filters_activities_by_subject(): void
    {
        $first = TimelineTestSubject::query()->create();
        $second = TimelineTestSubject::query()->create();
        $this->manager()->record($first, 'first.event', 'First subject');
        $this->manager()->record($second, 'second.event', 'Second subject');

        $activities = $this->manager()->forSubject($first)->get();
        $this->assertCount(1, $activities);
        $this->assertSame('first.event', $activities->first()->event);
    }

    #[Test]
    public function it_filters_activities_by_causer(): void
    {
        $subject = TimelineTestSubject::query()->create();
        $causer = TimelineTestCauser::query()->create();
        $this->manager()->record($subject, 'caused.event', 'Caused event', $causer);
        $this->manager()->record($subject, 'system.event', 'System event');
        $this->assertCount(1, $this->manager()->forCauser($causer)->get());
    }

    #[Test]
    public function it_orders_records_by_occurred_at(): void
    {
        $subject = TimelineTestSubject::query()->create();
        $this->manager()->record($subject, 'newer', 'Newer', null, [], new DateTimeImmutable('2026-01-02 10:00:00'));
        $this->manager()->record($subject, 'older', 'Older', null, [], new DateTimeImmutable('2026-01-01 10:00:00'));
        $this->assertSame('newer', $this->manager()->latest()->first()->event);
        $this->assertSame('older', $this->manager()->oldest()->first()->event);
    }

    #[Test]
    public function subject_trait_exposes_the_timeline_relationship(): void
    {
        $subject = TimelineTestSubject::query()->create();
        $this->manager()->record($subject, 'timeline.event', 'Timeline event');
        $this->assertCount(1, $subject->activities);
        $this->assertSame('timeline.event', $subject->activities->first()->event);
    }

    #[Test]
    public function activity_records_are_not_coupled_to_subject_deletion(): void
    {
        $subject = TimelineTestSubject::query()->create();
        $this->manager()->record($subject, 'kept.event', 'Independent activity');
        $subject->delete();
        $this->assertDatabaseCount('activity_timeline', 1);
    }
}
