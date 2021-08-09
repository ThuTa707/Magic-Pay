@extends('frontend.layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-6">
                <div class="card shadow border-0">

                    <div class="card-body">
                     
                
                                <h3 class="text-bold text-primary text-center">
                                   <i class="fas fa-wallet"></i> Magic Pay Register Form
                                </h3>
                                <p class="text-muted text-center">Fill form completely to be a magic pay user</p>
                           
                
                        <form method="POST" action="{{ route('register') }}" autocomplete="off">
                            @csrf

                            <div class="form-group">
                                <label for="name">
                                    <i class="fas fa-user"></i> Name
                                </label>

                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>


                            <div class="form-group">
                                <label for="email">
                                   <i class="fa fa-envelope" aria-hidden="true"></i> Email Address
                                </label>

                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                            <div class="form-group">
                                <label for="phone">
                                    <i class="fa fa-phone-alt" aria-hidden="true"></i> Phone
                                </label>

                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror"
                                    name="phone" value="{{ old('phone') }}" autofocus>

                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                          

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">
                                            <i class="fa fa-lock" aria-hidden="true"></i>  Password
                                        </label>
        
                                        <input id="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            value="{{ old('password') }}" autofocus>
        
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
        
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password-confirm">
                                           <i class="fa fa-key" aria-hidden="true"></i> Confirm Password
                                        </label>
                                    
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                                    </div>
                                </div>
                            </div>
                        

                            <button type="submit" class="btn btn-primary btn-block mt-4 mb-3">
                                Register
                            </button>

                            <a class="btn btn-link" href="{{ route('login') }}">Already have an account? Sign In!</a>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

