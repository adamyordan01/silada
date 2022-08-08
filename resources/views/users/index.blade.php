@extends('layouts.base', ["title" => "Daftar Pengguna"])

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
    {{-- add user --}}
    <div class="modal fade user-modal" tabindex="-1" role="dialog" id="user-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Tambah Pengguna</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('user.store') }}" method="POST" id="form-add-user">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama Pengguna</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <span class="text-danger mt-1 error-text name_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" class="form-control">
                            <span class="text-danger mt-1 error-text username_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="text" name="email" id="email" class="form-control">
                            <span class="text-danger mt-1 error-text email_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="position">Jabatan</label>
                            <select name="position" id="position" id="position" class="form-control">
                                {{-- <option value="">Pilih Jabatan</option> --}}
                                @foreach ($positions as $position)
                                    <option value="{{ $position->id }}">{{ $position->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <label for="">Peran</label>
                        </div>
                        @foreach ($roles as $role)
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="role" id="{{ $role->name }}" value="{{ $role->id }}">
                                <label class="form-check-label" for="{{ $role->name }}">{{ $role->name }}</label>
                            </div>
                        @endforeach
                        <span class="text-danger mt-1 error-text role_error"></span>
                        <div class="form-group mt-3">
                            <label for="" class="form-label">Password</label>
                            <div class="input-group mb-3">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="basic-addon2">
                                <span class="input-group-text" style="cursor: pointer !important;" id="basic-addon2" onclick="password_show_hide();">
                                    <i class="fas fa-eye" id="show_eye"></i>
                                    <i class="fas fa-eye-slash d-none" id="hide_eye"></i>
                                </span>
                            </div>
                            <span class="text-danger error-text password_error"></span>
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
@endsection

@section('section-header')
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item">Daftar Pengguna</div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-add py-2 mb-3" id="create-user" data-toggle="modal" data-target="#user-modal">Tambah Pengguna</button>
        </div>
        @if (session('success'))
            <div class="col-md-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Berhasil!</strong> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        @endif
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
                                    <th>Nama</th>
                                    <th>E-Mail</th>
                                    <th>Username</th>
                                    <th>Jabatan</th>
                                    <th>Peran</th>
                                    <th>Status</th>
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
    <script src="{{ asset('assets/js/select2/select2.min.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf_token"]').attr('content')
            }
        });

        
        $(document).ready(function () {
            $('#position').each(function () {
                $(this).select2({
                    dropdownParent: $(this).parent(),// fix select2 search input focus bug
                })
            })

            // fix select2 bootstrap modal scroll bug
            $(document).on('select2:close', '#position', function (e) {
                var evt = "scroll.select2"
                $(e.target).parents().off(evt)
                $(window).off(evt)
            })

            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('user.get-users') }}',
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'username', name: 'username' },
                    { data: 'position', name: 'position' },
                    { data: 'role', name: 'role' },
                    { data: 'status', name: 'status' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'updated_at', name: 'updated_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                columnDefs: [
                    { orderable: false, targets: 0 }
                ],
                order: []
            });

            // tambah data
            $('#form-add-user').on('submit', function (e) {
                e.preventDefault();
                var form = $(this);
                var formData = new FormData(this);
                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    method: form.attr('method'),
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function () {
                        $('#btn-save').text('Tambah Data...');
                        $('#btn-save').attr('disabled', true);
                        $(form).find('span.error-text').text('');
                    },
                    success: function (response) {
                        if (response.code == 0) {
                            $.each(response.error, function(prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val[0]);
                            })
                            $('#btn-save').text('Tambah Data');
                            $('#btn-save').attr('disabled', false);
                        } else if (response.code == 1) {
                            $('#user-modal').modal('hide');
                            $('#btn-save').text('Tambah Data');
                            $('#btn-save').attr('disabled', false);
                            $('#form-add-user')[0].reset();
                            $('#dataTable').DataTable().ajax.reload();
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            });
                        }
                    }
                });
            });

            // edit data
            // $(document).on('click', '#editButton', function () {
            //     let user_id = $(this).data('id');
            //     $('#edit-user-modal').find('form')[0].reset();
            //     $('#edit-user-modal').find('span.error-text').text('');

            //     $.ajax({
            //         url: "{{ route('user.get-user-detail') }}",
            //         type: 'POST',
            //         data: {
            //             user_id: user_id
            //         },
            //         dataType: 'json',
            //         success: function (response) {
            //             let admin = $('#edit-user-modal').find('#admin').val();

            //             console.log(admin, response);
            //             $('#edit-user-modal').modal('show');
            //             $('#edit-user-modal').find('input[name="user_id"]').val(response.detail.id);
            //             $('#edit-user-modal').find('#name').val(response.detail.name);
            //             $('#edit-user-modal').find('#username').val(response.detail.username);
            //             $('#edit-user-modal').find('#email').val(response.detail.email);
            //             if (response.detail.role_id == admin) {
            //                 $('#admin').prop('checked', true);
            //             } else if (response.detail.role_id == 2) {
            //                 $('#petugas-scan').prop('checked', true);
            //             } else if (response.detail.role_id == 3) {
            //                 $('#petugas-loket').prop('checked', true);
            //             }
            //         }
            //     })
            // })



        });

        function password_show_hide() {
            var x = document.getElementById("password");
            var show_eye = document.getElementById("show_eye");
            var hide_eye = document.getElementById("hide_eye");
            hide_eye.classList.remove("d-none");
            if (x.type === "password") {
                x.type = "text";
                show_eye.style.display = "none";
                hide_eye.style.display = "block";
            } else {
                x.type = "password";
                show_eye.style.display = "block";
                hide_eye.style.display = "none";
            }
        }
    </script>
@endpush