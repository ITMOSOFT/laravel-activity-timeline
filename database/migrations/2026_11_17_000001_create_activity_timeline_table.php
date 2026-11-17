<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(config('activity-timeline.table', 'activity_timeline'), function (Blueprint $table): void {
            $table->id();
            $table->morphs('subject');
            $table->nullableMorphs('causer');
            $table->string('event', 120);
            $table->string('description');
            $table->json('properties')->nullable();
            $table->timestamp('occurred_at')->index();
            $table->timestamps();
            $table->index(['subject_type', 'subject_id', 'occurred_at'], 'activity_subject_timeline_idx');
            $table->index(['causer_type', 'causer_id', 'occurred_at'], 'activity_causer_timeline_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('activity-timeline.table', 'activity_timeline'));
    }
};
