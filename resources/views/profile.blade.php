@extends('dashboard')

@section('title')
    <title>Profile | Mergersales</title>
@endsection

@section('content')

    @php
        use App\Models\User;
        $users = $users ?? User::where('role', 'User')->get();
    @endphp

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <!-- Header -->
            <div class="row">
                <div class="col-12">
                    <div class="card mb-6">
                        <!-- Banner -->
                        <div class="user-profile-header-banner">
                            <img src="{{ asset('assets/profile-image.jpg') }}" alt="Banner" class="rounded-top w-100"
                                style="max-height:200px; object-fit:cover;">
                        </div>
                        <div
                            class="user-profile-header d-flex flex-column flex-lg-row align-items-center text-sm-start text-center mb-5">

                            <!-- Image Left Side (Vertically Centered) -->
                            <div class="flex-shrink-0 d-flex justify-content-center align-items-center mx-sm-0 mx-auto"
                                style="min-width: 120px;">

                                @if ($user->profile_photo)
                                    <img src="{{ asset($user->profile_photo) }}" alt="{{ $user->name }} image"
                                        class="d-block rounded-circle user-profile-img"
                                        style="width: 100px; height: 100px; object-fit: cover;" />
                                @else
                                    <img src="{{ asset('assets/img/avatars/5.png') }}" alt="Default user image"
                                        class="d-block rounded-circle user-profile-img"
                                        style="width: 100px; height: 100px; object-fit: cover;" />
                                @endif

                            </div>

                            <!-- User Info -->
                            <div class="flex-grow-1 mt-3 mt-lg-0 ps-lg-4">
                                <div class="user-profile-info text-start">
                                    <h4 class="mb-2">{{ $user->name }}</h4>
                                    <ul class="list-inline mb-0 d-flex align-items-center flex-wrap gap-3">

                                        <li class="list-inline-item d-flex gap-2 align-items-center">
                                            <i class="ti ti-palette ti-lg"></i>
                                            <span class="fw-medium">
                                                @if (($user->role ?? auth()->user()->role) === 'User')
                                                    {{ old('role', $user->employee->role ?? ($user->role ?? (auth()->user()->employee->role ?? 'User'))) }}
                                                @elseif (($user->role ?? auth()->user()->role) === 'Admin')
                                                    {{ old('role', $user->role ?? (auth()->user()->role ?? 'Admin')) }}
                                                @else
                                                    {{ old('role', $user->role ?? (auth()->user()->role ?? 'N/A')) }}
                                                @endif
                                            </span>
                                        </li>


                                        <li class="list-inline-item d-flex gap-2 align-items-center">
                                            <i class="ti ti-map-pin ti-lg"></i>
                                            <span class="fw-medium">{{ $user->address ?? 'N/A' }}</span>
                                        </li>

                                        <li class="list-inline-item d-flex gap-2 align-items-center">
                                            <i class="ti ti-flag ti-lg"></i>
                                            <span class="fw-medium">
                                                Pakistan
                                            </span>
                                        </li>

                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="container">

            <div class="row mb-4">
                <div class="col-12">
                    <div class="nav-align-top">
                        <ul class="nav nav-pills flex-column flex-sm-row mb-3 gap-2 gap-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active" href="#profile" data-bs-toggle="tab">
                                    <i class="ti ti-user-check me-1_5"></i> Profile
                                </a>
                            </li>

                            {{-- @if (isset($user) && $user->role === 'Admin')
                                <li class="nav-item">
                                    <a class="nav-link" href="#Document" data-bs-toggle="tab">
                                        <i class="ti ti-briefcase me-1_5"></i> Document
                                    </a>
                                </li>
                            @endif --}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content">
            <!-- Profile Tab -->
            <div class="tab-pane fade show active" id="profile">
                <div class="row">
                    <!-- Left: Profile Card -->
                    <div class="col-xl-4 col-lg-5 col-md-5 mb-4">
                        <div class="card mb-6">
                            <div class="card-body">
                                <small class="text-uppercase text-muted">About</small>
                                <ul class="list-unstyled my-3 py-1">
                                    <li class="d-flex align-items-center mb-4">
                                        <i class="ti ti-user ti-lg"></i><span class="fw-medium mx-2">Full Name:</span>
                                        <span>{{ $user->name }}</span>
                                    </li>
                                    <li class="d-flex align-items-center mb-4">
                                        <i class="ti ti-user ti-lg"></i><span class="fw-medium mx-2">Father Name:</span>
                                        <span>{{ $user->father_name }}</span>
                                    </li>
                                    <li class="d-flex align-items-center mb-4">
                                        <i class="ti ti-credit-card ti-lg"></i><span class="fw-medium mx-2">ID
                                            Card:</span>
                                        <span>{{ $user->id_card }}</span>
                                    </li>
                                    <li class="d-flex align-items-center mb-4">
                                        <i class="ti ti-check ti-lg"></i><span class="fw-medium mx-2">Status:</span>
                                        <span>Active</span>
                                    </li>
                                    <li class="d-flex align-items-center mb-4">
                                        <i class="ti ti-crown ti-lg"></i><span class="fw-medium mx-2">Role:</span>
                                        <span>
                                            {{ $user->role }}
                                        </span>
                                    </li>
                                    <li class="d-flex align-items-center mb-4">
                                        <i class="ti ti-flag ti-lg"></i><span class="fw-medium mx-2">Country:</span>
                                        <span>Pakistan</span>
                                    </li>
                                    <li class="d-flex align-items-center mb-4">
                                        <i class="ti ti-language ti-lg"></i><span class="fw-medium mx-2">Languages:</span>
                                        <span>English / Urdu / Punjabi</span>
                                    </li>

                                </ul>
                                <small class="text-uppercase text-muted">Contacts</small>
                                <ul class="list-unstyled my-3 py-1">
                                    <li class="d-flex align-items-center mb-4">
                                        <i class="ti ti-phone-call ti-lg"></i><span class="fw-medium mx-2">Phone
                                            Number:</span>
                                        <span>{{ $user->phone_number }}</span>
                                    </li>
                                    <li class="d-flex align-items-center mb-4">
                                        <i class="ti ti-mail ti-lg"></i><span class="fw-medium mx-2">Email:</span>
                                        <span>{{ $user->email }}</span>
                                    </li>

                                    <li class="d-flex align-items-center mb-4">
                                        <i class="ti ti-map-pin ti-lg"></i><span class="fw-medium mx-2">Address:</span>
                                        <span>{{ $user->address }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Profile Form -->
                    <div class="col-xl-8 col-lg-7 col-md-7 mb-4">
                        <div class="card">
                            <div class="card-header text-black">
                                <h5>Profile Settings</h5>
                            </div>
                            <div class="card-body">
                                <!-- Success Alert -->
                                <!--@if (session('success'))
    -->
                                <!--    <div class="alert alert-success alert-dismissible fade show" role="alert">-->
                                <!--        {{ session('success') }}-->
                                <!--        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>-->
                                <!--    </div>-->
                                <!--
    @endif-->

                                <!-- Error Alert -->
                                <!--@if ($errors->any())
    -->
                                <!--    <div class="alert alert-danger alert-dismissible fade show" role="alert">-->
                                <!--        <ul class="mb-0">-->
                                <!--            @foreach ($errors->all() as $error)
    -->
                                <!--                <li>{{ $error }}</li>-->
                                <!--
    @endforeach-->
                                <!--        </ul>-->
                                <!--        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>-->
                                <!--    </div>-->
                                <!--
    @endif-->


                                <form action="{{ route('profile.update', $user->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label for="name" class="form-label">User Name</label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ $user->name }}" placeholder="Enter Name">
                                    </div>

                                    <div class="mb-3">
                                        <label for="father_name" class="form-label">Father Name</label>
                                        <input type="text" name="father_name" class="form-control"
                                            value="{{ $user->father_name }}" placeholder="Enter Father Name">
                                    </div>

                                    <div class="mb-3">
                                        <label for="id_card" class="form-label">ID Card</label>
                                        <input type="text" name="id_card" class="form-control"
                                            value="{{ $user->id_card }}" placeholder="Enter ID Card">
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control"
                                            value="{{ $user->email }}" placeholder="Enter Email Address">
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone_number" class="form-label">Phone Number</label>
                                        <input type="text" name="phone_number" class="form-control"
                                            value="{{ $user->phone_number }}" placeholder="Enter Phone Number">
                                    </div>

                                    <div class="mb-3">
                                        <label for="address" class="form-label">Address</label>
                                        <textarea name="address" class="form-control" placeholder="Enter Your Address">{{ $user->address }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="image" class="form-label">Profile Picture</label>
                                        <input type="file" name="profile_photo" class="form-control">
                                        @if ($user->profile_photo)
                                            <img src="{{ asset($user->profile_photo) }}" width="100" class="mt-2">
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">New Password (optional)</label>
                                        <input type="password" name="password" class="form-control"
                                            placeholder="Enter New Password">
                                    </div>

                                    <button type="submit" class="btn" style="background: #CCAA57; color:white">Update
                                        Profile</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div> <!-- row end -->
            </div> <!-- profile tab end -->
        </div>
    </div>
@endsection
