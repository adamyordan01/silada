@extends('layouts.base')

@section('modal')
    
@section('section-header')
    <h1>Profile</h1>
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/ijabo-crop/ijaboCropTool.min.css') }}">
@endpush
@section('content')

    <div class="row">
        <div class="col-12 col-md-12 col-lg-5">
            <div class="card profile-widget">
                <div class="profile-widget-header">
                    @if (auth()->user()->photo != '')
                        <img alt="image" src="{{ asset('profiles/'.auth()->user()->photo) }}" class="rounded-circle profile-widget-picture">
                    @else
                        <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}" class="rounded-circle profile-widget-picture">
                    @endif
                    {{-- <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}"
                        class="rounded-circle profile-widget-picture"> --}}
                </div>
                <div class="profile-widget-description">
                    <div class="profile-widget-name">{{ $user->name }} 
                        <div class="text-muted d-inline font-weight-normal">
                            <div class="slash"></div> {{ $user->position->name }}
                        </div>
                    </div>
                    <div>
                        <div class="profile-widget-name">
                            <div class="text-muted d-inline font-weight-normal">
                                <div class="row justify-content-between">
                                    <div class="col-5">
                                        E-Mail
                                    </div>
                                    <div class="col-7">
                                        <p class="" style="font-size: 17px">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="text-muted d-inline font-weight-normal">
                                <div class="row justify-content-between">
                                    <div class="col-5">
                                        NIP
                                    </div>
                                    <div class="col-7">
                                        <p class="" style="font-size: 17px"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="text-muted d-inline font-weight-normal">
                                <div class="row justify-content-between">
                                    <div class="col-5">
                                        Pangkat dan Golongan
                                    </div>
                                    <div class="col-7">
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="text-muted d-inline font-weight-normal">
                                <div class="row justify-content-between">
                                    <div class="col-5">
                                        KGB saat ini
                                    </div>
                                    <div class="col-7">
                                    </div>
                                </div>
                            </div>
                            <div class="text-muted d-inline font-weight-normal">
                                <div class="row justify-content-between">
                                    <div class="col-5">
                                        KGB Selanjutnya
                                    </div>
                                    <div class="col-7">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-12 col-lg-7">
            <div class="card">
                {{-- <form method="post" class="needs-validation" novalidate="" enctype="multipart/form-data"> --}}
                    {{-- @csrf --}}
                    {{-- @method('PUT') --}}

                    <div class="card-header">
                        <h4>Edit Profile</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>E-Mail</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->email }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Foto Profile</label>
                            <input type="file" class="form-control image" id="photo" name="photo">
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-primary" id="save_change">Save Changes</button>
                    </div>
                {{-- </form> --}}
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/js/ijabo-crop/ijaboCropTool.min.js') }}"></script>
    
    <script>
        $.ajaxSetup({
            headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf_token"]').attr('content')
            }
        });
            // $('#save_change').click(function () {
            //     console.log("Ok");
            // });

            $('#photo').ijaboCropTool({
                preview: '.profile-widget-picture',
                setRatio: 1,
                allowedExtensions: ['jpg', 'jpeg', 'png'],
                buttonsText: ['Crop', 'Cancel'],
                buttonsColor: ['#30bf7d', '#ee5155', -15],
                processUrl: '{{ route("profile.update") }}',
                // withCSRF: ['_token', '{{ csrf_token() }}'],
                onSuccess: function (message, element, status) {
                    alert(message);
                },
                onError: function (message, element, status) {
                    alert(message);
                }
            });
    </script>
@endpush