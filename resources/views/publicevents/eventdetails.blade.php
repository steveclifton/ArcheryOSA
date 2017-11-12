@section ('dashboard')
    <h1></h1>
@endsection

@extends ('home')

@section ('title'){{$event->name}} @endsection

@section ('content')

    @include('includes.session_errors')

    {{-- <div class="container"> --}}
    @if ($event->scoringenabled && (!is_null($userevententry) || Auth::id() == $event->createdby))
        <div class="row">
            @include('includes.events.eventdetails_scoring')
        </div>
    @endif

    <div class="row">
        @include('includes.events.eventdetails_sponsor')
    </div>

    @if ($event->scoringenabled && !is_null($userevententry))
        <div class="row">
            @include('includes.events.eventdetails_results')
        </div>
    @endif



    <div class="row">
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div>
                        <div class="box box-primary">
                            <div class="box-header with-border">

                                <h3 class="box-title">Event Details</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse" id="eventdetailboxtool">
                                        Click to Close  &nbsp;
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>

                            </div>



                            <!-- /.box-header -->
                            <div class="box-body">

                                @if ($event->visible || !is_null($userevententry))
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

                                    <div>
                                        <?= nl2br(substr($event->schedule, 0, 450)) ?>
                                        <?php if (strlen($event->schedule) > 450) { ?>
                                            <span style="font-weight: bold;text-decoration: underline;" class="readmore">...Read More</span>
                                            <span class="moreschedule hidden">
                                                    <?= nl2br(substr($event->schedule, 450)) ?>
                                                </span>
                                        <?php } ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('includes.events.eventdetails_currententries')

    </div>




@endsection

