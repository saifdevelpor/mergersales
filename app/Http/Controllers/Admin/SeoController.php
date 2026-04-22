<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\SeoHelper;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Industry;
use App\Models\Listing;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SeoController extends Controller
{
    public function index()
    {
        $stats = [
            'pages' => Page::count(),
            'listings' => Listing::count(),
            'blogs' => Blog::count(),
            'schemas' => Listing::whereNotNull('schema_json')->count(),
        ];

        return view('admin.seo.index', compact('stats'));
    }

    public function publicPage(Page $page)
    {
        $seo = SeoHelper::forPage($page, [
            'title' => $page->meta_title ?: ($page->heading ?: $page->name),
            'description' => $page->meta_description ?: Str::limit(strip_tags((string) $page->description), 320, ''),
            'canonical' => route('seo.pages.show', $page->slug),
            'og_title' => $page->og_title ?: ($page->heading ?: $page->name),
            'og_description' => $page->og_description ?: ($page->meta_description ?: Str::limit(strip_tags((string) $page->description), 200, '')),
        ]);

        return view('admin.seo.page-landing', compact('page', 'seo'));
    }

    public function showSeoPage($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        $seo = SeoHelper::forPage($page, [
            'canonical' => route('seo.pages.show', $page->slug),
        ]);

        return view('admin.seo.page-landing', compact('page', 'seo'));
    }

    public function pages()
    {
        $pages = Page::query()->latest()->get();

        return view('admin.seo.pages', compact('pages'));
    }

    public function storePage(Request $request): RedirectResponse
    {
        $data = $this->validatePageCreate($request);
        $data['og_image'] = $this->storeOgImageUpload($request);
        $page = Page::create($data);
        Cache::flush();

        return back()->with('success', 'Custom page created successfully: ' . $page->slug);
    }

    public function bulkStorePages(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'bulk_rows' => ['required', 'string'],
            'template_name' => ['required', 'string', 'max:255'],
            'template_heading' => ['required', 'string', 'max:255'],
            'template_description' => ['required', 'string'],
            'template_keywords' => ['nullable', 'string', 'max:1000'],
            'template_meta_title' => ['nullable', 'string', 'max:255'],
            'template_meta_description' => ['nullable', 'string', 'max:1000'],
            'template_og_title' => ['nullable', 'string', 'max:255'],
            'template_og_description' => ['nullable', 'string', 'max:1000'],
            'template_schema_type' => ['nullable', 'string', 'max:255'],
        ]);

        $rows = preg_split('/\r\n|\r|\n/', (string) $data['bulk_rows']);
        $created = 0;
        $skipped = [];
        $seenSlugs = [];

        foreach ($rows as $index => $row) {
            $line = trim($row);

            if ($line === '') {
                continue;
            }

            $parts = array_map('trim', explode('|', $line));

            if (count($parts) < 2) {
                $skipped[] = 'Line ' . ($index + 1) . ': use `Name | Slug | Country | Keywords | Heading` format.';
                continue;
            }

            [$name, $slug, $country, $keywords, $heading] = array_pad($parts, 5, null);
            $slug = Str::slug((string) $slug);

            if ($slug === '') {
                $skipped[] = 'Line ' . ($index + 1) . ': slug missing or invalid.';
                continue;
            }

            if (isset($seenSlugs[$slug]) || Page::where('slug', $slug)->exists()) {
                $skipped[] = 'Line ' . ($index + 1) . ': slug `' . $slug . '` already exists.';
                continue;
            }

            $context = [
                'name' => $name ?: Str::title(str_replace('-', ' ', $slug)),
                'slug' => $slug,
                'country' => $country ?: '',
                'keywords' => $keywords ?: '',
                'heading' => $heading ?: '',
            ];

            $resolvedHeading = $this->fillTemplate($data['template_heading'], $context);
            $resolvedName = $this->fillTemplate($data['template_name'], array_merge($context, ['heading' => $resolvedHeading]));

            Page::create([
                'name' => $resolvedName,
                'slug' => $slug,
                'heading' => $resolvedHeading,
                'description' => $this->fillTemplate($data['template_description'], array_merge($context, ['heading' => $resolvedHeading])),
                'keywords' => $this->fillTemplate((string) ($data['template_keywords'] ?? ''), array_merge($context, ['heading' => $resolvedHeading])),
                'meta_title' => $this->fillTemplate((string) ($data['template_meta_title'] ?? ''), array_merge($context, ['heading' => $resolvedHeading, 'name' => $resolvedName])),
                'meta_description' => $this->fillTemplate((string) ($data['template_meta_description'] ?? ''), array_merge($context, ['heading' => $resolvedHeading, 'name' => $resolvedName])),
                'og_title' => $this->fillTemplate((string) ($data['template_og_title'] ?? ''), array_merge($context, ['heading' => $resolvedHeading, 'name' => $resolvedName])),
                'og_description' => $this->fillTemplate((string) ($data['template_og_description'] ?? ''), array_merge($context, ['heading' => $resolvedHeading, 'name' => $resolvedName])),
                'schema_type' => $data['template_schema_type'] ?? 'WebPage',
                'robots_index' => true,
                'robots_follow' => true,
            ]);

            $seenSlugs[$slug] = true;
            $created++;
        }

        Cache::flush();

        $message = $created . ' pages created successfully.';

        if ($skipped !== []) {
            $message .= ' Skipped: ' . implode(' ', array_slice($skipped, 0, 5));
        }

        return back()->with($created > 0 ? 'success' : 'error', $message);
    }

    public function updatePage(Request $request, Page $page): RedirectResponse
    {
        $data = $this->validatePageUpdate($request, $page);
        $data['og_image'] = $this->storeOgImageUpload($request, $page->og_image);
        $page->update($data);
        Cache::flush();

        return back()->with('success', 'Page SEO updated successfully.');
    }

    public function destroyPage(Page $page): RedirectResponse
    {
        $name = $page->name;
        $this->deleteManagedOgImage($page->og_image);
        $page->delete();
        Cache::flush();

        return back()->with('success', 'Page deleted successfully: ' . $name);
    }

    public function bulkDestroyPages(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'page_ids' => ['required', 'array', 'min:1'],
            'page_ids.*' => ['integer', 'exists:pages,id'],
        ]);

        $pages = Page::query()->whereIn('id', $data['page_ids'])->get();

        if ($pages->isEmpty()) {
            return back()->with('error', 'No valid pages selected for deletion.');
        }

        foreach ($pages as $page) {
            $this->deleteManagedOgImage($page->og_image);
            $page->delete();
        }

        Cache::flush();

        return back()->with('success', $pages->count() . ' selected pages deleted successfully.');
    }

    public function listings()
    {
        $listings = Listing::with('industry')->latest()->paginate(20);

        return view('admin.seo.listings', compact('listings'));
    }

    public function updateListing(Request $request, Listing $listing): RedirectResponse
    {
        $data = $this->validateListing($request);
        $data['og_image'] = $this->storeOgImageUpload($request, $listing->og_image);
        $listing->update($data);
        Cache::flush();

        return back()->with('success', 'Listing SEO updated successfully.');
    }

    public function blogs()
    {
        $blogs = Blog::latest()->paginate(20);

        return view('admin.seo.blogs', compact('blogs'));
    }

    public function updateBlog(Request $request, Blog $blog): RedirectResponse
    {
        $data = $this->validateBlog($request);
        $data['og_image'] = $this->storeOgImageUpload($request, $blog->og_image);
        $blog->update($data);
        Cache::flush();

        return back()->with('success', 'Blog SEO updated successfully.');
    }

    public function sitemap()
    {
        $path = public_path('sitemap.xml');
        $exists = File::exists($path);
        $lastModified = $exists ? now()->createFromTimestamp(File::lastModified($path)) : null;

        return view('admin.seo.sitemap', compact('exists', 'lastModified'));
    }

    public function generateSitemap(): RedirectResponse
    {
        $this->buildSitemap();

        return back()->with('success', 'Sitemap generated successfully.');
    }

    public function schema()
    {
        $pages = Page::orderBy('name')->get();
        $listings = Listing::latest()->take(10)->get();
        $blogs = Blog::latest()->take(10)->get();
        $industries = Industry::orderBy('name')->take(10)->get();

        return view('admin.seo.schema', compact('pages', 'listings', 'blogs', 'industries'));
    }

    public function publicSitemap()
    {
        $path = public_path('sitemap.xml');

        if (! File::exists($path)) {
            $this->buildSitemap();
        }

        return response()->file($path, ['Content-Type' => 'application/xml']);
    }

    private function buildSitemap(): void
    {
        $sitemap = Sitemap::create()
            ->add(Url::create('/'))
            ->add(Url::create(route('webite-business')))
            ->add(Url::create(route('webite-blog')))
            ->add(Url::create(route('webite-about')))
            ->add(Url::create(route('webite-contact')));

        Page::query()->get()->each(function (Page $page) use ($sitemap): void {
            try {
                $url = $page->route_name
                    ? route($page->route_name)
                    : route('seo.pages.show', $page->slug);

                $sitemap->add(Url::create($url)->setLastModificationDate($page->updated_at));
            } catch (\Throwable $e) {
            }
        });

        Listing::query()->where('status', 'Approved')->get()->each(fn (Listing $listing) => $sitemap->add(
            Url::create(route('seo.business.show', $listing->slug))->setLastModificationDate($listing->updated_at)
        ));

        Blog::query()->get()->each(fn (Blog $blog) => $sitemap->add(
            Url::create(route('seo.blog.show', $blog->slug))->setLastModificationDate($blog->updated_at)
        ));

        Industry::query()->get()->each(fn (Industry $industry) => $sitemap->add(
            Url::create(route('seo.industry.show', $industry->slug))->setLastModificationDate($industry->updated_at)
        ));

        Listing::query()->select('country')->whereNotNull('country')->distinct()->pluck('country')->each(
            fn (string $country) => $sitemap->add(Url::create(route('seo.country.show', \Illuminate\Support\Str::slug($country))))
        );

        $sitemap->writeToFile(public_path('sitemap.xml'));
    }

    private function validatePageCreate(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:pages,slug'],
            'route_name' => ['nullable', 'string', 'max:255', 'unique:pages,route_name'],
            'heading' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'keywords' => ['nullable', 'string', 'max:1000'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string', 'max:1000'],
            'og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'robots_index' => ['nullable', 'boolean'],
            'robots_follow' => ['nullable', 'boolean'],
            'schema_type' => ['nullable', 'string', 'max:255'],
        ]);

        return $this->sanitizeMetaPayload($data);
    }

    private function validatePageUpdate(Request $request, Page $page): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:pages,slug,' . $page->id],
            'route_name' => ['nullable', 'string', 'max:255', 'unique:pages,route_name,' . $page->id],
            'heading' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'keywords' => ['nullable', 'string', 'max:1000'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string', 'max:1000'],
            'og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'robots_index' => ['nullable', 'boolean'],
            'robots_follow' => ['nullable', 'boolean'],
            'schema_type' => ['nullable', 'string', 'max:255'],
        ]);

        return $this->sanitizeMetaPayload($data);
    }

    private function validateListing(Request $request): array
    {
        $data = $request->validate([
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:1000'],
            'slug' => ['required', 'string', 'max:255', 'unique:listings,slug,' . $request->route('listing')->id],
            'focus_keyword' => ['nullable', 'string', 'max:255'],
            'og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'schema_json' => ['nullable', 'string'],
        ]);

        return $this->sanitizeMetaPayload($data);
    }

    private function validateBlog(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:1000'],
            'slug' => ['required', 'string', 'max:255', 'unique:blogs,slug,' . $request->route('blog')->id],
            'og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'featured_image_alt' => ['nullable', 'string', 'max:255'],
        ]);

        return $this->sanitizeMetaPayload($data);
    }

    private function sanitizeMetaPayload(array $data): array
    {
        if (isset($data['slug'])) {
            $data['slug'] = Str::slug((string) $data['slug']);
        }

        foreach (['name', 'heading', 'keywords', 'meta_title', 'meta_description', 'og_title', 'og_description', 'seo_title', 'seo_description', 'focus_keyword', 'featured_image_alt', 'title'] as $field) {
            if (isset($data[$field])) {
                $limit = in_array($field, ['keywords', 'meta_description', 'og_description', 'seo_description'], true) ? 1000 : 255;
                $data[$field] = SeoHelper::sanitizeMeta($data[$field], $limit);
            }
        }

        if (isset($data['description'])) {
            $data['description'] = trim(strip_tags((string) $data['description']));
        }

        if (array_key_exists('schema_json', $data)) {
            $data['schema_json'] = SeoHelper::sanitizeSchemaJson($data['schema_json']);
        }

        $data['robots_index'] = (bool) ($data['robots_index'] ?? false);
        $data['robots_follow'] = (bool) ($data['robots_follow'] ?? false);

        return $data;
    }

    private function storeOgImageUpload(Request $request, ?string $currentPath = null): ?string
    {
        if (! $request->hasFile('og_image')) {
            return $currentPath;
        }

        $path = $request->file('og_image')->store('seo/og-images', 'public');

        if ($currentPath && $this->isManagedOgImage($currentPath) && Storage::disk('public')->exists($currentPath)) {
            Storage::disk('public')->delete($currentPath);
        }

        return $path;
    }

    private function isManagedOgImage(string $path): bool
    {
        return Str::startsWith($path, ['seo/og-images/', 'storage/seo/og-images/']);
    }

    private function deleteManagedOgImage(?string $path): void
    {
        if (! $path || ! $this->isManagedOgImage($path)) {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function fillTemplate(string $template, array $context): string
    {
        $replace = [];

        foreach ($context as $key => $value) {
            $replace['{' . $key . '}'] = (string) $value;
        }

        return trim(strtr($template, $replace));
    }
}
