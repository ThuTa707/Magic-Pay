@extends('frontend.layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-6">
                <div class="card shadow border-0">

                    <div class="card-body">

                        <h3 class="text-bold text-primary text-center">
                            <i class="fas fa-wallet"></i> Magic Pay Login Form
                        </h3>
                        <p class="text-muted text-center">Welcome From Magic Pay!!!</p>

                        <form method="POST" action="{{ route('login') }}" autocomplete="off">
                            @csrf

                            <div class="form-group">
                                <label for="phone">
                                    <i class="fa fa-phone-alt" aria-hidden="true"></i>  Phone
                                </label> 

                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror"
                                    name="phone" value="{{ old('phone') }}" autofocus>

                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

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


                            {{-- Remember Me --}}
                            {{-- <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div> --}}


                            <button type="submit" class="btn btn-primary btn-block mt-4 mb-3">
                                Login
                            </button>

                            <a class="btn btn-link" href="{{ route('register') }}">Don't have an account? Sign Up!</a>

                            @if (Route::has('password.request'))
                                <a class="btn btn-link float-right" href="{{ route('password.request') }}">
                                    <i class="fa fa-key" aria-hidden="true"></i> Forgot Your Password
                                </a>
                            @endif

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
