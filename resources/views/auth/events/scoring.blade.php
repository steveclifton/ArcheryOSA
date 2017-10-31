@section ('dashboard')
    <h1></h1>
@endsection

@extends ('home')

@section ('title')Scoring @endsection

@section ('content')

    {{-- <div class="container"> --}}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Create New Event
                    <a href="{{route('events')}}">
                        <button type="submit" class="btn btn-default pull-right" id="addevent">
                            <i class="fa fa-backward" > Back</i>
                        </button>
                    </a>
                </div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('createevent') }}" id="eventform">
                        {{ csrf_field() }}

                        <div class="form-group {{ $errors->has('scoredate') ? ' has-error' : '' }}">
                            <div class="col-md-6">
                                <label class="col-md-3 control-label">Scoring Date</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" name="scoredate" class="form-control pull-right" id="scoredate" >
                                    </div>
                                @if ($errors->has('scoredate'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('scoredate') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <hr>

                        <table class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th class="col-md-1">Fullname</th>
                                    <th class="col-md-1">Division</th>
                                    <th class="col-md-1">Dist 1</th>
                                    <th class="col-md-1">Dist 2</th>
                                    <th class="col-md-1">Dist 3</th>
                                    <th class="col-md-1">Dist 4</th>
                                    <th class="col-md-1">Total Score</th>
                                    <th class="col-md-1">Hits</th>
                                    <th class="col-md-1">10s</th>
                                    <th class="col-md-1">X</th>
                                </tr>
                            </thead>
                            <tbody>
                        @foreach(range(1, 20) as $i)
                            <tr>
                                <td>Steve Clifton</td>
                                <td>Compound</td>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control"></td>
                            </tr>
                        @endforeach
                            </tbody>
                        </table>


                        <button type="submit" class="btn btn-success pull-right" value="update" name="update">
                            Submit Scores
                        </button>

                    </form>
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

