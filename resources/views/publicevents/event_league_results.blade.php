@extends ('home')

@section ('title')Results @endsection

@section ('content')

    <script>
        var collapse_siderbar = true;
    </script>

    @include('includes.session_errors')

    <div class="row">
        @include('includes.events.eventdetails_nav')
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Results</h3>
                    </div>

                    <div class="box-body">
                        <div class="padding10">
                            <div class="table-responsive">
                                <div class="row">
                                <br>
                                @if ($event->eventtype == 1)
                                    <div class="col-md-3" style="padding-bottom: 20px">
                                        <select class="week form-control" class="form-control">

                                            <option value="overall">Overall</option>

                                            @foreach (range(1, $event->numberofweeks) as $week)
                                                @php
                                                    $currentweek = $event->selectedweek ?? $event->currentweek;
                                                @endphp
                                                <option @if ( $week == $currentweek) {{'selected'}} @endif value="{{$week}}">
                                                    Week {{$week}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                </div>
                                @if (empty($results))

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
                                @elseif ($event->selectedweek == 'overall')

                                    @foreach($results as $divisionaname => $divisionresults)

                                        <table class="table table-bordered table-responsive table-striped resultstables">
                                            <caption>{{$divisionaname}}</caption>
                                            <thead>
                                            <tr>
                                                <th class="col-md-2 col-xs-3 col-sm-3">Archer</th>

                                                @if ($event->eventtype == 1)
                                                    <th class="col-md-1 col-xs-1 col-sm-1 alignCenter" style="background: lightblue;">Average</th>
                                                    <th class="col-md-1 col-xs-1 col-sm-1 alignCenter" style="background: lightblue;">Top 10 Scores</th>
                                                    <th class="col-md-1 col-xs-1 col-sm-1 alignCenter" style="background: lightblue;">Top 10 Points</th>
                                                @endif
                                            </tr>
                                            </thead>

                                            <tbody>

                                                @foreach($divisionresults as $result)

                                                    <tr style="">
                                                        <td><a href="{{route('getpublicuserview', $result->username)}}">{{ucwords($result->firstname) . ' ' . ucwords($result->lastname)}}</a></td>


                                                        @if ($event->eventtype == 1)
                                                            <td class="alignCenter">{!! number_format($result->avg_total_score, 2) !!}</td>
                                                            <td class="alignCenter">{{$result->top10scores ?? 0}}</td>
                                                            <td class="alignCenter">{{$result->totalpoints ?? 0}}</td>
                                                        @endif
                                                    </tr>
                                                @endforeach

                                            </tbody>

                                        </table>
                                    @endforeach

                                @else

                                    @foreach($results as $divisionaname => $divisionresults)
                                        <table class="table table-bordered table-responsive table-striped resultstables">
                                            <caption style="padding-left: 2%; text-decoration: underline; font-size: 18px">{{$divisionaname}}</caption>
                                            <thead>
                                            <tr>
                                                <th class="col-md-2 col-xs-3 col-sm-3" style="padding-right: 20%">Archer</th>

                                                @if (isset($resultdistances['Distance-1']))
                                                    <th class="col-md-1 hidden-xs hidden-sm alignCenter" >{!! $resultdistances['Distance-1'] . $resultdistances['Distance-1-unit']!!}
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

                                                <th class="col-md-1 col-xs-1 col-sm-1 alignCenter">Total</th>
                                                {{--<th class="col-md-1 hidden-xs hidden-sm alignCenter">Hits</th>--}}
                                                <th class="col-md-1 hidden-xs hidden-sm alignCenter">10+X</th>
                                                <th class="col-md-1 col-xs-1 col-sm-1 alignCenter">X</th>

                                                @if ($event->eventtype == 1)
                                                    <th class="col-md-1 col-xs-1 col-sm-1 alignCenter" style="background: lightblue;">Average</th>
                                                    <th class="col-md-1 col-xs-1 col-sm-1 alignCenter" style="background: lightblue;">Handicap</th>
                                                    <th class="col-md-1 col-xs-1 col-sm-1 alignCenter" style="background: lightblue;">Points</th>
                                                    <th class="col-md-1 col-xs-1 col-sm-1 alignCenter" style="background: lightblue;">Total Points</th>
                                                @endif
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @foreach($divisionresults as $result)
                                                @php
                                                    $colour = '';
                                                    if (($result->weekpoints ?? -1) == 10) {
                                                        $colour = '#ffdc96';
                                                    } else if (($result->weekpoints ?? -1) == 9) {
                                                        $colour = '#d6d5d4';
                                                    } else if (($result->weekpoints ?? -1) == 8){
                                                        $colour = '#e2cabc';
                                                    }
                                                @endphp
                                                <tr style="background: {{$colour}}">
                                                    <td>
                                                        <a href="{{route('getpublicuserview', $result->username)}}">{{ucwords($result->firstname) . ' ' . ucwords($result->lastname)}}</a>
                                                    </td>

                                                    @if (isset($resultdistances['Distance-1']))
                                                        <td class="hidden-xs hidden-sm alignCenter">{{$result->distance1_total}}</td>
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
                                                    {{--<td class="hidden-xs hidden-sm alignCenter">{{$result->total_hits}}</td>--}}
                                                    <td class="alignCenter hidden-xs hidden-sm">{{$result->total_10}}</td>
                                                    <td class="alignCenter">{{$result->total_x}}</td>

                                                    @if ($event->eventtype == 1)
                                                        <td class="alignCenter">{!! number_format($result->avg_total_score, 2) !!}</td>
                                                        <td class="alignCenter">{!! number_format($result->handicapscore ?? 0, 2) !!}</td>
                                                        <td class="alignCenter">{{$result->weekpoints ?? 0}}</td>
                                                        <td class="alignCenter">{{$result->totalpoints ?? 0}}</td>
                                                    @endif
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
    </div>
@endsection