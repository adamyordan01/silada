@extends('layouts.base')

@section('section-header')
    <h1>Ubah Password</h1>
@endsection

@push('style')
    <style>
        .btn-update {
            box-shadow: none;
            background-color: #02dda5 !important;
            color: #fff !important;
            /* outline-color: #02dda5 !important; */
        }
        .btn-update:hover {
            background-color: #019a73 !important;
            color: #fff !important;
        }
    </style>
@endpush   

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            {{-- <a href="{{ route('user.create') }}" class="btn btn-primary mb-4">
                <i class="fa fa-plus"></i>
                Ubah Password
            </a> --}}
            <div class="card">
                <div class="card-body">
                    {{-- @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif --}}
                    <form action="{{ route('change-password.update') }}" method="post">
                        @csrf
                        @method('patch')

                        <div class="form-group">
                            <label for="current_password" class="form-label">Password saat ini</label>
                            <div class="input-group mb-3">
                                <input type="password" name="current_password" id="current_password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="basic-addon2">
                                <span class="input-group-text" style="cursor: pointer !important;" id="basic-addon2" onclick="password_current_password();">
                                    <i class="fas fa-eye" id="show_eye_current_password"></i>
                                    <i class="fas fa-eye-slash d-none" id="hide_eye_current_password"></i>
                                </span>
                            </div>
                            @error('current_password')
                                <p class="text-danger mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">Password Baru</label>
                            <div class="input-group mb-3">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="basic-addon2">
                                <span class="input-group-text" style="cursor: pointer !important;" id="basic-addon2" onclick="password();">
                                    <i class="fas fa-eye" id="show_eye"></i>
                                    <i class="fas fa-eye-slash d-none" id="hide_eye"></i>
                                </span>
                            </div>
                            @error('password')
                                <p class="text-danger mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Ulangi Password Baru</label>
                            <div class="input-group mb-3">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" aria-label="Password" aria-describedby="basic-addon2">
                                <span class="input-group-text" style="cursor: pointer !important;" id="basic-addon2" onclick="password_confirmation();">
                                    <i class="fas fa-eye" id="show_eye_password_confirmation"></i>
                                    <i class="fas fa-eye-slash d-none" id="hide_eye_password_confirmation"></i>
                                </span>
                            </div>
                            @error('password_confirmation')
                                <p class="text-danger mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- <div class="form-group">
                            <label for="current_password">Password saat ini</label>
                            <input type="password" name="current_password" id="current_password" class="form-control">
                            @error('current_password')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Password Baru</label>
                            <input type="password" name="password" id="password" class="form-control">
                            @error('password')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Ulangi Password baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control">
                            @error('password_confirmation')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div> --}}
                        <button type="submit" class="btn btn-update float-right">
                            <i class="fab fa-telegram-plane"></i> Ubah Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function password_current_password() {
            var x = document.getElementById("current_password");
            var show_eye = document.getElementById("show_eye_current_password");
            var hide_eye = document.getElementById("hide_eye_current_password");
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

        function password() {
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

        function password_confirmation() {
            var x = document.getElementById("password_confirmation");
            var show_eye = document.getElementById("show_eye_password_confirmation");
            var hide_eye = document.getElementById("hide_eye_password_confirmation");
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