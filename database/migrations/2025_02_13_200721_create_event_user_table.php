<?php

use App\Models\User;
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
        Schema::create('event_user', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Event::class);
            $table->foreignIdFor(User::class);
            $table->string('status');
            $table->timestamps();
            $table->index(['user_id', 'status', 'event_id'], 'user_status_index');
            $table->index(['event_id', 'user_id', 'updated_at'], 'event_user_index');
            $table->index(['event_id', 'status', 'updated_at'], 'event_status_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_user');
    }
};
