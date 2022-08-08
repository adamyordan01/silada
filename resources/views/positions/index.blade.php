@extends('layouts.base', ['title' => 'Daftar Jabatan'])

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/datatable/datatables.min.css') }}">

    <style>
        table {
            width: 100% !important;
        }

        .btn-add {
            box-shadow: none;
            background-color: #02dda5 !important;
            color: #fff !important;
        }

        .btn-add:hover {
            background-color: #019a73 !important;
            color: #fff !important;
        }

        .btn-edit {
            box-shadow: none;
            background-color: #02dda5 !important;
            color: #fff !important;
            /* outline-color: #02dda5 !important; */
        }
        .btn-edit:hover {
            background-color: #019a73 !important;
            color: #fff !important;
        }
    </style>
@endpush

@section('modal')
    {{-- Modal add position --}}
    <div class="modal fade positionModal" tabindex="-1" role="dialog" id="addPositionModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jabatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('position.store') }}" id="addPosition" method="post">
                @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama Jabatan</label>
                            <input type="text" name="name" id="name" class="form-control" autofocus>
                            <span class="text-danger error-text name_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit position --}}
    <div class="modal fade editPositionModal" tabindex="-1" role="dialog" id="editPositionModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Jabatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('position.update') }}" id="editPosition" method="post">
                    @csrf
                    @method('patch')
                    <div class="modal-body">
                        <input type="hidden" class="d-none" name="position_id">
                        <div class="form-group">
                            <label for="name">Nama Jabatan</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <span class="text-danger error-text name_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('section-header')
    <h1>Daftar Jabatan</h1>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <a href="javascript:void(0)" class="btn btn-add py-2 mb-3" data-toggle="modal" data-target="#addPositionModal">
                <i class="fa fa-plus"></i>
                Tambah Jabatan Baru
            </a>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="data-table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Jabatan</th>
                                    <th>Dibuat Pada</th>
                                    <th>Diedit Pada</th>
                                    <th>Action</th>
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
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf_token"]').attr('content')
            }
        });

        $("#addPositionModal").on('shown.bs.modal', function(){
            $(this).find('#name').focus();
        });

        $(document).ready(function(){
            $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('position.get-position') }}",
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
        });

        $(function() {
            // tambah data 
            $('#addPosition').on('submit', function (e) {
                e.preventDefault();
                var form = this;

                $.ajax({
                    url: $(form).attr('action'),
                    type: $(form).attr('method'),
                    data: new FormData(form),
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    beforeSend: function () {
                        $(form).find('span.error-text').text('');
                    },
                    success: function (response) {
                        if (response.code == 0) {
                            $.each(response.error, function(prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val[0]);
                            });
                        } else if (response.code == 1) {
                            $('#addPositionModal').modal('hide');
                            $('#data-table').DataTable().ajax.reload();
                            $('#addPositionModal').find('form')[0].reset();
                            Toast.fire({
                                icon: 'success',
                                title: response.message
                            });
                        }
                    }
                })
            })

            // edit data
            $(document).on('click', '#editButton', function () {
                let position_id = $(this).data('id');
                $(".editPositionModal").find('form')[0].reset();
                $(".editPositionModal").find('span.error-text').text('');
                $.post('<?= route('position.get-position-detail') ?>', {position_id: position_id}, function (data) {
                    $('.editPositionModal').find('input[name="position_id"]').val(data.details.id);
                    $('.editPositionModal').find('input[name="name"]').val(data.details.name);
                    $('.editPositionModal').modal('show');
                }, 'json');
            });

            // update data
            $('#editPosition').on('submit', function (e) {
                e.preventDefault();
                var form = this;
                $.ajax({
                    url: $(form).attr('action'),
                    method: $(form).attr('method'),
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    beforeSend: function () {
                        $(form).find('span.error-text').text('');
                    },
                    success: function (response) {
                        if (response.code == 0) {
                            $.each(response.error, function(prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val[0]);
                            });
                        } else if (response.code == 1) {
                            $('#editPositionModal').modal('hide');
                            $('#data-table').DataTable().ajax.reload();
                            $('#editPositionModal').find('form')[0].reset();
                            Toast.fire({
                                icon: 'success',
                                title: response.message
                            });
                        }
                    }
                });
            })

            // delete data
            $(document).on('click', '#deleteButton', function () {
                let position_id = $(this).data('id');
                let url = '<?= route('position.destroy') ?>';

                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Anda akan menghapus data jabatan ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.value) {
                        $.post(url, {
                            position_id: position_id
                        }, function (response) {
                            if (response.code == 1) {
                                $('#data-table').DataTable().ajax.reload(null, false);
                                Toast.fire({
                                    icon: 'success',
                                    title: response.message
                                });
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: response.message
                                });
                            }
                        }, 'json');
                    }
                })
            })
        })
    </script>
@endpush