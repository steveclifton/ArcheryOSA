@section ('dashboard')
    <h1></h1>
@endsection

@extends ('home')

@section ('title'){{$event->name}} @endsection

@section ('content')

    {{-- <div class="container"> --}}
    @if ($event->scoringenabled && !is_null($userevententry))
        <div class="row">
            @include('includes.scoring')
        </div>
    @endif


    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-md-9">
                    @include('includes.session_errors')
                    <div>
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Event Details</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">

                                @if ($event->scoringenabled || !is_null($userevententry))
                                        @if (!is_null($userevententry))
                                            <a href="{{ route('updateeventregistrationview', $event->eventid) }}" class="btn btn-warning pull-right" role="button">
                                                <i class="fa fa-gear" aria-hidden="true"></i> Update Entry
                                            </a>
                                        @else
                                            <a href="{{ route('eventregistrationview', ['eventid' => $event->eventid, 'name' => urlencode($event->name)] ) }}" class="btn btn-primary pull-right" role="button">
                                                <i class="fa fa-mail-forward" aria-hidden="true"></i> Enter
                                            </a>
                                        @endif
                                @endif

                                <h3>{{$event->name}}</h3>
                                <div class="table-responsive">
                                    <table class="table">
                                        @include('includes.events.eventdetails_table')
                                    </table>
                                    {!! nl2br($event->schedule) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--Previous Events--}}
                <div class="col-md-3">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Current Entries</h3>
                        </div>

                        @include('includes.events.eventdetails_currententries')

                        <!-- /.box-footer -->
                        <div class="box-footer text-center showmore">
                            <a href="javascript:;" class="uppercase" id="showmoreentries">View More Entries</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




@endsection

