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
            {{--collapsed-box--}}
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Schedule</h3>
                </div>

                <div class="box-body">
                    <div class="table-responsive">

                        <div class="form-group" id="status" style="padding-bottom: 40px">

                            <div class="col-md-4">
                                <select name="currentweek" class="form-control shootingday">
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($daterange as $date)
                                        <option value="{!! $i; !!}" {!! ( ($_GET['day'] ?? -1) == $i) ? 'selected' : '' !!} >{{ date('d F', strtotime($date)) }}</option>
                                        @php $i++; @endphp
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                            @else

                                @foreach($results as $divisionaname => $divisionresults)


                                    <table class="table table-bordered table-responsive table-striped resultstables">
                                        <caption>{{$divisionaname}}</caption>
                                        <thead>
                                            <tr>
                                                <th class="col-md-2 col-xs-2 col-sm-2">Archer</th>

                                                @if (isset($resultdistances['Distance-1']))
                                                    <th class="col-md-1 col-xs-1 col-sm-1 alignCenter" >{!! $divisionresults[0]->distance1_label . $divisionresults[0]->distanceunit!!}
                                                    </th>
                                                @endif
                                                @if (isset($resultdistances['Distance-2']))
                                                    <th class="col-md-1 col-xs-1 col-sm-1 alignCenter" >{!! $divisionresults[0]->distance2_label . $divisionresults[0]->distanceunit!!}
                                                    </th>
                                                @endif
                                                @if (isset($resultdistances['Distance-3']))
                                                    <th class="col-md-1 col-xs-1 col-sm-1 alignCenter" >{!! $divisionresults[0]->distance3_label . $divisionresults[0]->distanceunit!!}
                                                    </th>
                                                @endif
                                                @if (isset($resultdistances['Distance-4']))
                                                    <th class="col-md-1 col-xs-1 col-sm-1 alignCenter" >{!! $divisionresults[0]->distance4_label . $divisionresults[0]->distanceunit!!}
                                                    </th>
                                                @endif

                                                <th class="col-md-1 col-xs-1 col-sm-1 alignCenter">Total</th>
                                                <th class="col-md-1 col-xs-1 col-sm-1 alignCenter">Hits</th>
                                                <th class="col-md-1 col-xs-1 col-sm-1 alignCenter">10+X</th>
                                                <th class="col-md-1 col-xs-1 col-sm-1 alignCenter">X</th>

                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($divisionresults as $result)

                                            <tr>
                                                <td><a href="{{route('getpublicuserview', $result->username)}}">{{ucwords($result->fullname)}}</a></td>

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