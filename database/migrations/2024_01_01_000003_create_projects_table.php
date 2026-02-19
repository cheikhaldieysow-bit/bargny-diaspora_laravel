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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('titre');
            $table->text('description')->nullable();
            $table->text('problem')->nullable();
            $table->text('objectif')->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->integer('duration')->nullable()->comment('Duration in days');
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'in_progress', 'completed'])->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('funded_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
