<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('listings')) {
            Schema::table('listings', function (Blueprint $table) {
                if (! Schema::hasColumn('listings', 'seo_title')) {
                    $table->string('seo_title')->nullable()->after('business_name');
                }
                if (! Schema::hasColumn('listings', 'seo_description')) {
                    $table->text('seo_description')->nullable()->after('seo_title');
                }
                if (! Schema::hasColumn('listings', 'slug')) {
                    $table->string('slug')->nullable()->after('seo_description');
                }
                if (! Schema::hasColumn('listings', 'focus_keyword')) {
                    $table->string('focus_keyword')->nullable()->after('slug');
                }
                if (! Schema::hasColumn('listings', 'og_image')) {
                    $table->string('og_image')->nullable()->after('focus_keyword');
                }
                if (! Schema::hasColumn('listings', 'schema_json')) {
                    $table->longText('schema_json')->nullable()->after('og_image');
                }
            });

            $this->backfillUniqueSlug('listings', 'business_name');

            Schema::table('listings', function (Blueprint $table) {
                if (Schema::hasColumn('listings', 'slug')) {
                    $table->unique('slug');
                }
            });
        }

        if (Schema::hasTable('blogs')) {
            Schema::table('blogs', function (Blueprint $table) {
                if (! Schema::hasColumn('blogs', 'title')) {
                    $table->string('title')->nullable()->after('user_id');
                }
                if (! Schema::hasColumn('blogs', 'seo_title')) {
                    $table->string('seo_title')->nullable()->after('title');
                }
                if (! Schema::hasColumn('blogs', 'seo_description')) {
                    $table->text('seo_description')->nullable()->after('seo_title');
                }
                if (! Schema::hasColumn('blogs', 'slug')) {
                    $table->string('slug')->nullable()->after('seo_description');
                }
                if (! Schema::hasColumn('blogs', 'og_image')) {
                    $table->string('og_image')->nullable()->after('slug');
                }
                if (! Schema::hasColumn('blogs', 'featured_image_alt')) {
                    $table->string('featured_image_alt')->nullable()->after('og_image');
                }
            });

            $this->backfillUniqueSlug('blogs', 'title', 'details');

            Schema::table('blogs', function (Blueprint $table) {
                if (Schema::hasColumn('blogs', 'slug')) {
                    $table->unique('slug');
                }
            });
        }

        if (Schema::hasTable('industries')) {
            Schema::table('industries', function (Blueprint $table) {
                if (! Schema::hasColumn('industries', 'meta_title')) {
                    $table->string('meta_title')->nullable()->after('slug');
                }
                if (! Schema::hasColumn('industries', 'meta_description')) {
                    $table->text('meta_description')->nullable()->after('meta_title');
                }
                if (! Schema::hasColumn('industries', 'og_image')) {
                    $table->string('og_image')->nullable()->after('meta_description');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('industries')) {
            Schema::table('industries', function (Blueprint $table) {
                foreach (['meta_title', 'meta_description', 'og_image'] as $column) {
                    if (Schema::hasColumn('industries', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('blogs')) {
            Schema::table('blogs', function (Blueprint $table) {
                foreach (['title', 'seo_title', 'seo_description', 'slug', 'og_image', 'featured_image_alt'] as $column) {
                    if (Schema::hasColumn('blogs', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('listings')) {
            Schema::table('listings', function (Blueprint $table) {
                foreach (['seo_title', 'seo_description', 'slug', 'focus_keyword', 'og_image', 'schema_json'] as $column) {
                    if (Schema::hasColumn('listings', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }

    private function backfillUniqueSlug(string $table, string $primarySource, ?string $fallbackSource = null): void
    {
        $columns = array_values(array_filter(['id', 'slug', $primarySource, $fallbackSource]));
        $rows = DB::table($table)->select($columns)->get();
        $used = [];

        foreach ($rows as $row) {
            $seed = $row->$primarySource ?: ($fallbackSource ? strip_tags((string) ($row->$fallbackSource ?? '')) : null);
            $base = Str::slug(Str::limit((string) $seed, 70, ''));
            $slug = $base !== '' ? $base : $table . '-' . $row->id;
            $counter = 2;

            while (in_array($slug, $used, true) || DB::table($table)->where('slug', $slug)->where('id', '!=', $row->id)->exists()) {
                $slug = ($base !== '' ? $base : $table . '-' . $row->id) . '-' . $counter;
                $counter++;
            }

            $used[] = $slug;

            DB::table($table)->where('id', $row->id)->update([
                'slug' => $slug,
                $primarySource => $row->$primarySource ?: Str::limit(strip_tags((string) ($row->$fallbackSource ?? 'Untitled')), 255, ''),
            ]);
        }
    }
};
