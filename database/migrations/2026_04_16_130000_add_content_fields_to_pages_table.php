<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('pages')) {
            return;
        }

        Schema::table('pages', function (Blueprint $table) {
            if (! Schema::hasColumn('pages', 'heading')) {
                $table->string('heading')->nullable()->after('route_name');
            }

            if (! Schema::hasColumn('pages', 'description')) {
                $table->longText('description')->nullable()->after('heading');
            }

            if (! Schema::hasColumn('pages', 'keywords')) {
                $table->text('keywords')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('pages')) {
            return;
        }

        Schema::table('pages', function (Blueprint $table) {
            foreach (['keywords', 'description', 'heading'] as $column) {
                if (Schema::hasColumn('pages', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
