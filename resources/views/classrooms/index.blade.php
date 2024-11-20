@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Manage Classrooms</h5>
        <button type="button" class="btn btn-primary" id="createBtn">
            Add Classroom
        </button>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Students Count</th>
                    <th>Teachers Count</th>
                    <th width="200">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($classrooms as $classroom)
                <tr id="classroom-{{ $classroom->id }}">
                    <td>{{ $classroom->name }}</td>
                    <td>{{ $classroom->students->count() }}</td>
                    <td>{{ $classroom->teachers->count() }}</td>
                    <td>
                        <button class="btn btn-sm btn-info viewBtn" data-id="{{ $classroom->id }}">
                            View
                        </button>
                        <button class="btn btn-sm btn-primary editBtn" data-id="{{ $classroom->id }}">
                            Edit
                        </button>
                        <button class="btn btn-sm btn-danger deleteBtn" data-id="{{ $classroom->id }}">
                            Delete
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Create/Edit Modal -->
<div class="modal fade" id="classroomModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="classroomForm">
                    <input type="hidden" id="classroom_id">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveBtn">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Classroom Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Students</h6>
                        <ul id="studentsList" class="list-group">
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Teachers</h6>
                        <ul id="teachersList" class="list-group">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Create button click
    $('#createBtn').click(function() {
        $('#classroom_id').val('');
        $('#classroomForm')[0].reset();
        $('.modal-title').text('Create Classroom');
        $('#classroomModal').modal('show');
    });

    // Save button click
    $('#saveBtn').click(function() {
        const id = $('#classroom_id').val();
        const formData = {
            name: $('#name').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        $.ajax({
            url: id ? `/classrooms/${id}` : '/classrooms',
            method: id ? 'PUT' : 'POST',
            data: formData,
            success: function(response) {
                $('#classroomModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                const errors = xhr.responseJSON.errors;
                for(const field in errors) {
                    $(`#${field}`).addClass('is-invalid');
                    $(`#${field}`).siblings('.invalid-feedback').text(errors[field][0]);
                }
            }
        });
    });

    // Edit button click
    $('.editBtn').click(function() {
        const id = $(this).data('id');
        $.get(`/classrooms/${id}`, function(classroom) {
            $('#classroom_id').val(classroom.id);
            $('#name').val(classroom.name);
            $('.modal-title').text('Edit Classroom');
            $('#classroomModal').modal('show');
        });
    });

    // View button click
    $('.viewBtn').click(function() {
        const id = $(this).data('id');
        $.get(`/classrooms/${id}`, function(classroom) {
            $('#studentsList').empty();
            $('#teachersList').empty();

            classroom.students.forEach(student => {
                $('#studentsList').append(`
                    <li class="list-group-item">
                        ${student.name} <br>
                        <small class="text-muted">${student.email}</small>
                    </li>
                `);
            });

            classroom.teachers.forEach(teacher => {
                $('#teachersList').append(`
                    <li class="list-group-item">
                        ${teacher.name} <br>
                        <small class="text-muted">${teacher.email}</small>
                    </li>
                `);
            });

            $('#viewModal').modal('show');
        });
    });

    // Delete button click
    $('.deleteBtn').click(function() {
        const id = $(this).data('id');
        if(confirm('Are you sure you want to delete this classroom?')) {
            $.ajax({
                url: `/classrooms/${id}`,
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    $(`#classroom-${id}`).remove();
                }
            });
        }
    });

    // Clear validation errors when modal is hidden
    $('#classroomModal').on('hidden.bs.modal', function () {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
    });
});
</script>
@endpush
