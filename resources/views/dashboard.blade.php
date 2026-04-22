<!doctype html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
    data-theme="theme-default" data-assets-path="{{ asset('assets/') }}/" data-template="vertical-menu-template"
    data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    @yield('title')
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.jpeg') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/tabler-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <aside id="layout-menu" class="layout-menu menu-vertical menu menu-theme"
                style="background-color: #FEFEFF;">
                <div class="app-brand demo">
                    <a href="{{ route('dashboard.index') }}" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <img src="{{ asset('/assets/remove.logo.png') }}" alt="Project logo" />
                        </span>
                    </a>
                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                        <i class="ti menu-toggle-icon d-none d-xl-block align-middle" style="color: black"></i>
                        <i class="ti ti-x d-block d-xl-none ti-md align-middle" style="color: black"></i>
                    </a>
                </div>
                <div class="menu-inner-shadow"></div>
                <ul class="menu-inner py-1">
                    <li class="menu-item {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                        <a href="{{ route('dashboard.index') }}" class="menu-link">
                            <i class="ti ti-layout-dashboard me-3 ti-md"></i>
                            <div data-i18n="Dashboard">Dashboard</div>
                        </a>
                    </li>
                    @if (Auth::check() && in_array(Auth::user()->role, ['Admin']))
                    <li class="menu-item {{ request()->routeIs('user-management') ? 'active' : '' }}">
                        <a href="{{ route('user-management') }}" class="menu-link">
                            <i class="ti ti-users me-3 ti-md"></i>
                            <div data-i18n="User Management">User Management</div>
                        </a>
                    </li>
                    @endif



                    @if (Auth::check() && in_array(Auth::user()->role, ['Seller', 'Admin', 'Buyer']))
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text text-muted">Business</span>
                    </li>
                    <li class="menu-item {{ request()->routeIs('listings.index') ? 'active' : '' }}">
                        <a href="{{ route('listings.index') }}" class="menu-link">
                            <i class="ti ti-briefcase me-3 ti-md"></i>
                            <div data-i18n="Businesses">Businesses</div>
                        </a>
                    </li>
                    @endif
                    @if (Auth::check() && in_array(Auth::user()->role, ['Seller', 'Admin']))
                    <li class="menu-item {{ request()->routeIs('listings.approved') ? 'active' : '' }}">
                        <a href="{{ route('listings.approved') }}" class="menu-link">
                            <i class="ti ti-circle-check me-3 ti-md"></i>
                            <div data-i18n="Approved Listings">Approved Businesses</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('listings.pending') ? 'active' : '' }}">
                        <a href="{{ route('listings.pending') }}" class="menu-link">
                            <i class="ti ti-clock-hour-4 me-3 ti-md"></i>
                            <div data-i18n="Pending Listings">Pending Businesses</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('listings.rejected') ? 'active' : '' }}">
                        <a href="{{ route('listings.rejected') }}" class="menu-link">
                            <i class="ti ti-circle-x me-3 ti-md"></i>
                            <div data-i18n="Rejected Listings">Rejected Businesses</div>
                        </a>
                    </li>
                    @endif

                    @if (Auth::check() && in_array(Auth::user()->role, ['Seller']))
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text text-muted">Enquires</span>
                    </li>
                    <li class="menu-item {{ request()->routeIs('seller.enquiries.all') ? 'active' : '' }}">
                        <a href="{{ route('seller.enquiries.all') }}" class="menu-link">
                            <i class="ti ti-message-circle me-3 ti-md"></i>
                            <div data-i18n="All Enquire">All Enquire</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('seller.enquiries.approved') ? 'active' : '' }}">
                        <a href="{{ route('seller.enquiries.approved') }}" class="menu-link">
                            <i class="ti ti-checkup-list me-3 ti-md"></i>
                            <div data-i18n="Approved Enquire">Approved Enquire</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('seller.enquiries.pending') ? 'active' : '' }}">
                        <a href="{{ route('seller.enquiries.pending') }}" class="menu-link">
                            <i class="ti ti-hourglass-empty me-3 ti-md"></i>
                            <div data-i18n="Pending Enquire">Pending Enquire</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('seller.enquiries.rejected') ? 'active' : '' }}">
                        <a href="{{ route('seller.enquiries.rejected') }}" class="menu-link">
                            <i class="ti ti-ban me-3 ti-md"></i>
                            <div data-i18n="Rejected Enquire">Rejected Enquire</div>
                        </a>
                    </li>
                    @endif
                    @if (Auth::check() && in_array(Auth::user()->role, ['Buyer', 'Admin']))
                    <li class="menu-item {{ request()->routeIs('buyer.enquiries.approved') ? 'active' : '' }}">
                        <a href="{{ route('buyer.enquiries.approved') }}" class="menu-link">
                            <i class="ti ti-checkup-list me-3 ti-md"></i>
                            <div data-i18n="Enquire Approved">Enquire Approved</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('buyer.enquiries.pending') ? 'active' : '' }}">
                        <a href="{{ route('buyer.enquiries.pending') }}" class="menu-link">
                            <i class="ti ti-hourglass-empty me-3 ti-md"></i>
                            <div data-i18n="Enquire Pending">Enquire Pending</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('buyer.enquiries.rejected') ? 'active' : '' }}">
                        <a href="{{ route('buyer.enquiries.rejected') }}" class="menu-link">
                            <i class="ti ti-ban me-3 ti-md"></i>
                            <div data-i18n="Enquire Rejected">Enquire Rejected</div>
                        </a>
                    </li>
                    @endif
                    @if (Auth::check() && in_array(Auth::user()->role, ['Admin']))
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text text-muted">Blog</span>
                    </li>

                    <li class="menu-item {{ request()->routeIs('blogs.index') ? 'active' : '' }}">
                        <a href="{{ route('blogs.index') }}" class="menu-link">
                            <i class="ti ti-notebook me-3 ti-md"></i>
                            <div data-i18n="Blog">Blog</div>
                        </a>
                    </li>
                    @endif
                    @if (Auth::check() && Auth::user()->role === 'seo_manager')
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text text-muted">SEO</span>
                    </li>

                    <li class="menu-item {{ request()->routeIs('admin.seo.index', 'admin.seo.listings', 'admin.seo.listings.*', 'admin.seo.blogs', 'admin.seo.blogs.*', 'admin.seo.sitemap', 'admin.seo.sitemap.*', 'admin.seo.schema', 'admin.seo.showSeoPage') ? 'active' : '' }}">
                        <a href="{{ route('admin.seo.index') }}" class="menu-link">
                            <i class="ti ti-world-search me-3 ti-md"></i>
                            <div data-i18n="SEO Manager">SEO Manager</div>
                        </a>
                    </li>
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text text-muted">Create Page</span>
                    </li>

                    <li class="menu-item {{ request()->routeIs('admin.seo.pages', 'admin.seo.pages.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.seo.pages') }}" class="menu-link">
                            <i class="ti ti-world-search me-3 ti-md"></i>
                            <div data-i18n="Page Template">Page Template</div>
                        </a>
                    </li>
                    @endif


                    @if (Auth::check() && in_array(Auth::user()->role, ['Admin']))
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text text-muted">Tickets</span>
                    </li>
                    <li class="menu-item {{ request()->routeIs('admin.tickets') ? 'active' : '' }}">
                        <a href="{{ route('admin.tickets') }}" class="menu-link">
                            <i class="ti ti-ticket me-3 ti-md"></i>
                            <div data-i18n="Support Tickets">Support Tickets</div>
                        </a>
                    </li>
                    @endif

                    @if (Auth::check() && in_array(Auth::user()->role, ['Buyer', 'Seller']))
                    <li class="menu-item {{ request()->routeIs('tickets.*') ? 'active' : '' }}">
                        <a href="{{ route('tickets.index') }}" class="menu-link">
                            <i class="ti ti-life-buoy me-3 ti-md"></i>
                            <div data-i18n="My Tickets">My Tickets</div>
                        </a>
                    </li>
                    @endif

                    @auth
                    @if (in_array(Auth::user()->role, ['Admin', 'Buyer', 'Seller']))
                    @php
                    $isAdmin = Auth::user()->role === 'Admin';

                    // Labels role-wise
                    $lblOpen = $isAdmin ? 'All Open Tickets' : 'My Open Tickets';
                    $lblReview = $isAdmin ? 'All Under Review Tickets' : 'My Under Review Tickets';
                    $lblComp = $isAdmin ? 'All Completed Tickets' : 'My Completed Tickets';
                    $lblClosed = $isAdmin ? 'All Closed Tickets' : 'My Closed Tickets';
                    $lblReject = $isAdmin ? 'All Rejected Tickets' : 'My Rejected Tickets';

                    @endphp

                    {{-- OPEN --}}
                    <li class="menu-item {{ request()->routeIs('tickets.open') ? 'active' : '' }}">
                        <a href="{{ route('tickets.open') }}" class="menu-link">
                            <i class="ti ti-circle-dot me-3 ti-md"></i>
                            <div data-i18n="Open Tickets">{{ $lblOpen }}</div>
                        </a>
                    </li>

                    {{-- UNDER REVIEW --}}
                    <li class="menu-item {{ request()->routeIs('tickets.under_review') ? 'active' : '' }}">
                        <a href="{{ route('tickets.under_review') }}" class="menu-link">
                            <i class="ti ti-activity me-3 ti-md"></i>
                            <div data-i18n="Under Review">{{ $lblReview }}</div>
                        </a>
                    </li>

                    {{-- COMPLETED --}}
                    <li class="menu-item {{ request()->routeIs('tickets.completed') ? 'active' : '' }}">
                        <a href="{{ route('tickets.completed') }}" class="menu-link">
                            <i class="ti ti-circle-check me-3 ti-md"></i>
                            <div data-i18n="Completed">{{ $lblComp }}</div>
                        </a>
                    </li>

                    {{-- CLOSED --}}
                    <li class="menu-item {{ request()->routeIs('tickets.closed') ? 'active' : '' }}">
                        <a href="{{ route('tickets.closed') }}" class="menu-link">
                            <i class="ti ti-lock me-3 ti-md"></i>
                            <div data-i18n="Closed">{{ $lblClosed }}</div>
                        </a>
                    </li>

                    {{-- REJECTED --}}
                    <li class="menu-item {{ request()->routeIs('tickets.rejected') ? 'active' : '' }}">
                        <a href="{{ route('tickets.rejected') }}" class="menu-link">
                            <i class="ti ti-circle-x me-3 ti-md"></i>
                            <div data-i18n="Rejected">{{ $lblReject }}</div>
                        </a>
                    </li>
                    @endif
                    @endauth
                </ul>
            </aside>
            <style>
            :root {
                --sidebar-accent: #CCAA57;
                --sidebar-bg: #FEFEFF;
                --sidebar-text: #111827;
                --sidebar-muted: #6B7280;
                --sidebar-hover: rgba(204, 170, 87, 0.12);
                --sidebar-active: rgba(204, 170, 87, 0.18);
                --sidebar-border: rgba(17, 24, 39, 0.10);
            }

            /* Sidebar container */
            #layout-menu.layout-menu {
                background: var(--sidebar-bg) !important;
                border-right: 1px solid var(--sidebar-border);
                box-shadow: 0 16px 40px rgba(17, 24, 39, 0.08);
            }

            /* Logo area */
            .app-brand.demo {
                padding: 14px 16px 10px;
                border-bottom: 1px solid rgba(17, 24, 39, 0.06);
            }

            /* Logo styling */
            .app-brand-logo.demo {
                display: flex;
                justify-content: center;
                align-items: center;
                width: 180px;
                height: 68px;
            }

            .app-brand-logo.demo img {
                max-width: 100%;
                max-height: 100%;
                object-fit: contain;
            }

            /* Sidebar height adjustments */
            .dark-style .menu .app-brand.demo {
                height: 120px;
            }

            .light-style .menu .app-brand.demo {
                height: 80px;
            }

            /* Menu spacing */
            .menu-inner {
                padding: 6px 12px 14px !important;
            }

            /* Section headers */
            .menu-header {
                margin-top: 10px;
                padding: 10px 12px 6px;
            }

            .menu-header .menu-header-text {
                letter-spacing: 0.12em;
                font-weight: 900;
                color: var(--sidebar-muted) !important;
                font-size: 10px;
            }

            /* Menu links */
            .menu-item>.menu-link {
                position: relative;
                margin: 4px 6px;
                padding: 10px 12px;
                border-radius: 14px;
                color: var(--sidebar-text) !important;
                display: flex;
                align-items: center;
                gap: 10px;
                font-weight: 850;
                transition: background-color 160ms ease, transform 160ms ease, color 160ms ease, box-shadow 160ms ease;
            }

            .menu-item>.menu-link i {
                color: var(--sidebar-muted);
                transition: color 160ms ease;
            }

            /* Hover */
            .menu-item>.menu-link:hover {
                background-color: var(--sidebar-hover) !important;
                transform: translateX(2px);
                color: var(--sidebar-text) !important;
                box-shadow: 0 10px 22px rgba(17, 24, 39, 0.06);
            }

            .menu-item>.menu-link:hover i {
                color: var(--sidebar-accent);
            }

            /* Active (pill + left indicator) */
            .menu-item.active>.menu-link {
                background-color: var(--sidebar-active) !important;
                color: var(--sidebar-text) !important;
                font-weight: 950;
            }

            .menu-item.active>.menu-link i {
                color: var(--sidebar-accent) !important;
            }

            .menu-item.active>.menu-link::before {
                content: "";
                position: absolute;
                left: -6px;
                top: 12px;
                bottom: 12px;
                width: 4px;
                border-radius: 999px;
                background: var(--sidebar-accent);
            }

            /* Table header styling */
            .table-header th {
                color: #000 !important;
            }

            /* Hide customizer button */
            .template-customizer-open-btn {
                display: none !important;
            }
            </style>


            <div class="layout-page">
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="ti ti-menu-2 ti-md"></i>
                        </a>
                    </div>
                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <div class="navbar-nav align-items-center">
                            <div class="nav-item navbar-search-wrapper mb-0">
                                <a class="nav-item nav-link search-toggler d-flex align-items-center px-0"
                                    href="javascript:void(0);">
                                    <span class="d-none d-md-inline-block text-muted fw-normal">MERDERSALES BRIDGING
                                        OPPORTUNITIES</span>
                                </a>
                            </div>
                        </div>
                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="{{ Auth::user() && Auth::user()->profile_photo ? asset(Auth::user()->profile_photo) : asset('assets/img/avatars/5.png') }}"
                                            alt="User Avatar" class="rounded-circle"
                                            style="width:40px;height:40px;object-fit:cover;" />
                                    </div>


                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item mt-0">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-online">
                                                    <img src="{{ Auth::user() && Auth::user()->profile_photo ? asset(Auth::user()->profile_photo) : asset('assets/img/avatars/5.png') }}"
                                                        alt="User Avatar" class="rounded-circle"
                                                        style="width:40px;height:40px;object-fit:cover;" />
                                                </div>

                                                <div class="user-info">
                                                    <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                                                    <small class="text-muted">{{ Auth::user()->role }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider my-1 mx-n2"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile') }}">
                                            <i class="ti ti-user me-3 ti-md"></i>
                                            <span class="align-middle">My Profile</span>
                                        </a>
                                    </li>
                                    <div class="d-grid px-2 pt-2 pb-1">
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                        </form>
                                        <button class="btn btn-sm d-flex" style="background: #CCAA57; color:#fff"
                                            type="button"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <small class="align-middle">Logout</small>
                                            <i class="ti ti-logout ms-2 ti-14px"></i>
                                        </button>
                                    </div>
                            </li>
                        </ul>
                        </li>
                        </ul>
                    </div>
                </nav>
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl">
                            <div
                                class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                            </div>
                        </div>
                    </footer>
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
        <div class="drag-target"></div>
    </div>
    <!-- ================= CORE JS ================= -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    <!-- ================= DATATABLES ================= -->
    <script src="{{ asset('assets/vendor/libs/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>


    <!-- ================= FORM VALIDATION ================= -->
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>

    <!-- ================= PAGE JS ================= -->
    <script src="{{ asset('assets/js/tables-datatables-basic.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>


    <!-- ================= CUSTOM INIT ================= -->
    <script>
    $(function() {
        $('#myTable, #myTable1, #myTable2, #myTable3').DataTable({
            responsive: true,
            autoWidth: false
        });
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    (function() {
        const success = @json(session('success'));
        const error = @json(session('error'));
        if (success) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: success,
                confirmButtonColor: '#CCAA57'
            });
        } else if (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error,
                confirmButtonColor: '#CCAA57'
            });
        }
    })();
    </script>
</body>

</html>
