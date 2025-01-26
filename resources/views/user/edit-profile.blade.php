@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="profile-form">
    <h1>Edit Profile</h1>
    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="form-group">
            <label for="password">New Password (optional)</label>
            <input type="password" name="password" id="password">
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm New Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation">
        </div>

        <button type="submit" class="btn">Update Profile</button>
    </form>
</div>
@endsection
