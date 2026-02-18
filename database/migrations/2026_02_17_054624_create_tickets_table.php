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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_no')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('subject');
            $table->string('category')->default('Other');
            $table->text('message');

            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');

            // Only Admin will update this
            $table->enum('status', [
                'open',
                'under_review',
                'completed',
                'rejected',
                'closed'
            ])->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
