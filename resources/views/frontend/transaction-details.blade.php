@extends('frontend.layouts.app')

@section('title', 'Transaction Detail')

@section('headerBar_title', 'Transaction Detail')

@section('content')


    <div class="card trans-detail">
        <div class="card-body p-2">

            <div class="text-center">
                <img src="{{asset('frontend/images/checked.png')}}" alt="">
            </div>

            @if ($transaction->type == 1)
            <h6 class="text-center text-success mt-2">+{{$transaction->amount}} <small>MMK</small></h6>
            @endif

            @if ($transaction->type == 2)
            <h6 class="text-center text-danger mt-2">-{{$transaction->amount}} <small>MMK</small></h6>
            @endif
           

            <div class="d-flex justify-content-between">
                <p>Transaction ID</p>
                <p>{{$transaction->transaction_id}}</p>  
            </div>
            <hr>

            <div class="d-flex justify-content-between">
                <p>Ref No</p>
                <p>{{$transaction->ref_no}}</p> 
            </div>
            <hr>

            <div class="d-flex justify-content-between">
                <p>Type</p>
                @if ($transaction->type == 1)
                <p class="badge badge-pill badge-success">Income</p> 
                @endif

                @if ($transaction->type == 2)
                <p class="badge badge-pill badge-danger">Expense</p> 
                @endif
              
            </div>
            <hr> 

            {{-- <div class="d-flex justify-content-between">
                <p>Amount</p>
                <p>{{$transaction->amount}} <small>MMK</small></p> 
            </div>
            <hr> --}}

            <div class="d-flex justify-content-between">
                <p>Date & Time</p>
                <p>{{$transaction->created_at}}</p> 
            </div>
            <hr>

            <div class="d-flex justify-content-between">
              
                @if ($transaction->type == 1)
                <p>From</p>
                <p>{{$transaction->sourceUser ? $transaction->sourceUser->name : '-'}}</p> 
                @endif

                @if ($transaction->type == 2)
                <p>To</p>
                <p>{{$transaction->sourceUser ? $transaction->sourceUser->name : '-'}}</p> 
                @endif
              
            </div>
            <hr> 

            <div class="d-flex justify-content-between">
                <p>Description</p>
                <p>{{$transaction->description}}</p> 
            </div>
        </div>
    </div>


@endsection
