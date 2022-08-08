@extends('layouts.auth', ["title" => "Login"])
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/auth/css/style.css') }}">
@endpush
@section('content')
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center mt-0">
                <div class="col-md-7 col-lg-5">
                    <div class="wrap">
                        <div class="img" style="background-image: url({{ asset('assets/auth/images/background_silada.png') }});"></div>
                        <div class="login-wrap p-4 p-md-5">
                            <div class="d-flex">
                                <div class="w-100">
                                    <h3 class="mb-4 text-center" style="margin-top: -1rem !important">Sign In</h3>
                                </div>
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif
                            <form action="{{ route('login.authenticate') }}" class="signin-form" method="POST">
                                @csrf
                                <div class="form-group mt-3">
                                    <input name="username" type="text" class="form-control" required autofocus autocomplete="off">
                                    <label class="form-control-placeholder" for="username">Username</label>
                                </div>
                                <div class="form-group" style="margin-top: 2.2rem !important">
                                    <input id="password-field" name="password" type="password" class="form-control" required>
                                    <label class="form-control-placeholder" for="password">Password</label>
                                    <span toggle="#password-field"
                                        class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="form-control btn btn-primary rounded submit px-3">Sign
                                        In
                                    </button>
                                </div>
                                <div class="form-group d-md-flex">
                                    <div class="w-50 text-left">
                                        {{-- <label class="checkbox-wrap checkbox-primary mb-0">Remember Me
                                            <input type="checkbox" checked>
                                            <span class="checkmark"></span>
                                        </label> --}}
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="remember" style="color: #01d28e" class="custom-control-input" tabindex="3" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <label class="custom-control-label" style="color: #01d28e" for="remember">Remember Me</label>
                                        </div>
                                    </div>
                                    {{-- <div class="w-50 text-md-right">
                                        <a href="#">Forgot Password</a>
                                    </div> --}}
                                </div>
                            </form>
                            {{-- <p class="text-center">Not a member? <a data-toggle="tab" href="#signup">Sign Up</a></p> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- <div class="container mt-2">
        <div class="row">
            <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="login-brand mb-3">
                <img src="{{ asset('/') }}assets/img/Logo IKU UNSAM Small.png" alt="logo" width="150" class="">
            </div>

            <div class="card card-primary">
                <div class="card-header"><h4>Login</h4></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate="">
                        @csrf

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" tabindex="1" required autofocus>
                            <div class="invalid-feedback">
                                Please fill in your email
                            </div>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="d-block">
                                <label for="password" class="control-label">Password</label>
                                <div class="float-right">
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="text-small">
                                            Forgot Password?
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" tabindex="2" required>
                            <div class="invalid-feedback">
                                please fill in your password
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="remember">Remember Me</label>
                            </div>
                        </div>

                        <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                            Login
                        </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="simple-footer">
                Copyright &copy; Indikator Kinerja Utama Fakultas Pendidikan | Universitas Samudra - @php
                    echo date('Y');
                @endphp
            </div>
            </div>
        </div>
    </div> --}}
@endsection

@push('script')
    <script src="{{ asset('assets/auth/js/main.js') }}"></script>
@endpush