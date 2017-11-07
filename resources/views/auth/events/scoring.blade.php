@section ('dashboard')
    <h1></h1>
@endsection

@extends ('home')

@section ('title')Scoring @endsection

@section ('content')

    {{--<div class="box-header">--}}
        {{--<h3 class="box-title">Responsive Hover Table</h3>--}}

        {{--<div class="box-tools">--}}
            {{--<div class="input-group input-group-sm" style="width: 150px;">--}}
                {{--<input type="text" name="table_search" class="form-control pull-right" placeholder="Search">--}}

                {{--<div class="input-group-btn">--}}
                    {{--<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

    {{-- <div class="container"> --}}
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><strong>Scoring</strong></h3><BR>
                    <h3 class="box-title">{{ ucwords($event->name)}}</h3>

                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <a href="{{URL::previous()}}">
                                <button type="submit" class="btn btn-primary pull-right" id="addevent">
                                    <i class="fa fa-backward" >
                                        <span style="font-family: sans-serif;"> Back</span>
                                    </i>
                                </button>
                            </a>
                        </div>
                    </div>
                    <hr>
                </div>


            <div class="box-body">
                {{--<div class="panel-heading">{!! ucwords($event->name) . ' - Scoring' !!}--}}
                    {{--<a href="{{route('events')}}">--}}
                        {{--<button type="submit" class="btn btn-default pull-right" id="addevent">--}}
                            {{--<i class="fa fa-backward" > Back</i>--}}
                        {{--</button>--}}
                    {{--</a>--}}
                {{--</div>--}}


                {{--<div class="panel-body">--}}
                    <form class="form-horizontal" method="POST" action="{{ route('enterscores', [$eventround[0]->eventroundid, $event->eventid])  }}" id="eventform">
                        {{ csrf_field() }}

                        <div class=" table-condensed table-striped table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="hidden">UserID</th>
                                        <th class="col-md-1 col-xs-1 col-sm-1">Archer</th>
                                        <th class="col-md-1 col-xs-1 col-sm-1">Division</th>

                                        @if (isset($distances['Distance-1']))
                                            <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">{!! $distances['Distance-1'] . $eventround[0]->unit !!}</th>
                                        @endif
                                        @if (isset($distances['Distance-2']))
                                            <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">{!! $distances['Distance-2'] . $eventround[0]->unit !!}</th>
                                        @endif
                                        @if (isset($distances['Distance-3']))
                                            <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">{!! $distances['Distance-3'] . $eventround[0]->unit !!}</th>
                                        @endif
                                        @if (isset($distances['Distance-4']))
                                            <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">{!! $distances['Distance-4'] . $eventround[0]->unit !!}</th>
                                        @endif
                                        <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">Total</th>
                                        <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">Hits</th>
                                        <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">10s</th>
                                        <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">X</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)

                                    {{--NEED LOGIC FOR VALIDATION --}}

                                    <tr>
                                        <td class="hidden">
                                            <input type="hidden" name="userid[]" value="{{$user->userid}}"></td>
                                        <td>{{$user->fullname}}</td>
                                        <td>{{$user->divisionname}}</td>

                                        @if(isset($distances['Distance-1'])) <td>
                                            <input type="text" class="form-control" name="dist1[]">
                                        </td> @endif
                                        @if(isset($distances['Distance-2'])) <td>
                                            <input type="text" class="form-control" name="dist2[]">
                                        </td> @endif
                                        @if(isset($distances['Distance-3'])) <td>
                                            <input type="text" class="form-control" name="dist3[]">
                                        </td> @endif
                                        @if(isset($distances['Distance-4'])) <td>
                                            <input type="text" class="form-control" name="dist4[]">
                                        </td> @endif

                                        <td><input type="text" class="form-control" name="total[]"></td>
                                        <td><input type="text" class="form-control" name="hit[]" placeholder="Optional"></td>
                                        <td><input type="text" class="form-control" name="10[]" placeholder="Optional"></td>
                                        <td><input type="text" class="form-control" name="x[]" placeholder="Optional"></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>


                        <button type="submit" class="btn btn-success pull-right" value="submit" name="submit">
                            Submit Scores
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    {{-- </div> --}}
    <!-- daterangepicker -->
    <script src="{{URL::asset('bower_components/moment/min/moment.min.js')}}"></script>
    <script src="{{URL::asset('bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
    <!-- datepicker -->
    <script src="{{URL::asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2();


            $('#scoredate').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true
            }).datepicker("update");

        });

        var collapse_siderbar = true;

    </script>

@endsection

