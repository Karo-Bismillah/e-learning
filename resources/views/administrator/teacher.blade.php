@extends('layouts.app')

{{-- start styles --}}
@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.bootstrap4.min.css">
<style>
    .dataTables_wrapper {
        font-size: 14px
    }
</style>
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
        <h3 class="page-title">Data Teacher</h3>
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
                <h6 class="m-0">Data Teachers</h6>
            </div>
            <div class="col-md-6">
                <button onclick="resetForm()" class="btn btn-primary float-right" data-toggle="modal" data-target="#formTeacherModal">
                    <i class="material-icons mr-1">person_add</i> Create New Teacher</button>
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
                        <th>Phone</th>
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
            ajax: "{{ route('jsonTeacher') }}",
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
                    data: 'phone',
                    name: 'phone',
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

        $('#formTeacher').on('submit', (function(event) {
            event.preventDefault();
            /* Store */
            if ($('#action').val() == "create") {
                $.ajax({
                    url: "{{ route('createTeacher') }}",
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
                        $('#formTeacherModal').modal('hide');
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
                    url: "{{ route('updateTeacher') }}",
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
                url: "{{url('/admin/teacher/edit')}}" + "/" + id,
                type: "GET",
                dataType: 'json',
                success: function(data) {
                    console.log('Success:', data);
                    resetForm();
                    $('#formTeacherModal').modal('show');
                    $('#action').val('update');
                    $('#actionLabel').text('Update');
                    $('#id').val(data.id);
                    $('#name').val(data.name);
                    $('#gender').val(data.gender);
                    $('#dob').val(data.dob);
                    $('#phone').val(data.phone);
                    $('#address').text(data.address);
                    $('#email').val(data.email);
                    $('#password').val('no changes');
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
                    url: "{{url('/admin/teacher/delete')}}" + "/" + id,
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

            /* end document ready */
        });


        /* end document ready */
    });

    function resetForm() {
        document.getElementById("formTeacher").reset();
        $('#action').val('create');
        $('#actionLabel').text('Create');
    }
</script>

{{-- start modal --}}
<div class="modal fade" id="formTeacherModal" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="actionLabel"> Create New Teacher</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="#" method="post" id="formTeacher">
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
                                <label for="phone">Phone</label>
                                <input type="numeric" id="phone" name="phone" class="form-control col-md-8">
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
                                <label for="status">Status</label>
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
