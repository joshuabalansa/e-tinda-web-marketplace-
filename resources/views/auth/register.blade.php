<x-guest-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-body p-4 p-md-5">
                        <!-- Logo -->
                        <div class="text-center mb-4">
                            <i class="fas fa-leaf fa-3x text-success mb-3"></i>
                            <h2 class="text-success fw-bold">Create Your Account</h2>
                        </div>

                        <!-- Validation Errors -->
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <!-- Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-user text-muted"></i>
                                    </span>
                                    <input type="text"
                                           class="form-control"
                                           id="name"
                                           name="name"
                                           value="{{ old('name') }}"
                                           required
                                           autofocus
                                           placeholder="Enter your full name">
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-envelope text-muted"></i>
                                    </span>
                                    <input type="email"
                                           class="form-control"
                                           id="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           required
                                           placeholder="Enter your email">
                                </div>
                            </div>

                            <!-- Role -->
                            <div class="mb-3">
                                <label for="role" class="form-label">Account Type</label>
                                <select id="role"
                                        name="role"
                                        class="form-select"
                                        required>
                                    <option value="">Select your account type</option>
                                    <option value="farmer" {{ old('role') == 'farmer' ? 'selected' : '' }}>I am a farmer (vendor)</option>
                                    <option value="buyer" {{ old('role') == 'buyer' ? 'selected' : '' }}>I am a buyer</option>
                                </select>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input type="password"
                                           class="form-control"
                                           id="password"
                                           name="password"
                                           required
                                           placeholder="Create a password">
                                </div>
                                <small class="text-muted">Minimum 8 characters</small>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input type="password"
                                           class="form-control"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           required
                                           placeholder="Confirm your password">
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-user-plus me-2"></i> Register
                                </button>
                            </div>

                            <!-- Login Link -->
                            <div class="text-center">
                                <p class="mb-0">Already have an account?
                                    <a href="{{ route('login') }}" class="text-decoration-none text-success fw-bold">
                                        Login here
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            body {
                background-color: #f8f9fa;
                min-height: 100vh;
                display: flex;
                align-items: center;
            }
            .card {
                border: none;
                border-radius: 10px;
            }
            .form-control:focus, .form-select:focus {
                box-shadow: none;
                border-color: #28a745;
            }
            .input-group-text {
                background-color: #f8f9fa;
                border-right: none;
            }
            .input-group .form-control {
                border-left: none;
            }
            .btn-success {
                background-color: #28a745;
                border-color: #28a745;
            }
            .btn-success:hover {
                background-color: #218838;
                border-color: #1e7e34;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Password match validation
                const password = document.getElementById('password');
                const confirmPassword = document.getElementById('password_confirmation');
                const form = document.querySelector('form');

                form.addEventListener('submit', function(e) {
                    if (password.value !== confirmPassword.value) {
                        e.preventDefault();
                        confirmPassword.classList.add('is-invalid');
                        confirmPassword.nextElementSibling.textContent = 'Passwords do not match';
                    }
                });

                confirmPassword.addEventListener('input', function() {
                    if (password.value !== confirmPassword.value) {
                        confirmPassword.classList.add('is-invalid');
                        confirmPassword.nextElementSibling.textContent = 'Passwords do not match';
                    } else {
                        confirmPassword.classList.remove('is-invalid');
                    }
                });
            });
        </script>
    @endpush
</x-guest-layout>
