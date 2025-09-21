@extends('layouts.admin')

@section('title', 'Profile Settings')
@section('page-title', 'Profile Settings')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-1">Profile Settings</h5>
                <p class="text-muted mb-0">Manage your account information and preferences</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Basic Information</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Full Name *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address *</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Professional Title</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                               id="title" name="title" value="{{ old('title', $user->title) }}"
                                               placeholder="e.g., Professor, Assistant Professor">
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="department" class="form-label">Department</label>
                                        <input type="text" class="form-control @error('department') is-invalid @enderror"
                                               id="department" name="department" value="{{ old('department', $user->department) }}">
                                        @error('department')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                               id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="office_location" class="form-label">Office Location</label>
                                        <input type="text" class="form-control @error('office_location') is-invalid @enderror"
                                               id="office_location" name="office_location" value="{{ old('office_location', $user->office_location) }}">
                                        @error('office_location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="bio" class="form-label">Biography</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror"
                                          id="bio" name="bio" rows="5">{{ old('bio', $user->bio) }}</textarea>
                                <div class="form-text">Brief professional biography for your public profile</div>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="specializations" class="form-label">Specializations</label>
                                <textarea class="form-control @error('specializations') is-invalid @enderror"
                                          id="specializations" name="specializations" rows="3">{{ old('specializations', $user->specializations) }}</textarea>
                                <div class="form-text">Your areas of expertise (one per line)</div>
                                @error('specializations')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-2"></i>Update Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Password Change -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Change Password</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.profile.password') }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password *</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                       id="current_password" name="current_password" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">New Password *</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                               id="password" name="password" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm New Password *</label>
                                        <input type="password" class="form-control"
                                               id="password_confirmation" name="password_confirmation" required>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-key me-2"></i>Change Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Social Media Links -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Social Media & Links</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.profile.social') }}">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="website" class="form-label">Personal Website</label>
                                        <input type="url" class="form-control @error('website') is-invalid @enderror"
                                               id="website" name="website" value="{{ old('website', $user->website) }}">
                                        @error('website')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="linkedin_url" class="form-label">LinkedIn Profile</label>
                                        <input type="url" class="form-control @error('linkedin_url') is-invalid @enderror"
                                               id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $user->linkedin_url) }}">
                                        @error('linkedin_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="orcid_id" class="form-label">ORCID iD</label>
                                        <input type="text" class="form-control @error('orcid_id') is-invalid @enderror"
                                               id="orcid_id" name="orcid_id" value="{{ old('orcid_id', $user->orcid_id) }}"
                                               placeholder="0000-0000-0000-0000">
                                        @error('orcid_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="google_scholar_url" class="form-label">Google Scholar Profile</label>
                                        <input type="url" class="form-control @error('google_scholar_url') is-invalid @enderror"
                                               id="google_scholar_url" name="google_scholar_url" value="{{ old('google_scholar_url', $user->google_scholar_url) }}">
                                        @error('google_scholar_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-2"></i>Update Links
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Profile Picture -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Profile Picture</h6>
                    </div>
                    <div class="card-body text-center">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}"
                                 alt="Profile Picture"
                                 class="rounded-circle mb-3"
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                                 style="width: 150px; height: 150px;">
                                <i class="bi bi-person-circle text-muted" style="font-size: 4rem;"></i>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.profile.avatar') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <input type="file" class="form-control @error('avatar') is-invalid @enderror"
                                       id="avatar" name="avatar" accept="image/*">
                                <div class="form-text">Recommended: 400x400px</div>
                                @error('avatar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if($user->avatar)
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remove_avatar" id="remove_avatar">
                                        <label class="form-check-label" for="remove_avatar">
                                            Remove current picture
                                        </label>
                                    </div>
                                </div>
                            @endif

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-upload me-2"></i>Update Picture
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Account Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <strong>User ID:</strong> #{{ $user->id }}
                        </div>
                        <div class="mb-2">
                            <strong>Role:</strong>
                            <span class="badge bg-primary">{{ ucfirst($user->role ?? 'Admin') }}</span>
                        </div>
                        <div class="mb-2">
                            <strong>Member Since:</strong>
                            {{ $user->created_at->format('F j, Y') }}
                        </div>
                        <div class="mb-2">
                            <strong>Last Updated:</strong>
                            {{ $user->updated_at->format('F j, Y g:i A') }}
                        </div>
                        @if($user->email_verified_at)
                            <div class="mb-2">
                                <strong>Email Verified:</strong>
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>Verified
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('home') }}" class="btn btn-outline-primary" target="_blank">
                                <i class="bi bi-eye me-2"></i>View Public Profile
                            </a>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-info">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password strength indicator
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');

    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            // Add visual feedback for password strength if needed
        });
    }

    if (confirmInput) {
        confirmInput.addEventListener('input', function() {
            if (passwordInput.value !== this.value) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });
    }

    // Preview uploaded avatar
    const avatarInput = document.getElementById('avatar');
    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.querySelector('.rounded-circle');
                    if (img && img.tagName === 'IMG') {
                        img.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endsection
@endsection