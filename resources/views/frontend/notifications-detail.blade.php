@extends('frontend.layouts.app')

@section('title', 'Notifications Detail')

@section('headerBar_title', 'Notifications Detail')

@section('content')

    <div class="notifications-detail">

        <div class="card">
            <div class="card-body text-center">
                <img src="{{asset('frontend/images/notifications-rafiki.png')}}" alt="">
                <h6>{{$notification->data['title']}}</h6>
                <p class="mb-1"><i class="fa fa-check" aria-hidden="true"></i> {{$notification->data['message']}}</p>
                <p class="text-muted"><small>{{$notification->created_at->format('d-m-Y h:ia')}}</small></p>
                
                <a href="{{$notification->data['web_link']}}" class="btn btn-primary btn-sm">Continue</a>
             

              

            </div>
        </div>
      
    </div>
@endsection


