@extends('layouts.app')

@section('title', 'Create User')
@section('page_title', 'Create User')

@section('content')
<div class="mb-4">
    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Users
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h5 class="m-0 fw-bold"><i class="fa-solid fa-user-plus text-primary me-2"></i>User Details</h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('users.store') }}" method="POST">
            @include('users._form')
        </form>
    </div>
</div>
@endsection
