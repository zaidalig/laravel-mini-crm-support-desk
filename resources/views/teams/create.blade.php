@extends('layouts.app')

@section('title', 'Create Team')
@section('page_title', 'Create Team')

@section('content')
<div class="mb-4">
    <a href="{{ route('teams.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Teams
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h5 class="m-0 fw-bold"><i class="fa-solid fa-people-group text-primary me-2"></i>Team Setup</h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('teams.store') }}" method="POST">
            @include('teams._form')
        </form>
    </div>
</div>
@endsection
