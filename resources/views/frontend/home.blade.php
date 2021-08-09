@extends('frontend.layouts.app')

@section('title', 'Magic Pay')

@section('headerBar_title', 'Magic Pay')

@section('content')


    <div class="profile text-center">
        <a href="{{ route('profile.image') }}">
            @if (is_null($user->image))
                <img src="https://ui-avatars.com/api/?background=3d5af1&color=fff&name={{ $user->name }}"
                    class="rounded-circle img-thumbnail" alt="error">
        </a>
    @else
        <img src="{{ asset('storage/profile/' . $user->image) }}" class="rounded-circle img-thumbnail" alt="error"></a>
        @endif
        </a>

        <h5>{{ $user->name }}</h5>
        <p> {{ $user->wallet ? number_format($user->wallet->amount) : 0 }} MMK</p>
    </div>
    <div class="row">
        <div class="col-6">
            <a href="{{ route('qr.scan.pay') }}">
                <div class="card home">
                    <div class="card-body p-3">

                        <img src="{{ asset('frontend/images/qr-code-scan.png') }}" alt="">
                        <span class="ml-1 align-middle">Scan & Pay</span>

                    </div>
                </div>
            </a>


        </div>

        <div class="col-6">
            <a href="{{ route('qr.receive.qr') }}">
                <div class="card home">
                    <div class="card-body p-3">


                        <img src="{{ asset('frontend/images/qr-code.png') }}" alt="">
                        <span class="ml-1 align-middle">Receive QR</span>

                    </div>
                </div>
            </a>
        </div>




        <div class="col-12">
            <div class="card mt-2">
                <div class="card-body pr-0 home">

                    <a class="d-flex justify-content-between" href="{{ route('transfer') }}">

                        <span>
                            <img src="{{ asset('frontend/images/money-transfer (1).png') }}" class="mr-1" alt="">
                            Transfer Money
                        </span>
                        <span class="mr-3">
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </span>
                    </a>
                    <hr>
                    <a class="d-flex justify-content-between" href="{{ route('wallet') }}">
                        <span>
                            <img src="{{ asset('frontend/images/wallet.png') }}" class="mr-1" alt="">
                            Wallet
                        </span>
                        <span class="mr-3">
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </span>
                    </a>
                    <hr>
                    <a class="d-flex justify-content-between" href="{{ route('transaction') }}">
                        <span>
                            <img src="{{ asset('frontend/images/transaction.png') }}" class="mr-1" alt="">
                            Transcation</span>
                        <span class="mr-3">
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </span>
                    </a>

                </div>
            </div>
        </div>


    </div>




@endsection
