@extends('layouts.base', ["title" => "Daftar Role"])

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/datatable/datatables.min.css') }}">
    <style>
        /* table, th, td {
            border: 1px solid #ced3d9 !important;
            border-collapse: collapse !important;
        } */
        .btn-add {
            box-shadow: none;
            background-color: #02dda5 !important;
            color: #fff !important;
        }

        .btn-add:hover {
            background-color: #019a73 !important;
            color: #fff !important;
        }

        th.first {
            min-width: 95px !important;
        }

        table {
            width: 100% !important;
        }
    </style>
@endpush

@section('modal')
    {{-- add role --}}
    <div class="modal fade roleModal" tabindex="-1" role="dialog" id="roleModal" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Tambah Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('role.store') }}" method="POST" id="formAddRole">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama Role</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <span class="text-danger mt-1 error-text name_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" id="btn-save" value="create" class="btn btn-primary">Tambah Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- edit product --}}
    <div class="modal fade editRoleModal" tabindex="-1" role="dialog" id="editRoleModal" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Edit Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('role.update') }}" method="POST" id="editRole">
                    @csrf
                    @method('patch')
                    <div class="modal-body">
                        <input type="hidden" class="d-none" name="role_id">
                        <div class="form-group">
                            <label for="name">Nama Role</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <span class="text-danger mt-1 error-text name_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" id="btn-update" value="update" class="btn btn-primary">Ubah Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('section-header')
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item">Daftar Role</div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-add py-2 mb-3" id="create-role" data-toggle="modal" data-target="#roleModal">Tambah Role</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="dataTable">
                            <thead>
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th>Nama Role</th>
                                    <th>Dibuat pada</th>
                                    <th>Diupdate pada</th>
                                    <th style="width: 10%">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/js/datatable/datatables.min.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf_token"]').attr('content')
            }
        });

        $(document).ready(function () {
            $("#dataTable").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('role.get-role') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'updated_at', name: 'updated_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                columnDefs: [
                    { orderable: false, targets: 0 }
                ],
                order: []
            });

            // tambah Data
            $('#formAddRole').on('submit', function (e) {
                e.preventDefault();
                var form = $(this);
                var formData = new FormData(this);
                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    method: form.attr('method'),
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    cache: false, 
                    processData: false,
                    beforeSend: function () {
                        $('#btn-save').text('Saving...');
                        $('#btn-save').attr('disabled', true);
                        $(form).find('span.error-text').text('');
                    },
                    success: function (response) {
                        if (response.code == 0) {
                            $.each(response.error, function(prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                            });
                            $('#btn-save').text('Tambah Data');
                            $('#btn-save').attr('disabled', false);
                        } else if (response.code == 1) {
                            $('#roleModal').modal('hide');
                            $('#btn-save').text('Tambah Data');
                            $('#btn-save').attr('disabled', false);
                            $('#formAddRole')[0].reset();
                            $('#dataTable').DataTable().ajax.reload();
                            Swal.fire({
                                title: 'Success',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            });
                        }
                    }
                });
            });

            // edit Data
            $(document).on('click', '#editButton', function () {
                let role_id = $(this).data('id');
                $('#editRoleModal').find('form')[0].reset();
                $('#editRoleModal').find('span.error-text').text('');
                $.ajax({
                    url: "{{ route('role.get-role-detail') }}",
                    type: "POST",
                    data: {
                        role_id: role_id
                    },
                    dataType: "json",
                    success: function (response) {
                        $('#editRoleModal').find('input.d-none').val(response.role.id);
                        $('#editRoleModal').find('#name').val(response.role.name);
                        $('#editRoleModal').modal('show');
                    }
                });
            });

            // update data
            $('#editRole').on('submit', function (e) {
                e.preventDefault();
                var form = $(this);
                var formData = new FormData(this);
                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    method: form.attr('method'),
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    cache: false, 
                    processData: false,
                    beforeSend: function () {
                        $('#btn-update').text('Updating...');
                        $('#btn-update').attr('disabled', true);
                        $(form).find('span.error-text').text('');
                    },
                    success: function (response) {
                        if (response.code == 0) {
                            $.each(response.error, function(prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                            });
                            $('#btn-update').text('Ubah Data');
                            $('#btn-update').attr('disabled', false);
                        } else if (response.code == 1) {
                            $('#editRoleModal').modal('hide');
                            $('#btn-update').text('Ubah Data');
                            $('#btn-update').attr('disabled', false);
                            $('#dataTable').DataTable().ajax.reload();
                            Swal.fire({
                                title: 'Success',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            });
                        }
                    }
                });
            });

            // delete data
            $(document).on('click', '#deleteButton', function () {
                let role_id = $(this).data('id');
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: "{{ route('role.destroy') }}",
                            type: "POST",
                            data: {
                                role_id: role_id
                            },
                            dataType: "json",
                            success: function (response) {
                                $('#dataTable').DataTable().ajax.reload();
                                Swal.fire({
                                    title: 'Success',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'Ok'
                                });
                            }
                        });
                    }
                });
            });
        });        
    </script>
@endpush