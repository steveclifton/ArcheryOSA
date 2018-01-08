@extends ('home')

@section ('title')Scoring @endsection

@section ('content')
    @include('includes.session_errors')

    <div class="row">
        @include('includes.events.eventdetails_nav')
    </div>



    @if($event->eventtype == 1)
        {{--@include('auth.events.event_league_scoringrounds')--}}
    @else
        {{--@include('auth.events.event_scoringrounds')--}}
    @endif


@endsection
