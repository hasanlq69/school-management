@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Student Details</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th width="200">Name</th>
                    <td>{{ $student->name }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $student->email }}</td>
                </tr>
                <tr>
                    <th>Classroom</th>
                    <td>{{ $student->classroom->name }}</td>
                </tr>
            </table>
        </div>
        <div class="mt-3">
            <a href="{{ route('students.index') }}" class="btn btn-secondary">Back to List</a>
            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary">Edit Student</a>
        </div>
    </div>
</div>
@endsection
