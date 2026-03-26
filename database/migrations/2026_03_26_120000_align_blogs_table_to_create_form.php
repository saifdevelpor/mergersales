<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Align blogs table with actual create form fields.
     * Form currently uses: user_id, details, image (+ timestamps).
     */
    public function up(): void
    {
        if (!Schema::hasTable('blogs')) {
            return;
        }

        Schema::table('blogs', function (Blueprint $table) {
            if (!Schema::hasColumn('blogs', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('blogs', 'details')) {
                $table->longText('details')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('blogs', 'image')) {
                $table->text('image')->nullable()->after('details');
            }
        });

        // Remove legacy columns if they exist.
        Schema::table('blogs', function (Blueprint $table) {
            if (Schema::hasColumn('blogs', 'publish_by')) {
                $table->dropColumn('publish_by');
            }
            if (Schema::hasColumn('blogs', 'title')) {
                $table->dropColumn('title');
            }
            if (Schema::hasColumn('blogs', 'description')) {
                $table->dropColumn('description');
            }
        });
    }

    public function down(): void
    {
        // Kept intentionally minimal; this migration normalizes legacy schemas.
    }
};

