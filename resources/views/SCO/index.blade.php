<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.jpeg') }}" />
    <title>SCO Page | Mergersales</title>

    <!-- SEO Meta Tags -->
    <meta name="robots" content="index, follow">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Structured Data for Enhanced SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "{{ $business->name ?? 'Business Listing' }}",
        "description": "{{ $metaDescription }}",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "{{ ucfirst($country) }}",
            "addressRegion": "NY",
            "addressCountry": "US"
        },
        "priceRange": "{{ $business->price ?? 'Contact for Price' }}"
    }
    </script>

    <!-- External CSS and Bootstrap for better design -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #CCAA57;
            color: white;
            padding: 20px;
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 36px;
        }

        .business-listings {
            padding: 20px;
        }

        .business-item {
            background-color: white;
            border-radius: 8px;
            padding: 20px 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .business-item h2 {
            color: #333;
        }

        .business-item p {
            color: #555;
            margin: 8px 0;
        }

        .business-item p strong {
            color: #333;
        }

        footer {
            background-color: #CCAA57;
            color: white;
            padding: 10px;
            text-align: center;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

        footer p {
            margin: 0;
        }

        /* Equal height for all cards */
        .card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Adding gap between the cards */
        .business-item {
            margin-bottom: 30px;
        }

        /* Responsive design adjustments */
        @media (max-width: 768px) {
            header h1 {
                font-size: 28px;
            }

            .business-item {
                padding: 15px;
            }

            .business-item h2 {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>

    <header>
        <h1>{{ $title }}</h1>
    </header>

    <main class="container">
        <section class="business-listings">
            <!-- Check if any businesses are available -->
            @if ($businesses->isEmpty())
                <div class="alert alert-warning">No businesses found for this deal type, city, industry, and
                    sub-industry.</div>
            @else
                <div class="row">
                    <!-- Loop through the businesses and display their information -->
                    @foreach ($businesses as $business)
                        <div class="col-md-4 mb-4 d-flex">
                            <div class="business-item">
                                <div class="listing-image-wrapper text-center mt-3">
                                    <img src="{{ rtrim('http://localhost/Mergersales/storage/app/public/', '/') . '/' . $business->business_img }}"
                                        class="listing-image rounded-img" alt="Business">
                                </div>
                                <style>
                                    .listing-image {
                                        /* Set the image size */
                                        width: 100px;
                                        /* Adjust the width */
                                        height: 100px;
                                        /* Adjust the height */
                                        object-fit: cover;
                                        /* Ensures the image covers the area without distortion */
                                    }

                                    .rounded-img {
                                        /* Apply rounded corners or make it circular */
                                        border-radius: 50%;
                                        /* For circular image */
                                        /* You can also use smaller values for rounded corners, like `border-radius: 10px;` */
                                    }
                                </style>

                                <h2>{{ $business->name }}</h2>
                                <p><strong>Name: </strong>{{ $business->business_name }}</p>
                                <p><strong>Description:</strong> {{ $business->description }}</p>
                                <p><strong>Deal Type:</strong> {{ ucfirst($business->deal_type) }}</p>
                                <p><strong>Reson For Sale:</strong> {{ ucfirst($business->reason_for_sale ?? 'N/A') }}
                                </p>
                                <p><strong>Industry:</strong> {{ ucfirst($business->industry->name ?? 'Unknown') }}</p>
                                <p><strong>Sub-Industry:</strong>
                                    {{ ucfirst($business->subIndustry->name ?? 'Unknown') }}</p>
                                <p><strong>Location:</strong> {{ ucfirst($business->country) }}</p>
                                <p><strong>Currency:</strong> {{ ucfirst($business->currency) }}</p>
                                <p><strong>EBITDA:</strong>
                                    {{ $business->currency }}
                                    {{ $business->ebitda_range ?? 'N/A' }}
                                </p>

                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} Mergersales. All Rights Reserved.</p>
    </footer>

    <!-- Optional: Add Bootstrap JS for interactivity -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
