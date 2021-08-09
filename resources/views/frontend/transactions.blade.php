@extends('frontend.layouts.app')

@section('title', 'Transactions')

@section('headerBar_title', 'History')

@section('content')

    <form action="{{ route('transaction.filter') }}" method="GET" id="filter" autocomplete="off">
        <h6> <i class="fa fa-filter" aria-hidden="true"></i> Filter</h6>
        <div class="row">
            <div class="col-6">

                <div class="input-group mb-3">
                    <select class="custom-select typeFilter" id="typeFilter" name="typeFilter"
                        aria-label="Example select with button addon">

                        <option value="" selected>All</option>
                        <option value="1" @if (request()->typeFilter == 1) selected @endif>Income</option>
                        <option value="2" @if (request()->typeFilter == 2) selected @endif>Expense</option>

                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="button">Type</button>
                    </div>
                </div>

            </div>


            <div class="col-6">

                <div class="input-group mb-3">

                    <input type="text" class="form-control date" name="dateFilter"
                       value="{{request()->dateFilter}}"  placeholder="All">

                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="button">Date</button>
                    </div>
                </div>
            </div>


        </div>

    </form>

    <h6> <i class="fas fa-exchange-alt"></i> Transactions</h6>
    <div class="transactions">
        <div class="infinite-scroll">
            @foreach ($transactions as $transaction)

                <a href="{{ route('transaction.detail', $transaction->transaction_id) }}" class="trans">
                    <div class="card mb-2">
                        <div class="card-body p-2">


                            <h6>{{ $transaction->created_at->format('d M Y') }}
                                ({{ $transaction->created_at->format('h:ia') }})
                            </h6>
                            <div class="d-flex justify-content-between">

                                @if ($transaction->type == 1)
                                    <div>

                                        <h6>Cash In</h6>
                                        <p class="text-muted mb-1">
                                            From - {{ $transaction->sourceUser ? $transaction->sourceUser->name : '' }}
                                        </p>
                                        <p class="text-muted mb-1">Transaction ID- {{ $transaction->transaction_id }}</p>
                                    </div>
                                    <div>
                                        <p class="text-success font-weight-bold">+{{ $transaction->amount }} <small>MMK</small></p>
                                    </div>

                                @endif


                                @if ($transaction->type == 2)
                                    <div>

                                        <h6>Cash Out</h6>
                                        <p class="text-muted mb-1">
                                            To - {{ $transaction->sourceUser ? $transaction->sourceUser->name : '' }}
                                        </p>
                                        <p class="text-muted mb-1">Transaction ID- {{ $transaction->transaction_id }}</p>
                                    </div>
                                    <div>
                                        <p class="text-danger font-weight-bold">-{{ $transaction->amount }} <small>MMK</small></p>
                                    </div>

                                @endif

                            </div>



                        </div>
                    </div>
                </a>
            @endforeach
            {{ $transactions->appends(Request::all())->links() }}
        </div>
    </div>
@endsection

@section('foot')



    <script type="text/javascript">
        //Jscrollable for infinity scroll(pagination) From documentation
        $('ul.pagination').hide();
        $(function() {
            $('.infinite-scroll').jscroll({
                autoTrigger: true,
                loadingHtml: '<img class="center-block" src="/images/loading.gif" alt="Loading..." />',
                padding: 0,
                nextSelector: '.pagination li.active + li a',
                contentSelector: 'div.infinite-scroll',
                callback: function() {
                    $('ul.pagination').remove();
                }
            });
        });

        // Date Filter (Date Range Picker) From doucmentation
        $('.date').daterangepicker({
            "singleDatePicker": true,
            "autoApply": false,
            "autoUpdateInput": false,
            "locale": {
                "format": "YYYY-MM-DD",
            },
        }, function(start, end, label) {
            console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format(
                'YYYY-MM-DD') + ' (predefined range: ' + label + ')');
        });


        $('.date').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            $('#filter').submit();
        });

        $('.date').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            $('#filter').submit();
        });

        $('.typeFilter').change(function() {
            $('#filter').submit();
        })


        // Without Using Form
        // Date and  Type Filter Using Javascript Histroy push state
        // $('.custom-select').change(function() {

        //     let typeFilter = $('.custom-select').val();
        //     let dateFilter = $('.dateFilter').val();
        //     history.pushState(null, '', `?typeFilter=${typeFilter}&dateFilter=${dateFilter}`);
        //     window.location.reload();
        // })


        // $('.dateFilter').change(function() {

        //     let typeFilter = $('.custom-select').val();
        //     let dateFilter = $('.dateFilter').val();
        //     history.pushState(null, '', `?typeFilter=${typeFilter}&dateFilter=${dateFilter}`);
        //     window.location.reload();
        // })
    </script>

@endsection
