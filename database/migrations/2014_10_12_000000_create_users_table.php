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
      Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete();

    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();

    $table->string('address')->nullable();
    $table->string('phone')->nullable();

    // Google auth
    $table->string('google_id')->nullable()->unique();
    $table->string('provider')->nullable(); // ex: google
    $table->string('avatar')->nullable();

    $table->string('password');
    $table->rememberToken();
    $table->timestamps();

    // Index utile si tu fais des recherches fréquentes
    $table->index(['provider', 'google_id']);
});

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
