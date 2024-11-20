@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Create New Classroom</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('classrooms.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Classroom Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                    id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Create Classroom</button>
                <a href="{{ route('classrooms.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
