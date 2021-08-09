@extends('frontend.layouts.app')

@section('title', 'Profile Picture Upload')

@section('headerBar_title', 'Profile Picture Upload')

@section('content')

    <div class="card mb-3">
        <div class="card-body">

            <div class="text-center mb-3">
                @if (is_null($user->image))
                    <img src="https://ui-avatars.com/api/?background=3d5af1&color=fff&name={{ $user->name }}"
                        class="rounded-circle img-thumbnail w-50" alt="error"></a>
                @else
                    <img src="{{ asset('storage/profile/' . $user->image) }}" class="rounded-circle img-thumbnail w-50"
                        alt="error"></a>

                @endif
            </div>

            <form method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-0 mr-2">
                    <label class="text-center">
                        <i class="mr-1 fas fa-image"></i>
                        Select New Photo
                    </label>
                    <input type="file" name="image"
                        class="form-control p-1 mr-2 overflow-hidden @error('image') is-invalid @enderror">
                    @error('image')
                        <small class="font-weight-bold text-danger text-center">{{ $message }}</small>
                    @enderror

                </div>
                <button type="submit" class=" mt-4 btn btn-primary btn-block">
                    <i class="fas fa-upload"></i> Upload
                </button>
            </form>


        </div>
    </div>
@endsection
