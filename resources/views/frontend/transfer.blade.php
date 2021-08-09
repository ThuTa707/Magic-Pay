@extends('frontend.layouts.app')

@section('title', 'Transfer Money')

@section('headerBar_title', 'Transfer Money')

@section('content')

    <div class="card">
        <div class="card-body">

            @include('frontend.layouts.error')

            <form action="{{ route('transfer.confirm') }}" method="GET" id="transfer_form" autocomplete="off">
                @csrf

                {{-- <div>
                    <strong>From</strong>
                    <p class="mb-1 text-muted">{{ Auth::user()->name }}</p>
                    <p class="mb-1 text-muted">{{ Auth::user()->phone }}</p>
                </div> --}}

                <input type="hidden" name="hash_hide" id="hash_hide">
                <div class="mb-2">
                    <label for="phone">To <span class="verify-name text-danger"></span></label>

                    <div class="input-group mb-3">
                        <input id="phone" type="text"
                            class="form-control verify-account @error('phone') is-invalid @enderror" name="phone"
                            value="{{ old('phone') }}">
                        <span class="input-group-text btn bg-primary text-white verify-btn">
                            <i class="fa fa-check-circle" aria-hidden="true"></i>
                        </span>
                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>




                <div class="mb-3">
                    <label for="amount">Amount (MMK)</label>

                    <input id="amount" type="string" class="form-control @error('amount') is-invalid @enderror"
                        name="amount" value="{{ old('amount') }}" autofocus>

                    @error('amount')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>




                <div class="mb-4">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" rows="2"
                        name="description">{{ old('description') }}</textarea>

                    @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>


                <button  class="btn btn-primary btn-block btn-hash">Continue</button>

            </form>


        </div>
    </div>
@endsection

@section('foot')

    <script>
        $(document).ready(function() {

            $('.verify-btn').click(function() {

                let phone = $('.verify-account').val();

                $.ajax({
                    type: "GET",
                    url: "/phone/verify/?phone=" + phone,
                    success: function(response) {
                        if (response.status == 'success') {
                            $('.verify-name').text("(" + response.data['name'] + ")");
                        } else {
                            $('.verify-name').text("(" + response.message + ")");
                        }

                        console.log(response);

                    }
                });
            })

            $('.btn-hash').click(function(e) {

                e.preventDefault();
                let phone = $('#phone').val();
                let amount = $('#amount').val();
                let description = $('#description').val();

                $.ajax({
                    url: `/transfer/hash/?phone=${phone}&amount=${amount}&description=${description}`,
                    type: 'GET',
                    success: function(result) {
                            $('#hash_hide').val(result.data);
                            $('#transfer_form').submit();

                    }
                });


            })

        })
    </script>

@endsection
