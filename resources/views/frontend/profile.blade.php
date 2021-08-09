@extends('frontend.layouts.app')

@section('title', 'Profile')

@section('headerBar_title', 'Profile')

@section('content')




    <div class="profile text-center mb-3">
        <a href="{{ route('profile.image') }}">
            @if (is_null($user->image))
                <img src="https://ui-avatars.com/api/?background=3d5af1&color=fff&name={{ $user->name }}"
                    class="rounded-circle img-thumbnail" alt="error">
        </a>
    @else
        <img src="{{ asset('storage/profile/' . $user->image) }}" class="rounded-circle img-thumbnail" alt="error"></a>

        @endif


    </div>


    <div class="card mb-2">
        <div class="card-body pr-0">

            <div class="d-flex justify-content-between">
                <span> <i class="fa fa-user" aria-hidden="true"></i> Name</span>
                <span class="mr-3">{{ $user->name }}</span>
            </div>
            <hr>
            <div class="d-flex justify-content-between">
                <span> <i class="fa fa-phone" aria-hidden="true"></i> Phone</span>
                <span class="mr-3">{{ $user->phone }}</span>
            </div>
            <hr>
            <div class="d-flex justify-content-between">
                <span> <i class="fa fa-envelope" aria-hidden="true"></i> Email</span>
                <span class="mr-3">{{ $user->email }}</span>
            </div>

        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body pr-0">

             
                    <a class="d-flex justify-content-between" href="{{ route('profile.password') }}">
                        <span> <i class="fa fa-key" aria-hidden="true"></i> Update Password</span>
                        <span class="mr-3">
                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </span>
                    </a>
         

             
                    {{-- <a class="d-flex justify-content-between" href="">
                        <span> <i class="fa fa-info-circle" aria-hidden="true"></i> Update Info</span>
                    </a> --}}
            
        



            <hr>
            <a class="d-flex justify-content-between logout-div">
                <span> <i class="fas fa-sign-out-alt"></i> Logout</span>
                <span class="mr-3">
                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                </span>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>

        </div>
    </div>
@endsection

@section('foot')

    <script>
        $(".logout-div").click(function() {
            Swal.fire({
                title: 'Are you sure to logout?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Logout Success',
                        'You will logout in 3sec.',
                        'success'
                    )

                    setTimeout(function() {
                        $("#logout-form").submit();
                    }, 3000)
                }
            })
        })
    </script>

@endsection
