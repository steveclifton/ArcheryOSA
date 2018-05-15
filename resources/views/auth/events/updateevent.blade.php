@section ('dashboard')
    <h1></h1>
@endsection

<script>
    var collapse_siderbar = true;
</script>

@extends ('home')

@section ('title')Update Event @endsection

@section ('content')

    <div class="row">
        <div class="col-md-12 ">
            @include('includes.session_errors')
        </div>
    </div>

    <div class="row">
        <div class="col-md-10">
            {{----}}
        </div>
    </div>

    <div class="row">

        <div class="col-md-2">
            <div class="box box-info ">
                <div class="box-header with-border">
                    <h3 class="box-title">Event</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">   &nbsp;
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>

                @include('includes.adminevents.sidebar')

            </div>
        </div>

        <div class="col-md-10">
            <div class="box box-info ">
                <div class="box-header with-border">
                    <h3 class="box-title" style="text-weight:bold">{{$event->name}}</h3>
                </div>
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <div class="panel-body" id="adminevents">

                    @if(empty($eventrounds))
                        @include('includes.adminevents.eventrounds')
                    @else
                        @include('includes.events.eventdetails_details')
                    @endif



                </div>
            </div>
        </div>
    </div>

@endsection


