@extends ('home')

@section ('title')Results @endsection

@section ('content')

    @include('includes.session_errors')

    <div class="row">
        @include('includes.events.eventdetails_nav')
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Results</h3>
                </div>

                <div class="box-body">
                    <div class="padding10">
                        <div class="table-responsive">
                            <br>
                            @if ($event->eventtype == 1)
                                <div class="col-md-3" style="padding-bottom: 20px">
                                    <select class="week form-control" class="form-control">
                                        @foreach (range(1, $event->numberofweeks) as $week)
                                            <option @if ( $week == $event->selectedweek ?? $event->currentweek) selected @endif value="{{$week}}">
                                                Week {{$week}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            @if (empty($results))

                                <table class="table table-bordered table-responsive">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="alert alert-warning">
                                                    <span>No Results Yet</span>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                                @else

                                @foreach($results as $divisionaname => $divisionresults)


                                    <table class="table table-bordered table-responsive">
                                        <caption>{{$divisionaname}}</caption>
                                        <thead>
                                            <tr>
                                                <th class="col-md-1 col-xs-1 col-sm-1">Archer</th>
                                                <th class="col-md-1 col-xs-1 col-sm-1">Division</th>

                                                @if (isset($resultdistances['Distance-1']))
                                                    <th class="col-md-1 col-xs-1 col-sm-1 alignCenter" >{!! $resultdistances['Distance-1'] . $resultdistances['Distance-1-unit']!!}
                                                    </th>
                                                @endif
                                                @if (isset($resultdistances['Distance-2']))
                                                    <th class="col-md-1 col-xs-1 col-sm-1 alignCenter" >{!! $resultdistances['Distance-2'] . $resultdistances['Distance-2-unit']!!}
                                                    </th>
                                                @endif
                                                @if (isset($resultdistances['Distance-3']))
                                                    <th class="col-md-1 col-xs-1 col-sm-1 alignCenter" >{!! $resultdistances['Distance-3'] . $resultdistances['Distance-3-unit']!!}
                                                    </th>
                                                @endif
                                                @if (isset($resultdistances['Distance-4']))
                                                    <th class="col-md-1 col-xs-1 col-sm-1 alignCenter" >{!! $resultdistances['Distance-4'] . $resultdistances['Distance-4-unit']!!}
                                                    </th>
                                                @endif
                                                <th class="col-md-1 col-xs-1 col-sm-1 alignCenter" >Total</th>
                                                <th class="col-md-1 col-xs-1 col-sm-1 alignCenter" >Hits</th>
                                                <th class="col-md-1 col-xs-1 col-sm-1 alignCenter" >10s</th>
                                                <th class="col-md-1 col-xs-1 col-sm-1 alignCenter" >X</th>
                                            </tr>
                                        </thead>

                                        <tbody>


                                        @foreach($divisionresults as $result)
                                            <tr>
                                                <td>{{ucwords($result->firstname) . ' ' . ucwords($result->lastname)}}</td>
                                                <td>{{$result->divisonname}}</td>

                                                @if (isset($resultdistances['Distance-1']))
                                                    <td class="alignCenter">{{$result->distance1_total}}</td>
                                                @endif
                                                @if(isset($resultdistances['Distance-2']))
                                                    <td class="alignCenter">{{$result->distance2_total}}</td>

                                                @endif
                                                @if(isset($resultdistances['Distance-3']))
                                                    <td class="alignCenter">{{$result->distance3_total}}</td>

                                                @endif
                                                @if(isset($resultdistances['Distance-4']))
                                                    <td class="alignCenter">{{$result->distance4_total}}</td>

                                                @endif

                                                <td class="alignCenter">{{$result->total_score}}</td>
                                                <td class="alignCenter">{{$result->total_hits}}</td>
                                                <td class="alignCenter">{{$result->total_10}}</td>
                                                <td class="alignCenter">{{$result->total_x}}</td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection