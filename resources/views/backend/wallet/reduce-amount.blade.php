@extends('backend.layouts.app')

@section('title', 'Wallets Management')
@section('wallets-active', 'mm-active')
@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">

            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-wallet icon-gradient bg-mean-fruit">
                    </i>
                </div>
                <div>Reduce Amount
                </div>


            </div>

        </div>

        <div class="card mt-2">
            <div class="card-body">

                @include('backend.layouts.error')

                <form action="{{ route('admin.reduce.amount.wallet') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="selectUser" class="form-label"> <i class="fa fa-users" aria-hidden="true"></i> Choose
                            Users</label>
                        <select class="form-control @error('user') is-invalid @enderror" id="selectUser" name="user">
                            <option></option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->phone }})</option>
                            @endforeach

                        </select>

                        @error('user')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
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


                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" rows="2"
                            name="description">{{ old('description') }}</textarea>

                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="text-center">
                        <button class="btn btn-primary">Reduce</button>
                        <button class="btn btn-secondary">Cancel</button>
                    </div>

                </form>



            </div>

        </div>



    </div>


@endsection


@section('foot')
    <script>
        $(function() {

            $('#selectUser').select2({
                theme: 'bootstrap4',
                placeholder: "Users",
                allowClear: true
            });

        })
    </script>

@endsection
