@extends('layouts.app')

@section('content')

<div class="section-header">
    <div>
        <div class="page-title">Profil</div>
        <div class="page-subtitle">
            Kelola informasi akun dan foto profil
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="profile-card">

            <div class="text-center mb-4">

                <div class="profile-avatar-wrapper">

                    @if(auth()->user()->avatar)
                        <img id="profilePreview"
                             src="{{ asset('storage/' . auth()->user()->avatar) }}"
                             class="profile-avatar">
                    @else
                        <img id="profilePreview"
                             src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=2563eb&color=fff&size=200"
                             class="profile-avatar">
                    @endif

                    <label for="avatarInput" class="profile-camera-btn">
                        <i class="bi bi-camera-fill"></i>
                    </label>

                </div>

                <h3 class="fw-bold mt-3 mb-1">
                    {{ auth()->user()->name }}
                </h3>

                <p class="text-muted mb-0">
                    {{ auth()->user()->email }}
                </p>

            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="/profile" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="form-label">Foto Profil</label>

                    <input type="file"
                           id="avatarInput"
                           name="avatar"
                           class="form-control"
                           accept="image/*"
                           onchange="previewImage(event)">

                    <small class="text-muted">
                        Format yang disarankan: JPG, JPEG, atau PNG.
                    </small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>

                    <input type="text"
                           name="name"
                           value="{{ auth()->user()->name }}"
                           class="form-control">
                </div>

                <div class="mb-4">
                    <label class="form-label">Email</label>

                    <input type="email"
                           name="email"
                           value="{{ auth()->user()->email }}"
                           class="form-control">
                </div>

                <button class="btn btn-primary-custom w-100">
                    <i class="bi bi-save"></i>
                    Simpan Perubahan
                </button>
            </form>

        </div>

    </div>
</div>

<script>
    function previewImage(event) {
        const image = document.getElementById('profilePreview');
        image.src = URL.createObjectURL(event.target.files[0]);
    }
</script>

@endsection