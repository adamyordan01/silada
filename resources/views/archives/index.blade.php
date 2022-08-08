@extends('layouts.base', ["title" => "Daftar Arsip Berkas"])

@php
    $role = auth()->user()->role->name;
@endphp

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/datatable/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/select2/select2.min.css') }}">
    <style>
        .btn-add {
            box-shadow: none;
            background-color: #02dda5 !important;
            color: #fff !important;
        }

        .btn-add:hover {
            background-color: #019a73 !important;
            color: #fff !important;
        }

        table {
            width: 100% !important;
        }

        .select2-container {
            z-index: 1061 !important;
        }
        span.select2-container {
            z-index:10050;
        }

        /* 1.18 Select2 */
        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            outline: none;
            box-shadow: none;
        }

        .select2-container .select2-selection--multiple,
        .select2-container .select2-selection--single {
            box-sizing: border-box;
            cursor: pointer;
            display: block;
            min-height: 42px;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-user-select: none;
            outline: none;
            background-color: #fdfdff;
            border-color: #e4e6fc;
        }

        .select2-dropdown {
            border-color: #e4e6fc !important;
        }

        .select2-container.select2-container--open .select2-selection--multiple {
            background-color: #fefeff;
            border-color: #95a0f4;
        }

        .select2-container.select2-container--focus .select2-selection--multiple,
        .select2-container.select2-container--focus .select2-selection--single {
            background-color: #fefeff;
            border-color: #95a0f4;
        }

        .select2-container.select2-container--open .select2-selection--single {
            background-color: #fefeff;
            border-color: #95a0f4;
        }

        .select2-results__option {
            padding: 10px;
        }

        .select2-search--dropdown .select2-search__field {
            padding: 7px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            min-height: 42px;
            line-height: 42px;
            padding-left: 15px;
            padding-right: 20px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__arrow,
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            position: absolute;
            top: 1px;
            right: 1px;
            width: 40px;
            min-height: 42px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.03);
            color: #fff;
            padding-left: 10px;
            padding-right: 10px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            padding-left: 10px;
            padding-right: 10px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            margin-right: 5px;
            color: #fff;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice,
        .select2-container--default .select2-results__option[aria-selected=true],
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #6777ef;
            color: #fff;
        }

        .select2-results__option {
            padding-right: 10px 15px;
        }
    </style>
@endpush

@section('modal')
    {{-- add archive --}}
    <div class="modal fade add-archive-modal" role="dialog" id="add-archive-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Tambah Data Arsip</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('archive.store') }}" method="POST" id="form-add-archive" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="archive-date">Tanggal Surat</label>
                            <input type="text" class="form-control datepicker" id="archive-date" name="archive_date">
                            <span class="text-danger mt-1 error-text archive_date_error"></span>
                        </div>
                        {{-- <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="month">Bulan</label>
                                <select name="month" id="month" class="form-control">
                                    <option value="">Pilih Bulan</option>
                                    @foreach ($months as $month => $name)
                                        <option value="{{ $month }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger mt-1 error-text month_error"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="year">Tahun</label>
                                <input type="number" class="form-control" name="year">
                                <span class="text-danger mt-1 error-text year_error"></span>
                            </div>
                        </div> --}}
                        <div class="form-group">
                            <label for="archive_number">Nomor Berkas</label>
                            <input type="text" class="form-control" id="archive-number" name="archive_number">
                            <span class="text-danger mt-1 error-text archive_number_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="title">Nama Berkas</label>
                            <input type="text" name="title" id="title" class="form-control">
                            <span class="text-danger mt-1 error-text title_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="origin">Asal Surat</label>
                            <input type="text" name="origin" id="origin" class="form-control">
                            <span class="text-danger mt-1 error-text origin_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="sender">Pengirim Surat</label>
                            <input type="text" name="sender" id="sender" class="form-control">
                            <span class="text-danger mt-1 error-text sender_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="type">Jenis Berkas</label>
                            <select name="type" id="type" class="form-control">
                                <option value="">Pilih Jenis Dokumen</option>
                                @foreach ($documentTypes as $documentType)
                                    <option value="{{ $documentType->id }}">{{ $documentType->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger mt-1 error-text type_error"></span>
                        </div>
                        @if ($role == 'admin')
                            <div class="form-group mb-0">
                                <label for="">Pemilik Berkas Arsip</label>
                            </div>
                            @foreach ($positions as $position)
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="position" id="position-{{ $loop->iteration }}" value="{{ $position->id }}">
                                    <label class="form-check-label" for="position-{{ $loop->iteration }}">{{ $position->name }}</label>
                                </div>
                            @endforeach
                        @endif
                        <div class="form-group mt-3">
                            <label for="file">File</label>
                            <input type="file" name="file" id="file" class="form-control">
                            <span class="text-danger mt-1 error-text file_error"></span>
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

    <div class="modal fade share-document-modal" tabindex="-1" role="dialog" id="share-document-modal" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Bagikan Data Arsip</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('archive.share') }}" method="POST" id="form-share-document">
                    <input type="hidden" name="archive_id" id="archive_id" value="" class="d-none">
                    <div class="modal-body">
                        <div class="form-group mb-0">
                            <label for="">Bagikan Berkas Arsip</label>
                        </div>
                        @foreach ($positions as $position)
                            <div class="form-check ">
                                <input type="checkbox" class="form-check-input" name="position[]" id="{{ $position->name . $loop->iteration }}" value="{{ $position->id }}">
                                <label class="form-check-label" for="{{ $position->name . $loop->iteration }}">{{ $position->name }}</label>
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" id="btn-share" value="share" class="btn btn-primary">Bagikan Data</button>
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
        <div class="breadcrumb-item">Daftar Arsip Berkas</div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-add py-2 mb-3" id="create-archive" data-toggle="modal" data-target="#add-archive-modal">Tambah Arsip</button>
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
                                    <th>Nomor Berkas</th>
                                    <th>Nama Berkas</th>
                                    <th>Jenis Berkas</th>
                                    <th>Pemilik Berkas</th>
                                    <th>Pengirim</th>
                                    <th>Asal Surat</th>
                                    <th>Tanggal Arsip</th>
                                    <th>User Upload</th>
                                    <th>Dibuat pada</th>
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
    <script src="{{ asset('assets/js/select2/select2.min.js') }}"></script>
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
                ajax: "{{ route('archive.get-archives') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'archive_number', name: 'archive_number'},
                    {data: 'title', name: 'title'},
                    {data: 'document_type', name: 'document_type'},
                    {data: 'position', name: 'position'},
                    {data: 'sender', name: 'sender'},
                    {data: 'origin', name: 'origin'},
                    {data: 'archive_date', name: 'archive_date'},
                    {data: 'user_name', name: 'user_name'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                columnDefs: [
                    { orderable: false, targets: 0 }
                ],
                order: []
            });

            // $('#type').select2({
            //     dropdownParent: $('#add-archive-modal')
            // });

            $('#type').each(function () {
                $(this).select2({
                    dropdownParent: $(this).parent(),// fix select2 search input focus bug
                })
            })

            // fix select2 bootstrap modal scroll bug
            $(document).on('select2:close', '#type', function (e) {
                var evt = "scroll.select2"
                $(e.target).parents().off(evt)
                $(window).off(evt)
            })

            // tambah data
            $('#form-add-archive').on('submit', function (e) {
                e.preventDefault();
                var form = this;
                var formData = new FormData(this);
                $.ajax({
                    url: $(form).attr('action'),
                    type: $(form).attr('method'),
                    data: new FormData(this),
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
                            $.each(response.error, function (prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val[0]);
                            });
                            $('#btn-save').text('Tambah Data');
                            $('#btn-save').attr('disabled', false);
                        } else if (response.code == 1) {
                            $('#add-archive-modal').modal('hide');
                            $('#dataTable').DataTable().ajax.reload();
                            $('#btn-save').text('Tambah Data');
                            $('#btn-save').attr('disabled', false);
                            $('#form-add-archive')[0].reset();
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

            $(document).on('click', '#shareButton', function() {
                // $('#share-document-modal').modal('show');
                var archive_id = $(this).data('id');
                $('#share-document-modal').modal('show');
                $('#archive_id').val(archive_id);
            });

            $(document).on('submit', '#form-share-document', function(e) {
                e.preventDefault();
                var archive_id = $('#archive_id').val();
                var data = $('#form-share-document').serialize();
                $.ajax({
                    url: "{{ route('archive.share') }}",
                    type: 'POST',
                    data: data,
                    dataType: 'json',
                    beforeSend: function() {
                        $('#btn-share').text('Sending...');
                        $('#btn-share').attr('disabled', true);
                    },
                    success: function(response) {
                        console.log(data);
                        if (response.code == 0) {
                            $('#share-document-modal').modal('hide');
                            $('#dataTable').DataTable().ajax.reload();
                            $('#btn-share').text('Share');
                            $('#btn-share').attr('disabled', false);
                            Swal.fire({
                                title: 'Berhasil',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            });
                        } else if (response.code == 1) {
                            $('#share-document-modal').modal('hide');
                            $('#dataTable').DataTable().ajax.reload();
                            $('#btn-share').text('Share');
                            $('#btn-share').attr('disabled', false);
                            Swal.fire({
                                title: 'Gagal',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            });
                        }
                    }
                });
            });

        });

        function share(archive_id) {
            $.ajax({
                url: "{{ route('archive.create') }}",
                method: 'GET',
                dataType: 'html',
                data: {
                    archive_id: archive_id
                },
                success: function (response) {
                    $('#share-document-modal').html(response);
                }
            });
            $('#share-document-modal').modal('show');
        }

    </script>
@endpush