@extends('layouts.app')

{{-- start styles --}}
@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.bootstrap4.min.css">
<style>
    .dataTables_wrapper {
        font-size: 14px
    }

    .select2-selection__rendered {
        font-size: 12px;
        font-weight:normal;
    }
    .select2-results__option {
        font-size: 12px;
        font-weight:normal;
    }
</style>
<!-- select2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

<!-- select2-bootstrap4-theme -->
<link href="https://raw.githack.com/ttskch/select2-bootstrap4-theme/master/dist/select2-bootstrap4.css" rel="stylesheet"> <!-- for live demo page -->
@endpush
{{-- end styles --}}

{{-- start navbar --}}
@section('navbar')
@include('administrator.layouts.navbar')
@endsection
{{-- end navbar --}}

{{-- start sidebar --}}
@section('sidebar')
@include('administrator.layouts.sidebar')
@endsection
{{-- end sidebar --}}

{{-- start page_header --}}
@section('page_header')
<div class="page-header row no-gutters py-4">
    <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
        <span class="text-uppercase page-subtitle">Overview</span>
        <h3 class="page-title">Data Student</h3>
    </div>
</div>
@endsection
{{-- end page_header --}}

{{-- start content --}}
@section('content')

<div class="card card-small mb-4">
    <div class="card-header border-bottom">
        <div class="row">
            <div class="col-md-6">
                <h6 class="m-0">Data Students</h6>
            </div>
            <div class="col-md-6">
                <button onclick="resetForm()" class="btn btn-primary float-right" data-toggle="modal" data-target="#formStudentModal">
                    <i class="material-icons mr-1">person_add</i> Create New Student</button>
            </div>
        </div>
    </div>
    <ul class="list-group list-group-flush">
        <div class="card-body p-3 pb-3">
            <table id="dataTable" class="table table-sm mb-0 dt-responsive nowrap" style="width:100%;font-size=12px">
                <thead class="bg-light">
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Address</th>
                        <th>Class</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </ul>
</div>

@endsection
{{-- end content --}}

{{-- start scripts --}}
@push('scripts')
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.5/js/responsive.bootstrap4.min.js"></script>
<!-- select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        /* Datatables Yajra */
        var table = $('#dataTable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            searching: true,
            ordering: true,
            ajax: "{{ route('jsonStudent') }}",
            lengthMenu: [
                [5, 10, 25],
                [5, 10, 25]
            ],
            columnDefs: [{
                "width": "5%",
                "targets": 0
            }, ],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name',
                },
                {
                    data: 'gender',
                    name: 'gender',
                },
                {
                    data: 'address',
                    name: 'address',
                },
                {
                    data: 'classroom',
                    name: 'classroom',
                },
                {
                    data: 'email',
                    name: 'email',
                },
                {
                    data: 'status',
                    name: 'status',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $('#formStudent').on('submit', (function(event) {
            event.preventDefault();
            /* Store */
            if ($('#action').val() == "create") {
                $.ajax({
                    url: "{{ route('createStudent') }}",
                    type: "POST",
                    data: new FormData(this), // for upload image
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: 'json',
                    success: function(data) {
                        console.log('Success:', data);
                        table.draw();
                        alert('Saved Successfully');
                        $('#formStudentModal').modal('hide');
                        resetForm()
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        console.log(errors);
                        errorsHtml = '<div class="text-danger"><ul>';
                        $.each(errors.errors, function(k, v) {
                            errorsHtml += '<li>' + v + '</li>';
                        });
                        errorsHtml += '</ul></di>';
                        $('#message').html(errorsHtml);
                    }
                });
            }
            /* Update */
            if ($('#action').val() == "update") {
                $.ajax({
                    url: "{{ route('updateStudent') }}",
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: 'json',
                    success: function(data) {
                        console.log('Success:', data);
                        table.draw();
                        resetForm();
                        $('#formStudentModal').modal('hide');
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        console.log(errors);
                        errorsHtml = '<div class="text-danger"><ul>';
                        $.each(errors.errors, function(k, v) {
                            errorsHtml += '<li>' + v + '</li>';
                        });
                        errorsHtml += '</ul></di>';
                        $('#message').html(errorsHtml);
                    }
                });
            }
        }));

        /* Edit */

        $(document).on('click', '#editBtn', function() {
            var id = $(this).data('id');
            $.ajax({
                url: "{{url('/admin/student/edit')}}" + "/" + id,
                type: "GET",
                dataType: 'json',
                success: function(data) {
                    console.log('Success:', data);
                    resetForm();
                    $('#formStudentModal').modal('show');
                    $('#action').val('update');
                    $('#actionLabel').text('Update');
                    $('#id').val(data.id);
                    $('#name').val(data.name);
                    $('#gender').val(data.gender);
                    $('#dob').val(data.dob);
                    $('#address').text(data.address);
                    $('#email').val(data.email);
                    $('#password').val('no changes');
                    $('#password-confirm').val('no changes');
                    $('#classroom_id').val(data.classroom_id);
                    $('#select2-classroom_id-container').text(data.classroom_name);
                    $('#status').val(data.status);
                    $('#action').val('update');
                    $('#action').text('Update');
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    console.log(errors);
                    errorsHtml = '<div class="text-danger"><ul>';
                    $.each(errors.errors, function(k, v) {
                        errorsHtml += '<li>' + v + '</li>';
                    });
                    errorsHtml += '</ul></di>';
                    $('#message').html(errorsHtml);
                }
            });
        });

        /* Delete */
        $(document).on('click', '#deleteBtn', function() {
            var result = confirm("Are you sure want to delete?");
            var id = $(this).data('id');
            if (result) {
                $.ajax({
                    url: "{{url('/admin/student/delete')}}" + "/" + id,
                    type: "GET",
                    dataType: 'json',
                    success: function(data) {
                        console.log('Success:', data);
                        table.draw();
                        alert('Delete Successfully');
                        resetForm()
                    },
                    error: function(data) {
                        console.log(data);
                        var errors = data.responseJSON;
                        console.log(errors);
                        errorsHtml = '<div class="text-danger"><ul>';
                        $.each(errors.errors, function(k, v) {
                            errorsHtml += '<li>' + v + '</li>';
                        });
                        errorsHtml += '</ul></di>';
                        $('#message').html(errorsHtml);
                    }
                });
            }
        });

        /* get Classroom */
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: "{{route('listClassroom')}}",
            success: function(data) {
                $('#classroom_id').empty();
                $('#classroom_id').append('<option value="">Not Selected</option>');
                for (var i = 0; i < data.length; i++) {

                    $('#classroom_id').append('<option value="' + data[i]['id'] + '">' + data[i]['name'] + '</option>');
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus);
            }
        });

        $('#classroom_id').select2({
            theme: 'bootstrap4',
        });
        /* end document ready */
    });

    function resetForm() {
        $('#message').html("")
        document.getElementById("formStudent").reset();
        $('#action').val('create');
        $('#actionLabel').text('Create');
    }
</script>

{{-- start modal --}}
<div class="modal fade" id="formStudentModal" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="actionLabel"> Create New Student</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="#" method="post" id="formStudent">
                <div class="modal-body">
                    <div id="message"></div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="feFirstName">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                            <div class="form-group">
                                <label for="feFirstName">Gender</label>
                                <select name="gender" id="gender" class="form-control col-md-6">
                                    <option value="">Not Selected</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="dob">Date of Birth</label>
                                <input type="date" name="dob" id="dob" class="form-control col-md-6">
                            </div>
                            <div class="form-group">
                                <label for="addrress">Address</label>
                                <textarea name="address" id="address" cols="30" rows="2" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password">Confirm Password</label>
                                <input type="password" id="password-confirm" name="password_confirmation" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="feFirstName">Class</label>
                                <select name="classroom_id" id="classroom_id" class="form-control col-md-6">
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="feFirstName">Class</label>
                                <select name="status" id="status" class="form-control col-md-6">
                                    <option value="">Not Selected</option>
                                    <option value="0">InActive</option>
                                    <option value="1">Active</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="id" id="id" value="">
                </div>
                <div class="modal-footer">
                    <button id="action" value="create" type="submit" class="btn btn-secondary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- end modal --}}
@endpush
{{-- end scripts --}}
