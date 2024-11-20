@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Manage Students</h5>
        <button type="button" class="btn btn-primary" id="createBtn">
            Add Student
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
                    <th>Email</th>
                    <th>Classroom</th>
                    <th width="200">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr id="student-{{ $student->id }}">
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->classroom->name }}</td>
                    <td>
                        <button class="btn btn-sm btn-info viewBtn" data-id="{{ $student->id }}">
                            View
                        </button>
                        <button class="btn btn-sm btn-primary editBtn" data-id="{{ $student->id }}">
                            Edit
                        </button>
                        <button class="btn btn-sm btn-danger deleteBtn" data-id="{{ $student->id }}">
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
<div class="modal fade" id="studentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="studentForm">
                    <input type="hidden" id="student_id">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="classroom_id" class="form-label">Classroom</label>
                        <select class="form-control" id="classroom_id" name="classroom_id" required>
                            <option value="">Select Classroom</option>
                            @foreach($classrooms as $classroom)
                                <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                            @endforeach
                        </select>
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Student Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="100">Name</th>
                        <td id="view-name"></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td id="view-email"></td>
                    </tr>
                    <tr>
                        <th>Classroom</th>
                        <td id="view-classroom"></td>
                    </tr>
                </table>
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
        $('#student_id').val('');
        $('#studentForm')[0].reset();
        $('.modal-title').text('Create Student');
        $('#studentModal').modal('show');
    });

    // Save button click
    $('#saveBtn').click(function() {
        const id = $('#student_id').val();
        const formData = {
            name: $('#name').val(),
            email: $('#email').val(),
            classroom_id: $('#classroom_id').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        $.ajax({
            url: id ? `/students/${id}` : '/students',
            method: id ? 'PUT' : 'POST',
            data: formData,
            success: function(response) {
                $('#studentModal').modal('hide');
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
        $.get(`/students/${id}`, function(student) {
            $('#student_id').val(student.id);
            $('#name').val(student.name);
            $('#email').val(student.email);
            $('#classroom_id').val(student.classroom_id);
            $('.modal-title').text('Edit Student');
            $('#studentModal').modal('show');
        });
    });

    // View button click
    $('.viewBtn').click(function() {
        const id = $(this).data('id');
        $.get(`/students/${id}`, function(student) {
            $('#view-name').text(student.name);
            $('#view-email').text(student.email);
            $('#view-classroom').text(student.classroom.name);
            $('#viewModal').modal('show');
        });
    });

    // Delete button click
    $('.deleteBtn').click(function() {
        const id = $(this).data('id');
        if(confirm('Are you sure you want to delete this student?')) {
            $.ajax({
                url: `/students/${id}`,
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    $(`#student-${id}`).remove();
                }
            });
        }
    });

    // Clear validation errors when modal is hidden
    $('#studentModal').on('hidden.bs.modal', function () {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
    });
});
</script>
@endpush
