@extends('layouts.farmer')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><i class="entypo-user"></i> Profile Settings</h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <h3>Profile Settings</h3>
                            <p class="text-muted">Manage your profile information and preferences.</p>

                            <!-- Personal Information Form -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4><i class="entypo-user"></i> Personal Information</h4>
                                        </div>
                                        <div class="panel-body">
                                            <form action="{{ route('farmer.profile.update-personal') }}" method="POST">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="name">Full Name</label>
                                                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="email">Email Address</label>
                                                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="phone">Phone Number</label>
                                                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                                            @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="address">Address</label>
                                                            <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                                                            @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="entypo-check"></i> Update Personal Information
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Password Change Form -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4><i class="entypo-key"></i> Security Settings</h4>
                                        </div>
                                        <div class="panel-body">
                                            <form action="{{ route('farmer.profile.update-password') }}" method="POST">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="current_password">Current Password</label>
                                                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                                                            @error('current_password') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="password">New Password</label>
                                                            <input type="password" class="form-control" id="password" name="password" required>
                                                            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="password_confirmation">Confirm New Password</label>
                                                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-success">
                                                    <i class="entypo-lock"></i> Update Password
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Profile Picture Upload -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4><i class="entypo-picture"></i> Profile Picture</h4>
                                        </div>
                                        <div class="panel-body">
                                            <form action="{{ route('farmer.profile.upload-picture') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="profile_picture">Choose Profile Picture</label>
                                                            <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*" required>
                                                            @error('profile_picture') <span class="text-danger">{{ $message }}</span> @enderror
                                                            <small class="text-muted">Max file size: 2MB. Supported formats: JPEG, PNG, JPG, GIF</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        @if($user->profile_picture)
                                                            <div class="form-group">
                                                                <label>Current Profile Picture</label>
                                                                <div>
                                                                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-info">
                                                    <i class="entypo-upload"></i> Upload Profile Picture
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Notification Preferences -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4><i class="entypo-notification"></i> Notification Preferences</h4>
                                        </div>
                                        <div class="panel-body">
                                            <form action="{{ route('farmer.profile.update-notifications') }}" method="POST">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="checkbox text-center" style="padding: 20px 10px;">
                                                            <label style="display: block; margin-bottom: 20px;">
                                                                <input type="checkbox" name="email_notifications" value="1" {{ $user->email_notifications ? 'checked' : '' }} style="margin-bottom: 15px; transform: scale(1.2);">
                                                                <br>
                                                                <span style="font-size: 14px; color: #666;">Email Notifications</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="checkbox text-center" style="padding: 20px 10px;">
                                                            <label style="display: block; margin-bottom: 20px;">
                                                                <input type="checkbox" name="sms_notifications" value="1" {{ $user->sms_notifications ? 'checked' : '' }} style="margin-bottom: 15px; transform: scale(1.2);">
                                                                <br>
                                                                <span style="font-size: 14px; color: #666;">SMS Notifications</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="checkbox text-center" style="padding: 20px 10px;">
                                                            <label style="display: block; margin-bottom: 20px;">
                                                                <input type="checkbox" name="order_notifications" value="1" {{ $user->order_notifications ? 'checked' : '' }} style="margin-bottom: 15px; transform: scale(1.2);">
                                                                <br>
                                                                <span style="font-size: 14px; color: #666;">Order Notifications</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="checkbox text-center" style="padding: 20px 10px;">
                                                            <label style="display: block; margin-bottom: 20px;">
                                                                <input type="checkbox" name="marketing_notifications" value="1" {{ $user->marketing_notifications ? 'checked' : '' }} style="margin-bottom: 15px; transform: scale(1.2);">
                                                                <br>
                                                                <span style="font-size: 14px; color: #666;">Marketing Notifications</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    <button type="submit" class="btn btn-warning">
                                                        <i class="entypo-bell"></i> Update Notification Preferences
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Deletion -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="panel panel-danger">
                                        <div class="panel-heading">
                                            <h4><i class="entypo-trash"></i> Danger Zone</h4>
                                        </div>
                                        <div class="panel-body">
                                            <p class="text-danger"><strong>Warning:</strong> Deleting your account will permanently remove all your data, products, orders, and cannot be undone.</p>
                                            <form action="{{ route('farmer.profile.delete-account') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone!')">
                                                @csrf
                                                @method('DELETE')
                                                <div class="form-group">
                                                    <label for="confirmation">Type "DELETE" to confirm account deletion:</label>
                                                    <input type="text" class="form-control" id="confirmation" name="confirmation" placeholder="Type DELETE to confirm" required>
                                                </div>
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="entypo-trash"></i> Delete Account Permanently
                                                </button>
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
</div>
@endsection
