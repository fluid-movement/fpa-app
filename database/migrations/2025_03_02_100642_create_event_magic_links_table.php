<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event_magic_links', function (Blueprint $table) {
            $table->ulid('id');
            $table->foreignIdFor(\App\Models\Event::class)->constrained()->cascadeOnDelete();
            $table->dateTime('expires_at');
            $table->timestamps();
            $table->index('event_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_magic_links');
    }
};
