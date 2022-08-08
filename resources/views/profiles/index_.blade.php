@extends('layouts.base')

@section('modal')
    <div class="modal fade" data-keyboard="false" data-backdrop="static" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Laravel Cropper Js - Crop Image Before Upload - Tutsmake.com
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <div class="row">
                            <div class="col-md-8">
                                <img id="image" src="">
                            </div>
                            <div class="col-md-4">
                                <div class="preview"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="crop">Crop</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('section-header')
    <h1>Profile</h1>
@endsection
@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css" />
    <style>
        img {
            display: block;
            max-width: 100%;
        }

        .preview {
            overflow: hidden;
            width: 160px;
            height: 160px;
            margin: 10px;
            border: 1px solid red;
        }

        .modal-lg {
            max-width: 1000px !important;
        }
    </style>
@endpush
@section('content')

    <div class="row">
        <div class="col-12 col-md-12 col-lg-5">
            <div class="card profile-widget">
                <div class="profile-widget-header">
                    <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}"
                        class="rounded-circle profile-widget-picture">
                </div>
                <div class="profile-widget-description">
                    <div class="profile-widget-name">{{ auth()->user()->name }} <div
                            class="text-muted d-inline font-weight-normal">
                            <div class="slash"></div> Web Developer
                        </div>
                    </div>
                    Ujang maman is a superhero name in <b>Indonesia</b>, especially in my family. He is not a fictional
                    character but an original hero in my family, a hero for his children and for his wife. So, I use the
                    name as a user in this template. Not a tribute, I'm just bored with <b>'John Doe'</b>.
                </div>
                <div class="card-footer text-center">
                    <div class="font-weight-bold mb-2">Follow Ujang On</div>
                    <a href="#" class="btn btn-social-icon btn-facebook mr-1">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="btn btn-social-icon btn-twitter mr-1">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="btn btn-social-icon btn-github mr-1">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="#" class="btn btn-social-icon btn-instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-12 col-lg-7">
            <div class="card">
                <form method="post" class="needs-validation" action="{{ route('profile.update', auth()->id()) }}" novalidate="" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card-header">
                        <h4>Edit Profile</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Nomor Induk Pegawai</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->nip }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>E-Mail</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->email }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Foto Profile</label>
                            <input type="file" class="form-control image" name="photo">
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>
<script>
    // $('#modal').modal({backdrop: 'static', keyboard: false})  
    var $modal = $('#modal');
    var image = document.getElementById('image');
    var cropper;

    $("body").on('change', '.image', function (e) {
        var files = e.target.files;
        var done = function (url) {
            image.src = url;
            $modal.modal('show');
        }

        var reader;
        var file;
        var url;

        if (files && files.length > 0) {
            file = files[0];

            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function (e) {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
            }
        }
    })
    $modal.on('shown.bs.modal', function () {
        cropper = new Cropper(image, {
            aspectRatio: 1,
            viewMode: 3,
            preview: '.preview'
        });
    }).on('hidden.bs.modal', function () {
        cropper.destroy();
        cropper = null;
    });
    $("#crop").click(function () {
        canvas = cropper.getCroppedCanvas({
            width: 200,
            height: 200
        });
        canvas.toBlob(function (blob) {
            url = URL.createObjectURL(blob);
            var reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = function () {
                var base64data = reader.result;
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "/profile/update/2",
                    data: {
                        '_method': 'PUT',
                        '_token': "{{ csrf_token() }}",
                        'photo': base64data
                    },
                    success: function (data) {
                        console.log(data);
                        $modal.modal('hide');
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                })
            }
        })
    })
</script>
    
@endpush