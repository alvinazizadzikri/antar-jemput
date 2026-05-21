@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="card border-0 shadow-sm">

                    {{-- HEADER --}}
                    <div class="card-body p-5">

                        <div class="text-center mb-4">

                            {{-- FOTO PROFILE --}}
                            <div class="position-relative d-inline-block">

                                @if(auth()->user()->avatar)

                                    <img id="profilePreview" src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                        class="rounded-circle border shadow" width="140" height="140" style="object-fit:cover;">

                                @else

                                    <img id="profilePreview"
                                        src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=4e73df&color=fff&size=200"
                                        class="rounded-circle border shadow" width="140" height="140" style="object-fit:cover;">

                                @endif

                                {{-- ICON CAMERA --}}
                                <label for="avatarInput"
                                    class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow"
                                    style="width:38px; height:38px; cursor:pointer;">

                                    📷

                                </label>

                            </div>

                            <h3 class="fw-bold mt-3 mb-1">
                                {{ auth()->user()->name }}
                            </h3>

                            <p class="text-muted">
                                {{ auth()->user()->email }}
                            </p>

                        </div>

                        {{-- ALERT --}}
                        @if(session('success'))

                            <div class="alert alert-success">

                                {{ session('success') }}

                            </div>

                        @endif

                        {{-- FORM --}}
                        <form method="POST" action="/profile" enctype="multipart/form-data">

                            @csrf

                            {{-- FOTO --}}
                            <div class="mb-4">

                                <label class="form-label fw-semibold">

                                    Foto Profile

                                </label>

                                <input type="file" id="avatarInput" name="avatar" class="form-control" accept="image/*"
                                    onchange="previewImage(event)">

                                <small class="text-muted">
                                    Format: JPG, JPEG, PNG
                                </small>

                            </div>

                            {{-- NAMA --}}
                            <div class="mb-3">

                                <label class="form-label fw-semibold">

                                    Nama Lengkap

                                </label>

                                <input type="text" name="name" value="{{ auth()->user()->name }}" class="form-control">

                            </div>

                            {{-- EMAIL --}}
                            <div class="mb-4">

                                <label class="form-label fw-semibold">

                                    Email

                                </label>

                                <input type="email" name="email" value="{{ auth()->user()->email }}" class="form-control">

                            </div>

                            {{-- BUTTON --}}
                            <div class="d-grid">

                                <button class="btn btn-primary btn-lg">

                                    💾 Simpan Perubahan

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- PREVIEW IMAGE --}}
    <script>

        function previewImage(event) {
            const image = document.getElementById('profilePreview');

            image.src = URL.createObjectURL(event.target.files[0]);
        }

    </script>

@endsection