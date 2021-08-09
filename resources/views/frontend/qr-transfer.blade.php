@extends('frontend.layouts.app')

@section('title', 'QR Transfer Money')

@section('headerBar_title', ' QR Transfer Money')

@section('content')

    <div class="card">
        <div class="card-body">

            @include('frontend.layouts.error')

            <form action="{{route('qr.transfer.confirm')}}" method="GET" id="transfer_form">
                @csrf

                <input type="hidden" name="hash_hide" id="hash_hide">
                <input type="hidden" name="phone" value={{$to_user->phone}} id="phone">

                <div class="mb-3">
                    <strong>To</strong>
                    <p class="mb-1 text-muted">{{$to_user->phone}}</p>
                    <p class="mb-1 text-muted">{{$to_user->name}}</p>
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


                <button class="btn btn-primary btn-block btn-hash">Continue</button>

            </form>


        </div>
    </div>
@endsection

@section('foot')

    <script>
        $(document).ready(function() {

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
