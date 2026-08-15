<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // The audio bytes live in object storage, never here — this table only
        // records where they went. Keeping it separate from `questions` means
        // the dashboard listings never carry audio metadata they don't render.
        Schema::create('question_audios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->unique()->constrained()->cascadeOnDelete();

            // The disk is stored per row so that moving to another bucket is a
            // config change plus a backfill, not a guess about where old
            // objects went.
            $table->string('disk');
            $table->string('path');

            $table->string('voice');

            // Lets the panel tell whether the audio still matches the text it
            // was generated from. Regeneration stays a manual decision.
            $table->string('text_hash', 64);

            // What was billed: Lemonfox charges per character, so this is the
            // only local record of what a confession's narration cost.
            $table->unsignedInteger('char_count');

            $table->unsignedInteger('size');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_audios');
    }
};
