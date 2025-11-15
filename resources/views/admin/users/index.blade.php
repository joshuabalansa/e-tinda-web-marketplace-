@extends('layouts.admin')
@section('content')

<!-- Page Header -->
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4>User Management</h4>
                </div>
                <div class="panel-options">
                    <span class="text-muted">Manage all system users</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-md-4 col-sm-6 mb-3">
        <div class="panel panel-default">
            <div class="panel-body text-center">
                <h3 class="text-primary">{{ $roleCounts['admin'] }}</h3>
                <p class="text-muted">Admins</p>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-6 mb-3">
        <div class="panel panel-default">
            <div class="panel-body text-center">
                <h3 class="text-success">{{ $roleCounts['farmer'] }}</h3>
                <p class="text-muted">Farmers</p>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-6 mb-3">
        <div class="panel panel-default">
            <div class="panel-body text-center">
                <h3 class="text-info">{{ $roleCounts['buyer'] }}</h3>
                <p class="text-muted">Buyers</p>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">Search & Filters</div>
            </div>
            <div class="panel-body">
                <form method="GET" action="{{ route('admin.users.index') }}" class="form-inline">
                    <div class="form-group">
                        <input type="text" name="search" class="form-control" placeholder="Search by name, email, or business..." value="{{ request('search') }}">
                    </div>

                    <div class="form-group">
                        <select name="role" class="form-control">
                            <option value="">All Roles</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="farmer" {{ request('role') == 'farmer' ? 'selected' : '' }}>Farmer</option>
                            <option value="buyer" {{ request('role') == 'buyer' ? 'selected' : '' }}>Buyer</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="entypo-search"></i> Search
                    </button>

                    <a href="{{ route('admin.users.index') }}" class="btn btn-default">
                        <i class="entypo-cancel"></i> Clear
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">Users List</div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Business</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <strong>{{ $user->name }}</strong>
                                    @if($user->profile_picture)
                                        <img src="{{ $user->profile_picture }}" alt="{{ $user->name }}" class="img-circle" width="30" height="30" style="margin-left: 10px;">
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="label label-{{ $user->role->value === 'admin' ? 'danger' : ($user->role->value === 'farmer' ? 'success' : 'info') }}">
                                        {{ ucfirst($user->role->value) }}
                                    </span>
                                </td>
                                <td>{{ $user->business_name ?: '-' }}</td>
                                <td>
                                    @if($user->is_active)
                                        <span class="label label-success">Active</span>
                                    @else
                                        <span class="label label-warning">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info">
                                            <i class="entypo-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">
                                            <i class="entypo-pencil"></i>
                                        </a>

                                        @if($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-warning' : 'btn-success' }}"
                                                        onclick="return confirm('Are you sure you want to {{ $user->is_active ? 'deactivate' : 'activate' }} this user?')">
                                                    <i class="entypo-{{ $user->is_active ? 'block' : 'check' }}"></i>
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone!')">
                                                    <i class="entypo-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">
                                    <i class="entypo-users" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <br><br>
                                    No users found matching your criteria.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                <div class="text-center">
                    {{ $users->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .panel {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .table {
        margin-bottom: 0;
    }
    .table th {
        border-top: none;
        font-weight: 600;
        background-color: #f8f9fa;
    }
    .btn-group .btn {
        margin-right: 2px;
    }
    .form-inline .form-group {
        margin-right: 10px;
    }
    .img-circle {
        border-radius: 50%;
    }
</style>
@endpush
