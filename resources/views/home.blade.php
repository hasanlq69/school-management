@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Classes</h5>
                <h1 class="display-4">{{ $classroomsCount }}</h1>
                <a href="{{ route('classrooms.index') }}" class="btn btn-primary">Manage Classes</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Teachers</h5>
                <h1 class="display-4">{{ $teachersCount }}</h1>
                <a href="{{ route('teachers.index') }}" class="btn btn-primary">Manage Teachers</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Students</h5>
                <h1 class="display-4">{{ $studentsCount }}</h1>
                <a href="{{ route('students.index') }}" class="btn btn-primary">Manage Students</a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Classes Overview</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Class</th>
                            <th>Students</th>
                            <th>Teachers</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($classrooms as $classroom)
                        <tr>
                            <td>{{ $classroom->name }}</td>
                            <td>{{ $classroom->students_count }}</td>
                            <td>{{ $classroom->teachers_count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Activities</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach($recentActivities as $activity)
                    <li class="list-group-item">
                        {{ $activity }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
