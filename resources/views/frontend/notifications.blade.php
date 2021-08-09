@extends('frontend.layouts.app')

@section('title', 'Notifications')

@section('headerBar_title', 'Notifications')

@section('content')

    <div class="notifications">
        <div class="infinite-scroll">

            @foreach ($notifications as $notification)

                <a href="{{ route('notifications.show', $notification->id) }}">
                    <div class="card mb-2 @if (is_null($notification->read_at)) bg-noti @endif">
                        <div class="card-body p-3">

                            <div class="d-flex justify-content-between">
                                <div>
                                    @if ($notification->data['sourceable_type'] == App\User::class)
                                        <i class="fa fa-user @if (is_null($notification->read_at)) text-danger @endif " aria-hidden="true"></i>
                                    @elseif ($notification->data['sourceable_type'] == App\Transaction::class) 
                                    <i class="fa fa-exchange-alt @if (is_null($notification->read_at)) text-danger @endif " aria-hidden="true"></i>
                                    @endif


                                    <strong>{{ Str::limit($notification->data['title'], 40, '...') }}</strong>
                                    <p class=" mb-1 text-muted">
                                        {{ Str::limit($notification->data['message'], 100, '...') }}</p>
                                </div>

                                <div>
                                    <p class="mb-1 text-muted">{{ $notification->created_at->format('d/m/Y') }}</p>
                                </div>

                            </div>

                        </div>
                    </div>
                </a>

            @endforeach
            {{ $notifications->appends(Request::all())->links() }}

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
    </script>

@endsection
