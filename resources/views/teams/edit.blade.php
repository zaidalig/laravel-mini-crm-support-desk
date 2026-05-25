@extends('layouts.app')

@section('title', 'Edit Team')
@section('page_title', 'Edit Team')

@section('content')
<div class="mb-4">
    <a href="{{ route('teams.show', $team) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Team
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h5 class="m-0 fw-bold"><i class="fa-solid fa-people-group text-primary me-2"></i>{{ $team->name }}</h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('teams.update', $team) }}" method="POST">
            @method('PUT')
            @include('teams._form')
        </form>
    </div>
</div>
@endsection
