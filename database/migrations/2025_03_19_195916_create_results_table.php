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
        Schema::create('results', function (Blueprint $table) {
            $table->ulid('id');
            $table->foreignIdFor(\App\Models\Pool::class);
            $table->foreignIdFor(\App\Models\User::class);
            $table->foreignIdFor(\App\Models\Team::class);
            $table->string('judging_type'); // strategy name
            $table->json('data'); // flexible data structure for storing results
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
