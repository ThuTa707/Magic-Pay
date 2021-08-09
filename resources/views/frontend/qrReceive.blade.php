@extends('frontend.layouts.app')

@section('title', 'Receive QR')

@section('headerBar_title', 'Receive QR')

@section('content')

    <div class="myQR">    
        <div class="card">
            <div class="card-body">
                <p class="mbA-0 text-center">Scan to pay me</p>
                <div class="text-center">
                    <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(200)->generate(Auth::user()->phone)) !!} ">
                </div>
                <p class="mb-0 text-center"> <strong>{{Auth::user()->name}}</strong></p>
                <p class="mb-0 text-center"> {{Auth::user()->phone}}</p>

            </div>
        </div>
    </div>

@endsection
