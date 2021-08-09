@extends('frontend.layouts.app')

@section('title', 'Wallet')

@section('headerBar_title', 'Wallet')

@section('content')

    <div class="wallet">    
        <div class="card bg-primary text-white my-card">
            <div class="card-body">

                <div class="mb-4">
                    <small class="mb-5">Available Balance</small>
                    <h4> {{ number_format($user->wallet ? $user->wallet->amount : '0') }} <span class="balance">MMK</span>
                    </h4>
                </div>

                <div class="mb-4">
                    <small class="mb-5">Account Number</small>
                    <h4>{{ $user->wallet ? $user->wallet->account_number : '_' }}</h4>
                </div>


                <div>
                    <h5>{{ $user->name }}</h5>
                </div>
            </div>
        </div>
    </div>

@endsection
