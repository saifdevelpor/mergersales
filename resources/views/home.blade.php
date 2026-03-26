<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @yield('website-title')
    <link rel="shortcut icon" href="">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/plugins.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.jpeg') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/color.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.15.4/css/all.css">
    <link rel="preload"
        href="https://fonts.googleapis.com/css2?family=Raleway:wght@700&family=Montserrat:wght@400&display=swap"
        as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Raleway:wght@700&family=Montserrat:wght@400&display=swap">
    </noscript>
</head>

<body>
    <div class="loader-wrap"></div>
    <div id="main">
        <header class="main-header">
            <div class="logo-holder"><a href="{{ route('webite-home') }}"><img src="{{ asset('images/logo.png') }}"
                        alt=""></a>
            </div>
            <div class="nav-button-wrap color-bg nvminit">
                <div class="nav-button">
                    <span></span><span></span><span></span>
                </div>
            </div>
            <div class="add-list_wrap">
                <a href="{{ auth()->check() ? route('listings.index') : route('login') }}" class="add-list color-bg">
                    <i class="fal fa-plus"></i>
                    <span>Add Listing</span>
                </a>
            </div>

            <div class="show-reg-form modal-open"><i class="fas fa-user"></i><span>Sign In</span></div>
            <div class="nav-holder main-menu">
                <nav>
                    <ul class="no-list-style">
                        <li class="{{ request()->is('/') ? 'active' : '' }}">
                            <a href="{{ url('/') }}">Home</a>
                        </li>

                        <li class="{{ request()->is('About-Us') ? 'active' : '' }}">
                            <a href="{{ url('About-Us') }}">About</a>
                        </li>

                        <li class="{{ request()->is('Business') ? 'active' : '' }}">
                            <a href="{{ url('Business') }}">Business</a>
                        </li>

                        <li class="{{ request()->is('Blog') ? 'active' : '' }}">
                            <a href="{{ url('Blog') }}">Blog</a>
                        </li>

                        <li class="{{ request()->is('Contact-Us') ? 'active' : '' }}">
                            <a href="{{ url('Contact-Us') }}">Contact Us</a>
                        </li>
                    </ul>
                </nav>

                <style>
                    .no-list-style li a {
                        color: #555;
                        text-decoration: none;
                        padding: 8px 12px;
                    }

                    .no-list-style li.active a {
                        color: #CCAA57;
                        font-weight: 700;
                    }
                </style>
            </div>

        </header>
        <div id="wrapper">
            <div class="content">
                @yield('website-content')
            </div>
            <style>
                /* ===============================
   MODERN FOOTER DESIGN
   (Color Same as Theme)
================================ */

                .main-footer {
                    padding: 60px 0 30px;
                }

                .footer-widget {
                    margin-bottom: 35px;
                }

                .footer-widget-logo img {
                    max-width: 170px;
                }

                .footer-widget p {
                    margin-top: 15px;
                    line-height: 1.7;
                }

                .footer-widget-title h4 {
                    font-weight: 600;
                    margin-bottom: 20px;
                    position: relative;
                }

                .footer-widget-title h4:after {
                    content: '';
                    width: 40px;
                    height: 2px;
                    display: block;
                    margin-top: 8px;
                    background: currentColor;
                    opacity: 0.4;
                }

                .footer-list,
                .footer-contacts {
                    list-style: none;
                    padding: 0;
                    margin: 0;
                }

                .footer-list li,
                .footer-contacts li {
                    margin-bottom: 10px;
                }

                .footer-list li a,
                .footer-contacts li a {
                    transition: 0.3s ease;
                }

                .footer-list li a:hover,
                .footer-contacts li a:hover {
                    padding-left: 5px;
                }

                .footer-contacts li span {
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                }

                .footer-social ul {
                    display: flex;
                    gap: 12px;
                    padding: 0;
                    margin: 20px 0 0;
                    list-style: none;
                }

                .footer-social ul li a {
                    width: 36px;
                    height: 36px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 50%;
                    transition: 0.3s ease;
                }

                .footer-social ul li a:hover {
                    transform: translateY(-3px);
                }

                /* ===============================
   SIGNUP BUTTON MODERN
================================ */

                .api-links {
                    margin-top: 25px;
                }

                .api-btn {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: 10px;
                    padding: 14px 28px;
                    border-radius: 50px;
                    font-weight: 600;
                    text-decoration: none;
                    transition: 0.3s ease;
                }

                .api-btn:hover {
                    transform: translateY(-3px);
                }

                /* ===============================
   MOBILE RESPONSIVE
================================ */

                @media (max-width:767.98px) {

                    .main-footer {
                        text-align: center;
                    }

                    .footer-widget-logo {
                        display: flex;
                        justify-content: center;
                    }

                    .footer-widget-title h4:after {
                        margin-left: auto;
                        margin-right: auto;
                    }

                    .footer-social ul {
                        justify-content: center;
                    }

                    .api-links {
                        display: flex;
                        justify-content: center;
                    }

                }
            </style>


            <footer class="main-footer fl-wrap">
                <div class="footer-inner fl-wrap">
                    <div class="container">
                        <div class="row">

                            <!-- Column 1 -->
                            <div class="col-md-3">
                                <div class="footer-widget fl-wrap">
                                    <div class="footer-widget-logo fl-wrap">
                                        <img src="{{ asset('images/logo.png') }}" alt="Mergersales Logo">
                                    </div>
                                    <p>
                                        World's largest free, confidential M&A marketplace.
                                        Buy or sell businesses anonymously with zero fees.
                                    </p>
                                    <div class="fw_hours fl-wrap">
                                        <span>Global Marketplace: <strong>24/7 Access</strong></span><br>
                                    </div>
                                </div>
                            </div>

                            <!-- Column 2 -->
                            <div class="col-md-3">
                                <div class="footer-widget fl-wrap">
                                    <div class="footer-widget-title fl-wrap">
                                        <h4>Quick Links</h4>
                                    </div>
                                    <ul class="footer-list fl-wrap">
                                        <li><a href="{{ url('/') }}">Home</a></li>
                                        <li><a href="{{ url('About-Us') }}">About</a></li>
                                        <li><a href="{{ url('Business') }}">Business</a></li>
                                        <li><a href="{{ url('Blog') }}">Blog</a></li>
                                        <li><a href="{{ url('Contact-Us') }}">Contact Us</a></li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Column 3 -->
                            <div class="col-md-3">
                                <div class="footer-widget fl-wrap">
                                    <div class="footer-widget-title fl-wrap">
                                        <h4>Contact Info</h4>
                                    </div>
                                    <ul class="footer-contacts fl-wrap">
                                        <li> <span><i class="fas fa-envelope"></i> Support :</span> <a
                                                href="{{ route('webite-contact') }}">support@mergersales.com</a> </li>
                                        <li> <span><i class="fas fa-globe"></i> Platform :</span> <a
                                                href="{{ route('webite-business') }}">Worldwide Access</a> </li>
                                        <li> <span><i class="fas fa-headset"></i> Help Center :</span> <a
                                                href="{{ route('webite-contact') }}">help.mergersales.com</a> </li>
                                    </ul>
                                    <div class="footer-social fl-wrap">
                                        <ul>
                                            <li><a href="https://www.linkedin.com/company/merger-sales/"><i
                                                        class="fab fa-linkedin-in"></i></a></li>
                                            <li><a href="https://x.com/MergerSales"><i class="fab fa-twitter"></i></a>
                                            </li>
                                            <li><a href="https://www.facebook.com/share/15xB6mdH4c/?mibextid=wwXIfr"><i
                                                        class="fab fa-facebook-f"></i></a></li>
                                            <li><a
                                                    href="https://www.instagram.com/mergersales?igsh=MXI1MjVyMWozem42ZA%3D%3D&utm_source=qr"><i
                                                        class="fa-brands fa-instagram"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Column 4 -->
                            <div class="col-md-3">
                                <div class="footer-widget fl-wrap">
                                    <div class="footer-widget-title fl-wrap">
                                        <h4>Join Our Network</h4>
                                    </div>
                                    <p>
                                        Connect with thousands of serious buyers, sellers,
                                        investors, and advisors worldwide.
                                    </p>
                                    <div class="api-links">
                                        <a href="javascript:void(0)" class="api-btn color-bg modal-open"
                                            data-open-tab="register">
                                            <i class="fas fa-user-plus"></i>
                                            <span>Sign Up Free</span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </footer>

        </div>
        <div class="main-register-wrap modal">
            <div class="reg-overlay"></div>
            <div class="main-register-holder tabs-act">
                <div class="main-register-wrapper modal_main fl-wrap">
                    <div class="main-register-header color-bg">
                        <div class="main-register-bg">
                        </div>
                        <div class="mrb_dec"></div>
                        <div class="mrb_dec mrb_dec2"></div>
                    </div>
                    <div class="main-register">
                        <div class="close-reg"><i class="fal fa-times"></i></div>
                        <ul class="tabs-menu fl-wrap no-list-style">
                            <li class="current"><a href="#tab-1"><i class="fal fa-sign-in-alt"></i> Login</a></li>
                            <li><a href="#tab-2"><i class="fal fa-user-plus"></i> Register</a></li>
                        </ul>
                        <div class="tabs-container">
                            <div class="tab">
                                <div id="tab-1" class="tab-content first-tab">
                                    <div class="custom-form">
                                        <form method="post" id="loginForm" action="{{ route('login') }}">
                                            @csrf

                                            <label>Username or Email Address * <span class="dec-icon"><i
                                                        class="fal fa-user"></i></span></label>
                                            <input name="email" type="text" placeholder="Your Name or Mail"
                                                onClick="this.select()" value="">

                                            <div class="pass-input-wrap fl-wrap">
                                                <label>Password * <span class="dec-icon"><i
                                                            class="fal fa-key"></i></span></label>
                                                <input name="password" placeholder="Your Password" type="password"
                                                    autocomplete="off" onClick="this.select()" value="">
                                                <span class="eye"><i class="fal fa-eye"></i></span>
                                            </div>

                                            <div class="filter-tags">
                                                <input id="check-a3" type="checkbox" name="remember">
                                                <label for="check-a3">Remember me</label>
                                            </div>

                                            <button type="submit" class="log_btn color-bg"> LogIn </button>
                                        </form>

                                    </div>
                                </div>

                                <div class="tab">
                                    <div id="tab-2" class="tab-content">
                                        <div class="custom-form">
                                            <form method="post" id="registerForm" class="main-register-form"
                                                action="{{ route('register') }}">
                                                @csrf

                                                {{-- ✅ Errors show (IMPORTANT) --}}
                                                @if ($errors->any())
                                                    <div class="alert alert-danger" style="margin-bottom:10px;">
                                                        <ul style="margin:0; padding-left:18px;">
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                                <label>Full Name * <span class="dec-icon"><i
                                                            class="fal fa-user"></i></span></label>
                                                <input name="name" type="text" placeholder="Your Name"
                                                    value="{{ old('name') }}">

                                                <label>Email Address * <span class="dec-icon"><i
                                                            class="fal fa-envelope"></i></span></label>
                                                <input name="email" type="email" placeholder="Your Mail"
                                                    value="{{ old('email') }}">

                                                <div class="pass-input-wrap fl-wrap">
                                                    <label>Password * <span class="dec-icon"><i
                                                                class="fal fa-key"></i></span></label>
                                                    <input name="password" type="password"
                                                        placeholder="Your Password" autocomplete="new-password">
                                                    <span class="eye"><i class="fal fa-eye"></i></span>
                                                </div>

                                                <div class="pass-input-wrap fl-wrap">
                                                    <label>Confirm Password * <span class="dec-icon"><i
                                                                class="fal fa-key"></i></span></label>
                                                    <input name="password_confirmation" type="password"
                                                        placeholder="Confirm Password" autocomplete="new-password">
                                                    <span class="eye"><i class="fal fa-eye"></i></span>
                                                </div>

                                                {{-- ✅ terms name must be "terms" (backend jaisa) --}}
                                                <div class="filter-tags ft-list">
                                                    <input id="terms-conditions" type="checkbox" name="terms"
                                                        value="1" required>
                                                    <label for="terms-conditions">
                                                        I agree to the <a
                                                            href="{{ route('webite-privacy-policy') }}">Privacy
                                                            Policy</a> and <a
                                                            href="{{ route('webite-terms-conditions') }}">Terms and
                                                            Conditions</a>
                                                    </label>
                                                </div>

                                                <div class="clearfix"></div>
                                                <button type="submit" class="log_btn color-bg"> Register </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/plugins.js') }}"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script>
        $(document).on('click', '[data-open-tab="register"]', function() {
            // modal open trigger aapke theme ka already class "modal-open/show-reg-form" karega
            // yahan hum register tab click karwa rahe hain:
            setTimeout(function() {
                $('.tabs-menu a[href="#tab-2"]').trigger('click');
            }, 50);
        });

        $(document).on('click', '.show-reg-form:not([data-open-tab])', function() {
            // normal Sign In pe login tab hi rahe
            setTimeout(function() {
                $('.tabs-menu a[href="#tab-1"]').trigger('click');
            }, 50);
        });
    </script>

</body>

</html>
