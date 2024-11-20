@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Teacher Details</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th width="200">Name</th>
                    <td>{{ $teacher->name }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $teacher->email }}</td>
                </tr>
                <tr>
                    <th>Classroom</th>
                    <td>{{ $teacher->classroom->name }}</td>
                </tr>
            </table>
        </div>
        <div class="mt-3">
            <a href="{{ route('teachers.index') }}" class="btn btn-secondary">Back to List</a>
            <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn btn-primary">Edit Teacher</a>
        </div>
    </div>
</div>
@endsection
