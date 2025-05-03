@extends('layouts.admin')
@section('content')
<div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Header with Logout -->
                <div class="flex justify-between items-center p-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold">Dashboard</h2>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
                <!-- Main Content -->
                <div class="p-6 text-gray-900">
                    @if(auth()->user()->isAdmin())
                        {{ __("You're logged in as an administrator!") }}
                    @elseif(auth()->user()->isFarmer())
                        {{ __("You're logged in as a farmer!") }}
                    @else
                        {{ __("You're logged in as a buyer!") }}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
