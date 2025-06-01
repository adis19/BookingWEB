<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type'); // bookings, revenue, rooms, users, occupancy
            $table->json('data')->nullable(); // JSON данные отчета
            $table->foreignId('generated_by')->constrained('users')->onDelete('cascade');
            $table->datetime('period_start')->nullable();
            $table->datetime('period_end')->nullable();
            $table->enum('status', ['generating', 'completed', 'failed'])->default('generating');
            $table->timestamps();

            $table->index(['type', 'created_at']);
            $table->index(['generated_by', 'created_at']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
};
