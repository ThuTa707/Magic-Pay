@extends('frontend.layouts.app')

@section('title', 'Transfer Confirm')

@section('headerBar_title', 'Transfer Confirm')

@section('content')

    <div class="card">
        <div class="card-body">

            @include('frontend.layouts.error')

            <form action="{{ route('transfer.complete') }}" method="POST" id="completeForm">
                @csrf
                
                <input type="hidden" name="hash_hide" value="{{ request()->hash_hide }}">
                <input type="hidden" name="phone" value="{{ $to_user->phone }}">
                <input type="hidden" name=" amount" value="{{ $amount }}">
                <input type="hidden" name="description" value="{{ $description }}">

                <div class="mb-3">
                    <strong>From</strong>
                    <p class="mb-1 text-muted">{{ Auth::user()->name }}</p>
                    <p class="mb-1 text-muted">{{ Auth::user()->phone }}</p>
                </div>

                <div class="mb-3">
                    <strong>To</strong>
                    <p class="mb-1 text-muted">{{ $to_user->name }}</p>
                    <p class="mb-1 text-muted">{{ $to_user->phone }}</p>
                </div>

                <div class="mb-3">
                    <strong>Amount</strong>
                    <p class="mb-1 text-muted">{{ $amount }} MMK</p>
                </div>

                <div class="mb-4">
                    <strong>Description</strong>
                    <p class="mb-1 text-muted">{{ $description }}</p>
                </div>

                <button type="button" class="btn btn-primary btn-block btn-confirm">Confirm</button>
            </form>



        </div>
    </div>
@endsection

@section('foot')

    <script>
        $(document).ready(function() {
            $('.btn-confirm').click(function() {




                Swal.fire({
                    title: 'Type Your Password',
                    icon: 'info',
                    html: '<input type="password" class="text-center password"></input>',
                    showCloseButton: true,
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonText: 'Confirm',
                    cancelButtonText: 'Cancel',
                }).then((result) => {
                    if (result.isConfirmed) {

                        let password = $('.password').val();

                        $.ajax({
                            type: "GET",
                            url: "/password/check/?password=" + password,
                            success: function(response) {
                                if (response.status == 'success') {

                                    // Swal.fire(
                                    //     'Transfer Done!',
                                    //     'Your money has been transferred.',
                                    //     'success'
                                    // )

                                    $('#completeForm').submit();

                                } else {
                                    Swal.fire(
                                        'Oops!!',
                                        response.message,
                                        'error'
                                    )
                                }

                                console.log(response);

                            }
                        });

                    }
                })





            })
        });
    </script>

@endsection
