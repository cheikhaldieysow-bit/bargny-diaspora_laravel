<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // ✅ Les checks "hasColumn" évitent les erreurs si quelqu’un a déjà ces colonnes
            if (!Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id')->nullable()->unique()->after('email');
            }

            if (!Schema::hasColumn('users', 'provider')) {
                $table->string('provider')->nullable()->after('google_id');
            }

            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('provider');
            }

            // optionnel (utile si tu veux distinguer les comptes)
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // down propre (si rollback)
            if (Schema::hasColumn('users', 'avatar')) $table->dropColumn('avatar');
            if (Schema::hasColumn('users', 'provider')) $table->dropColumn('provider');
            if (Schema::hasColumn('users', 'google_id')) $table->dropUnique(['google_id']);
            if (Schema::hasColumn('users', 'google_id')) $table->dropColumn('google_id');
        });
    }
};
