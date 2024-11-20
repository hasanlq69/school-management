@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Manage Teachers</h5>
        <button type="button" class="btn btn-primary" id="createBtn">
            Add Teacher
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
                @foreach($teachers as $teacher)
                <tr id="teacher-{{ $teacher->id }}">
                    <td>{{ $teacher->name }}</td>
                    <td>{{ $teacher->email }}</td>
                    <td>{{ $teacher->classroom->name }}</td>
                    <td>
                        <button class="btn btn-sm btn-info viewBtn" data-id="{{ $teacher->id }}">
                            View
                        </button>
                        <button class="btn btn-sm btn-primary editBtn" data-id="{{ $teacher->id }}">
                            Edit
                        </button>
                        <button class="btn btn-sm btn-danger deleteBtn" data-id="{{ $teacher->id }}">
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
<div class="modal fade" id="teacherModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="teacherForm">
                    <input type="hidden" id="teacher_id">
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
                <h5 class="modal-title">Teacher Details</h5>
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
        $('#teacher_id').val('');
        $('#teacherForm')[0].reset();
        $('.modal-title').text('Create Teacher');
        $('#teacherModal').modal('show');
    });

    // Save button click
    $('#saveBtn').click(function() {
        const id = $('#teacher_id').val();
        const formData = {
            name: $('#name').val(),
            email: $('#email').val(),
            classroom_id: $('#classroom_id').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        $.ajax({
            url: id ? `/teachers/${id}` : '/teachers',
            method: id ? 'PUT' : 'POST',
            data: formData,
            success: function(response) {
                $('#teacherModal').modal('hide');
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
        $.get(`/teachers/${id}`, function(teacher) {
            $('#teacher_id').val(teacher.id);
            $('#name').val(teacher.name);
            $('#email').val(teacher.email);
            $('#classroom_id').val(teacher.classroom_id);
            $('.modal-title').text('Edit Teacher');
            $('#teacherModal').modal('show');
        });
    });

    // View button click
    $('.viewBtn').click(function() {
        const id = $(this).data('id');
        $.get(`/teachers/${id}`, function(teacher) {
            $('#view-name').text(teacher.name);
            $('#view-email').text(teacher.email);
            $('#view-classroom').text(teacher.classroom.name);
            $('#viewModal').modal('show');
        });
    });

    // Delete button click
    $('.deleteBtn').click(function() {
        const id = $(this).data('id');
        if(confirm('Are you sure you want to delete this teacher?')) {
            $.ajax({
                url: `/teachers/${id}`,
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    $(`#teacher-${id}`).remove();
                }
            });
        }
    });

    // Clear validation errors when modal is hidden
    $('#teacherModal').on('hidden.bs.modal', function () {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
    });
});
</script>
@endpush
