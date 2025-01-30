@extends('layouts.app')

@section('title', 'User Profile')

@section('content')
<div class="min-h-[calc(100vh-200px)] bg-gradient-to-br from-blue-50 to-indigo-50">
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-2xl overflow-hidden animate-fade-in-down">
            <div class="px-8 py-6">
                <div class="text-center mb-8">
                    <div class="mb-6 flex justify-center">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}"
                                 alt="{{ $user->name }}"
                                 class="w-24 h-24 rounded-full object-cover">
                        @else
                            <div class="w-24 h-24 rounded-full bg-blue-600 flex items-center justify-center">
                                <span class="text-white text-3xl font-medium">
                                    {{ Str::initials($user->name) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                        {{ $user->name }}
                    </h1>
                    <p class="mt-2 text-gray-600">{{ $user->email }}</p>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="text-gray-700">Member since {{ $user->created_at->format('M Y') }}</span>
                    </div>

                    @if(auth()->id() === $user->id)
                    <div class="flex justify-center space-x-4 mt-6">
                        <a href="{{ route('profile.edit') }}"
                           class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-2 px-6 rounded-lg font-semibold
                                  hover:from-blue-700 hover:to-indigo-700 transition-all transform hover:scale-105 inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            Edit Profile
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
