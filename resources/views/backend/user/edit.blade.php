@extends('backend.layouts.app')

@section('title', 'Edit Admin User')
@section('admin-users-active', 'mm-active')
@section('content')

    <div class="app-page-title create-admin-users">
        <div class="page-title-wrapper">

        </div>
        <div class="col-12 col-md-8 offset-md-2">
            <div class="card mt-2">
                <div class="card-header">
                    Edit Admin User
                </div>
                <div class="card-body">

                    <form action="{{route('admin.users.update', $user->id)}}" method="POST">
                        @csrf
                        @method('PATCH')
                      
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user->name ? $user->name : old('name') }}">

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email ? $user->email : old('email') }}">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        
                        <div class="form-group row">
                            <label for="phone" class="col-md-4 col-form-label text-md-right">Phone</label>

                            <div class="col-md-6">
                                <input id="phone" type="string" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ $user->phone ? $user->phone : old('phone') }}">

                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                         
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary float-right">
                                    
                                    Update

                                </button>
                            </div>
                        </div>


                    
                      </form>

                </div>
            </div>
        </div>


    </div>
@endsection
