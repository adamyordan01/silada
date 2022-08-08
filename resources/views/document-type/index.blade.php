@extends('layouts.base', ["title" => "Daftar Jenis Dokumen"])

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
    <div class="modal fade document-type-modal" tabindex="-1" role="dialog" id="document-type-modal" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document-type">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Tambah Dokumen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('document-type.store') }}" method="POST" id="form-add-document-type">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Jenis Dokumen</label>
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
    <div class="modal fade edit-document-type-modal" tabindex="-1" role="dialog" id="edit-document-type-modal" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Edit Jenis Dokumen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('document-type.update') }}" method="POST" id="form-edit-document-type">
                    @csrf
                    @method('patch')
                    <div class="modal-body">
                        <input type="hidden" class="d-none" name="document_type_id">
                        <div class="form-group">
                            <label for="name">Jenis Dokumen</label>
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
        <div class="breadcrumb-item">Daftar Jenis Dokumen</div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-add py-2 mb-3" id="create-document-type" data-toggle="modal" data-target="#document-type-modal">Tambah Jenis Dokumen</button>
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
                                    <th>Jenis Dokumen</th>
                                    <th>Ditambahkan pada</th>
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

        $("#document-type-modal").on('shown.bs.modal', function(){
            $(this).find('#name').focus();
        });

        $(document).ready(function () {
            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('document-type.get-document-type') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'name', name: 'name' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                columnDefs: [
                    { orderable: false, targets: 0 }
                ],
                order: []
            });

            // tambah data
            $('#form-add-document-type').on('submit', function (e) {
                e.preventDefault();
                var form = $(this);
                var formData = new FormData(this);
                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
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
                            $('#document-type-modal').modal('hide');
                            $('#btn-save').text('Tambah Data');
                            $('#btn-save').attr('disabled', false);
                            $('#form-add-document-type')[0].reset();
                            $('#dataTable').DataTable().ajax.reload();
                            Swal.fire({
                                title: 'Berhasil',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            });
                        }
                    }
                })
            });

            // edit data
            $(document).on('click', '#editButton', function () {
                let document_type_id = $(this).data('id');
                $('#edit-document-type-modal').find('form')[0].reset();
                $('#edit-document-type-modal').find('span.error-text').text('');
                $.ajax({
                    url: "{{ route('document-type.get-document-type-detail') }}",
                    type: 'POST',
                    data: {
                        document_type_id: document_type_id
                    },
                    dataType: 'json',
                    success: function (response) {
                        console.log(response);
                        $('#edit-document-type-modal').find('input.d-none').val(response.detail.id);
                        $('#edit-document-type-modal').find('input#name').val(response.detail.name);
                        $('#edit-document-type-modal').modal('show');
                    }
                })
            });

            // update data
            $('#form-edit-document-type').on('submit', function (e) {
                e.preventDefault();
                var form = $(this);
                var formData = new FormData(this);
                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
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
                            $('#edit-document-type-modal').modal('hide');
                            $('#btn-update').text('Ubah Data');
                            $('#btn-update').attr('disabled', false);
                            $('#form-edit-document-type')[0].reset();
                            $('#dataTable').DataTable().ajax.reload();
                            Swal.fire({
                                title: 'Berhasil',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            });
                        }
                    }
                })
            });
        })

    </script>
@endpush