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
                <div>Wallets
                </div>


            </div>

            
            <a href="{{route('admin.add.amount')}}" class="btn btn-success mt-2 ml-3"><i
                class="fa fa-plus-circle" aria-hidden="true"></i> Add Amount</a>

                <a href="{{route('admin.reduce.amount')}}" class="btn btn-danger
                 mt-2 ml-3"><i class="fa fa-minus-circle" aria-hidden="true"></i> Reduce Amount</a>

        </div>





        <div class="card mt-2">
            <div class="card-body">

                <table id="tableTest" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Acc Number</th>
                            <th>Acc Person</th>
                            <th>Amount</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>

        </div>

    </div>


@endsection


@section('foot')
    <script>
        $(function() {
            $('#tableTest').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "/admin/datatable/wallets/", // Pls write carefully your route
                columns: [
                    {
                        data: 'id',
                        name: 'id',

                    },
                    {
                        data: 'account_number',
                        name: 'account_number',

                    },
                    {
                        data: 'user_id',
                        name: 'user_id'

                    },
                    {
                        data: 'amount',
                        name: 'amount'

                    },
                    {
                        data: 'created_at',
                        name: 'created_at',


                    }

                ]
                ,
                order: [
                    [4, "desc"]
                ]

            });
        });
    </script>

@endsection
