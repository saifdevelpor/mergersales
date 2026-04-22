<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $seo['description'] ?? ($page->meta_description ?? $page->description) }}">
    <meta name="keywords" content="{{ $page->keywords }}">
    <meta name="robots"
        content="{{ $page->robots_index ? 'index' : 'noindex' }}, {{ $page->robots_follow ? 'follow' : 'nofollow' }}">
    <link rel="canonical" href="{{ $seo['canonical'] ?? ($page->canonical_url ?? url()->current()) }}">

    <meta property="og:title" content="{{ $seo['og']['title'] ?? ($page->og_title ?? $page->heading) }}">
    <meta property="og:description"
        content="{{ $seo['og']['description'] ?? ($page->og_description ?? $page->meta_description) }}">
    <meta property="og:image"
        content="{{ $seo['og']['image'] ?? ($page->og_image ?? asset('assets/img/default-og-image.jpg')) }}">

    <title>{{ $seo['title'] ?? ($page->meta_title ?? $page->heading) }} | Mergersale</title>
    <!-- Style Start -->
    <style>
    :root {
        --bg: #f6f7fb;
        --surface: rgba(255, 255, 255, 0.92);
        --surface-solid: #ffffff;
        --ink: #1f2937;
        --muted: #6a7fa2;
        --accent: #CCAA57;
        --accent-deep: #b89235;
        --dark: #1a202c;
        --line: rgba(31, 41, 55, 0.08);
        --shadow: 0 20px 50px rgba(31, 41, 55, 0.10);
    }

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: Georgia, "Times New Roman", serif;
        color: var(--ink);
        background:
            radial-gradient(circle at top left, rgba(204, 170, 87, 0.18), transparent 26%),
            linear-gradient(180deg, #ffffff 0%, var(--bg) 100%);
    }

    .shell {
        width: min(1120px, calc(100% - 32px));
        margin: 0 auto;
    }

    .hero {
        padding: 40px 0 28px;
    }

    .hero-card {
        background: linear-gradient(135deg, #ffffff 0%, #fbf8ef 65%, #f7f0dc 100%);
        color: var(--ink);
        border-radius: 28px;
        padding: 44px;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow);
        border: 1px solid rgba(204, 170, 87, 0.20);
    }

    .hero-card::after {
        content: "";
        position: absolute;
        inset: auto -60px -60px auto;
        width: 220px;
        height: 220px;
        border-radius: 999px;
        background: rgba(204, 170, 87, 0.14);
    }

    .hero-card::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 0;
        width: 6px;
        background: linear-gradient(180deg, var(--accent), var(--accent-deep));
    }

    .eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(204, 170, 87, 0.12);
        color: var(--accent-deep);
        font-size: 12px;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        font-weight: 700;
    }

    h1 {
        margin: 18px 0 14px;
        font-size: clamp(2.4rem, 5vw, 4.8rem);
        line-height: 0.98;
        max-width: 11ch;
    }

    .hero-text {
        max-width: 760px;
        font-size: 1.08rem;
        line-height: 1.8;
        color: #4b5563;
    }

    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        margin-top: 26px;
    }

    .hero-actions a {
        text-decoration: none;
        padding: 13px 20px;
        border-radius: 999px;
        font-weight: 700;
    }

    .hero-actions .primary {
        background: var(--accent);
        color: #fff;
        box-shadow: 0 12px 24px rgba(204, 170, 87, 0.25);
    }

    .hero-actions .secondary {
        border: 1px solid rgba(204, 170, 87, 0.35);
        color: var(--dark);
        background: #fff;
    }

    .grid {
        display: grid;
        grid-template-columns: 1.3fr 0.7fr;
        gap: 22px;
        padding-bottom: 40px;
    }

    .panel {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 24px;
        box-shadow: var(--shadow);
    }

    .content-panel {
        padding: 30px;
    }

    .content-panel h2,
    .sidebar h3 {
        margin: 0 0 12px;
        font-size: 1.25rem;
    }

    .copy {
        line-height: 1.9;
        color: #374151;
        white-space: pre-line;
    }

    .sidebar {
        padding: 24px;
        display: grid;
        gap: 18px;
        align-content: start;
    }

    .meta-box {
        background: var(--surface-solid);
        border: 1px solid var(--line);
        border-radius: 18px;
        padding: 18px;
        box-shadow: 0 10px 24px rgba(17, 24, 39, 0.04);
    }

    .meta-box strong {
        display: block;
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: var(--muted);
        margin-bottom: 8px;
    }

    .keyword-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }

    .keyword {
        padding: 10px 14px;
        border-radius: 999px;
        background: rgba(204, 170, 87, 0.14);
        color: var(--accent-deep);
        font-size: 0.92rem;
        font-weight: 700;
    }

    .stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-top: 20px;
    }

    .stat {
        background: #fff;
        border: 1px solid rgba(204, 170, 87, 0.16);
        border-radius: 20px;
        padding: 18px;
    }

    .stat .label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--muted);
    }

    .stat .value {
        margin-top: 8px;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--dark);
    }

    footer {
        padding: 0 0 34px;
        color: var(--muted);
        font-size: 0.94rem;
    }

    @media (max-width: 860px) {
        .hero-card {
            padding: 30px 22px;
        }

        .grid {
            grid-template-columns: 1fr;
        }

        .stats {
            grid-template-columns: 1fr;
        }
    }
    </style>
    <!-- Style End -->
</head>
<!-- Yaha sa Body Start ho rahe ha  -->

<body>
    @php
    $keywords = collect(explode(',', (string) $page->keywords))
    ->map(fn ($keyword) => trim($keyword))
    ->filter()
    ->values();
    @endphp

    <section class="hero">
        <div class="shell">
            <div class="hero-card">
                <div class="eyebrow">Mergersales Landing Page</div>
                <h1>{{ $page->heading ?: $page->name }}</h1>
                <div class="hero-text">
                    {{ $page->meta_description ?: \Illuminate\Support\Str::limit((string) $page->description, 240, '') }}
                </div>

                <div class="stats">
                    <div class="stat">
                        <div class="label">SEO Slug</div>
                        <div class="value">{{ $page->slug }}</div>
                    </div>
                    <div class="stat">
                        <div class="label">Schema</div>
                        <div class="value">{{ $page->schema_type ?: 'WebPage' }}</div>
                    </div>
                    <div class="stat">
                        <div class="label">Index Status</div>
                        <div class="value">{{ $page->robots_index ? 'Indexed' : 'Noindex' }}</div>
                    </div>
                </div>

                <div class="hero-actions">
                    <a class="primary" href="{{ url('/Contact-Us') }}">Talk to Our Team</a>
                    <a class="secondary" href="{{ url('/Business') }}">Browse Businesses</a>
                </div>
            </div>
        </div>
    </section>

    <main class="shell grid">
        <section class="panel content-panel">
            <h2>Page Overview</h2>
            <div class="copy">
                {{ $page->description ?: 'This page is ready for custom SEO content. Update the description from the SEO manager to publish a stronger landing page.' }}
            </div>
        </section>

        <aside class="panel sidebar">
            <div class="meta-box">
                <strong>Meta Title</strong>
                <div>{{ $page->meta_title ?: ($page->heading ?: $page->name) }}</div>
            </div>

            <div class="meta-box">
                <strong>Meta Description</strong>
                <div>{{ $page->meta_description ?: 'Add a focused SEO meta description from the admin page builder.' }}
                </div>
            </div>

            <div class="meta-box">
                <strong>Canonical URL</strong>
                <div>{{ $page->canonical_url ?: route('seo.pages.show', $page->slug) }}</div>
            </div>

            <div class="meta-box">
                <strong>Target Keywords</strong>
                @if ($keywords->isNotEmpty())
                <div class="keyword-list">
                    @foreach ($keywords as $keyword)
                    <span class="keyword">{{ $keyword }}</span>
                    @endforeach
                </div>
                @else
                <div>No keywords added yet.</div>
                @endif
            </div>
        </aside>
    </main>

    <footer>
        <div class="shell">&copy; 2026 Mergersale. Custom SEO page powered by the page builder.</div>
    </footer>
</body>

</html>