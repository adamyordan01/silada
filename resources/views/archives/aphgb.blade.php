@extends('layouts.base', ["title" => "Daftar Arsip Akta Hak Guna Bangunan Bersama"])

@php
    $role = auth()->user()->role->name;
@endphp

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/datatable/datatables.min.css') }}">
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
    </style>
@endpush

@section('section-header')
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item">Daftar Arsip Akta Hak Guna Bangunan Bersama</div>
    </div>
@endsection

@section('content')
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
                ajax: "{{ route('archive.get-aphgb') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'archive_number', name: 'archive_number'},
                    {data: 'title', name: 'title'},
                    {data: 'document_type', name: 'document_type'},
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
        });

    </script>
@endpush