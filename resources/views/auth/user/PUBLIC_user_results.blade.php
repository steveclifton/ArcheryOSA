@extends ('home')

@section ('title')Results @endsection

@section ('content')

    <script>
        var collapse_siderbar = true;
    </script>

    @include('includes.session_errors')


    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">

                <div class="box-header with-border">
                    <h3 class="box-title">Results - {!! ucwords(strtolower($user->firstname)) . ' ' . ucwords(strtolower($user->lastname)) !!}</h3>
                </div>

                <div class="box-body">
                    <div class="padding10">
                        <div class="table-responsive">

                            @if (empty($resultssorted))

                                <table class="table table-bordered table-responsive table-striped">
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

                                @foreach($resultssorted as $resultname => $eventresults)

                                    @if ($eventresults[0]->eventtype == 1)
                                        <div>
                                            <caption><a href="{{route('geteventresults', urlencode($resultname))}}">{{$resultname}}</a></caption>
                                            <table class="table removedborders">
                                                <tr>
                                                    <th class="col-md-1 col-xs-1 col-sm-1">Average</th>
                                                    <td>{!! number_format($eventresults[0]->avg_total_score, 0) !!}</td>
                                                </tr>
                                                <tr>
                                                    <th class="col-md-1 col-xs-1 col-sm-1">Total Points</th>
                                                    <td> {{ number_format($eventresults[0]->totalpoints ?? 0, 0)}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    @endif

                                    <table class="table table-bordered table-responsive table-striped resultstables">
                                        <thead>
                                            <tr>
                                                <th class="col-md-1 col-xs-1 col-sm-1 alignCenter">Round</th>
                                                <th class="col-md-1 col-xs-1 col-sm-1 alignCenter">Division</th>

                                                <th class="col-md-1 col-xs-1 col-sm-1 alignCenter">Date</th>
                                                @if (!empty($eventresults[0]->distance1_label))
                                                    <th class="col-md-1 col-xs-1 col-sm-1 alignCenter" >{!! $eventresults[0]->distance1_label . $eventresults[0]->distanceunit!!}
                                                    </th>
                                                @endif
                                                @if (!empty($eventresults[0]->distance2_label))
                                                    <th class="col-md-1 col-xs-1 col-sm-1 alignCenter" >{!! $eventresults[0]->distance2_label . $eventresults[0]->distanceunit!!}
                                                    </th>
                                                @endif
                                                @if (!empty($eventresults[0]->distance3_label))
                                                    <th class="col-md-1 col-xs-1 col-sm-1 alignCenter" >{!! $eventresults[0]->distance3_label . $eventresults[0]->distanceunit!!}
                                                    </th>
                                                @endif
                                                @if (!empty($eventresults[0]->distance4_label))
                                                    <th class="col-md-1 col-xs-1 col-sm-1 alignCenter" >{!! $eventresults[0]->distance4_label . $eventresults[0]->distanceunit!!}
                                                    </th>
                                                @endif

                                                <th class="col-md-1 col-xs-1 col-sm-1 alignCenter">Total</th>
                                                <th class="col-md-1 col-xs-1 col-sm-1 alignCenter">Hits</th>
                                                <th class="col-md-1 col-xs-1 col-sm-1 alignCenter">10+X</th>
                                                <th class="col-md-1 col-xs-1 col-sm-1 alignCenter">X</th>

                                                @if ($eventresults[0]->eventtype == 1)

                                                    <th class="col-md-1 col-xs-1 col-sm-1 alignCenter" style="background: lightblue;">Handicap</th>
                                                    <th class="col-md-1 col-xs-1 col-sm-1 alignCenter" style="background: lightblue;">Points</th>

                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($eventresults as $result)
                                                <tr>

                                                        <td>{{$result->roundname}}</td>
                                                        <td>{{$result->divisionname}}</td>
                                                        <td>{!! date('d M Y', strtotime($result->created_at)) !!}</td>
                                                        @if (!empty($result->distance1_label))
                                                            <td class="alignCenter">{{$result->distance1_total}}</td>
                                                        @endif
                                                        @if(!empty($result->distance2_label))
                                                            <td class="alignCenter">{{$result->distance2_total}}</td>

                                                        @endif
                                                        @if(!empty($result->distance3_label))
                                                            <td class="alignCenter">{{$result->distance3_total}}</td>

                                                        @endif
                                                        @if(!empty($result->distance4_label))
                                                            <td class="alignCenter">{{$result->distance4_total}}</td>

                                                        @endif

                                                        <td class="alignCenter">{{$result->total_score}}</td>
                                                        <td class="alignCenter">{{$result->total_hits}}</td>
                                                        <td class="alignCenter">{{$result->total_10}}</td>
                                                        <td class="alignCenter">{{$result->total_x}}</td>

                                                        @if ($result->eventtype == 1)
                                                            <td class="alignCenter">{!! number_format($result->handicapscore ?? 0, 0) !!}</td>
                                                            <td class="alignCenter">{{$result->weekspoints ?? 0}}</td>
                                                        @endif
                                                    </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection