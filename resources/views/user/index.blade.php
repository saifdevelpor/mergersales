@extends('dashboard')

@section('title')
    <title>Users | Mergersales</title>
@endsection

@section('content')
    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <h1 style="font-size:1.5rem;font-weight:600;">Users</h1>

            <button class="btn-employee"
                style="color:white; background:#CCAA57; padding:10px 20px; border-radius:5px; border: none;"
                data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="ti ti-plus"></i> Create User
            </button>


        </div>

        {{-- TABLE --}}
        <div class="card-datatable table-responsive text-nowrap">
            <table class="table" id="myTable1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            {{-- USER + IMAGE --}}
                            <td>
                                <a href="#" class="d-flex align-items-center text-decoration-none text-dark">
                                    <img src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/avatars/defualt_profile_imgavif.avif') }}"
                                        class="rounded-circle me-2" style="width:32px;height:32px;object-fit:cover">
                                    <span class="fw-medium">{{ $user->name }}</span>
                                </a>
                            </td>


                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone_number ?? 'NA' }}</td>

                            @php
                                // Define role colors
                                $roleColors = [
                                    'Admin' => '#FF5733', // red-orange
                                    'Seller' => '#FF8D1A', // orange
                                    'Buyer' => '#3498DB', // blue
                                    'Investor' => '#28A745', // green
                                    'Advisor' => '#6C757D', // gray
                                    'Corporate' => '#6610F2', // purple
                                    'Partner' => '#E83E8C', // pink
                                ];

                                // Set color based on role, fallback to dark
                                $bgColor = $roleColors[$user->role] ?? '#343A40';
                            @endphp

                            <td>
                                <span class="badge" style="background: {{ $bgColor }}; color: #fff;">
                                    {{ $user->role }}
                                </span>
                            </td>


                            {{-- ACTIONS --}}
                            <td>
                                <div class="dropdown">
                                    <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">

                                        <a class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#editUser{{ $user->id }}">
                                            <i class="ti ti-pencil me-1"></i> Edit
                                        </a>

                                        <a class="dropdown-item text-danger" onclick="confirmDelete({{ $user->id }})">
                                            <i class="ti ti-trash me-1"></i> Delete
                                        </a>

                                        <form id="delete-form-{{ $user->id }}"
                                            action="{{ route('user-delete', e_id($user->id)) }}" method="POST"
                                            style="display:none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- EDIT MODALS (placed outside the table) --}}
    @foreach ($users as $index => $user)
        <div class="modal fade" id="editUser{{ $user->id }}" tabindex="-1"
            aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <form method="POST" action="{{ route('user-update', e_id($user->id)) }}" enctype="multipart/form-data"
                    class="modal-content">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        <!-- Row 1: Name + Father Name -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name{{ $user->id }}" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name{{ $user->id }}" name="name"
                                    value="{{ $user->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="father_name{{ $user->id }}" class="form-label">Father Name</label>
                                <input type="text" class="form-control" id="father_name{{ $user->id }}"
                                    name="father_name" value="{{ $user->father_name }}">
                            </div>
                        </div>

                        <!-- Row 2: Email + Role -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email{{ $user->id }}" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email{{ $user->id }}" name="email"
                                    value="{{ $user->email }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="role{{ $user->id }}" class="form-label">Role</label>
                                <select name="role" id="role{{ $user->id }}" class="form-control" required>
                                    @foreach (['Admin', 'Seller', 'Buyer', 'Investor', 'Advisor', 'Corporate', 'Partner'] as $r)
                                        <option value="{{ $r }}" {{ $user->role == $r ? 'selected' : '' }}>
                                            {{ $r }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Row 3: Profile Photo -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="profile_photo{{ $user->id }}" class="form-label">Profile Photo</label>
                                <input type="file" class="form-control" id="profile_photo{{ $user->id }}"
                                    name="profile_photo">
                                @if ($user->profile_photo)
                                    <img src="{{ asset($user->profile_photo) }}" alt="Profile Photo"
                                        class="mt-2 rounded-circle" style="width:80px; height:80px; object-fit:cover;">
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label for="phone{{ $user->id }}" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone{{ $user->id }}" name="phone_number"
                                    value="{{ $user->phone_number }}" required>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mt-3">
                            <button type="submit" class="btn" style="background: #CCAA57; color:white;">Update
                                User</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    @endforeach

    {{-- CREATE MODAL --}}
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form method="POST" action="{{ route('user-save') }}" enctype="multipart/form-data" class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Create User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name"
                            placeholder="Enter Name" required>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="Enter Email" required>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Enter Password" required>
                    </div>

                    <!-- Profile Photo -->
                    <div class="mb-3">
                        <label for="profile_photo" class="form-label">Profile Photo</label>
                        <input type="file" class="form-control" id="profile_photo" name="profile_photo">
                    </div>

                    <!-- Role -->
                    <div class="mb-3">
                        <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="">Select Role</option>
                            <option value="Admin">Admin</option>
                            <option value="Seller">Seller</option>
                            <option value="Buyer">Buyer</option>
                            <option value="Investor">Investor</option>
                            <option value="Advisor">Advisor</option>
                            <option value="Corporate">Corporate</option>
                            <option value="Partner">Partner</option>
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid mt-3">
                        <button type="submit" class="btn"
                            style="background: #CCAA57; color: white; font-weight: 500;">Create User</button>
                    </div>

                </div>
            </form>
        </div>
    </div>




    {{-- DELETE --}}
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function confirmDelete(id) {
            swal({
                title: "Are you sure?",
                text: "This user will be deleted permanently",
                icon: "warning",
                buttons: true,
                dangerMode: true
            }).then((willDelete) => {
                if (willDelete) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endsection
