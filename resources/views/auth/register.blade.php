<!doctype html>
<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="public/assets/" data-template="horizontal-menu-template-no-customizer" data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Register - Page | Mergersales</title>
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.jpeg') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/tabler-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/form-validation.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

<body>
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-6">
                <div class="card">
                    <div class="card-body">
                        <div class="app-brand justify-content-center mb-6">
                            <a class="app-brand-link">
                                <img src="{{ asset('assets/remove.logo.png') }}" alt="Taxi-Link Logo" width="100%"
                                    height="115px" />
                            </a>
                        </div>
                        {{-- <h4 class="mb-1">Adventure starts here 🚀</h4> --}}
                        {{-- <p class="mb-6">Bespoke Ceilings | Smart Homes | Custom Lighting | Atlanta | DM Lets make it happen.</p> --}}
                        <form id="formAuthentication" class="mb-6" method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="mb-6">
                                <label for="name" class="form-label">Username</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}"
                                    placeholder="Enter your username" required autofocus />
                                @error('name')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}"
                                    placeholder="Enter your email" required />
                                @error('email')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-6 form-password-toggle">
                                <label class="form-label" for="password">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        placeholder="••••••••••••" required autocomplete="new-password" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-6 form-password-toggle">
                                <label class="form-label" for="password-confirm">Confirm Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password-confirm" class="form-control"
                                        name="password_confirmation" placeholder="••••••••••••" required
                                        autocomplete="new-password" />
                                </div>
                            </div>
                            <div class="my-8">
                                <div class="form-check mb-0 ms-2">
                                    <input class="form-check-input" type="checkbox" id="terms-conditions"
                                        name="terms">
                                    <label class="form-check-label" for="terms-conditions">
                                        I agree to
                                        <a href="javascript:void(0);" style="color: #CCAA57">privacy policy &
                                            terms</a>
                                    </label>
                                </div>
                            </div>
                            <button class="btn d-grid w-100" type="submit"
                                style="background: #CCAA57; color: white">Sign up</button>
                        </form>
                        <p class="text-center">
                            <span>Already have an account?</span>
                            <a href="{{ route('login') }}">
                                <span style="color: #CCAA57">Sign in instead</span>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/popular.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/auto-focus.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/pages-auth.js') }}"></script>
</body>

</html>
