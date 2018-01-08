@extends ('home')

@section ('title')Scoring @endsection

@section ('content')
    @include('includes.session_errors')

    <div class="row">
        @include('includes.events.eventdetails_nav')
    </div>




    <div class="row">
        <div class="col-md-12">
            {{--collapsed-box--}}
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Event Rounds</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">Click to Open &nbsp;
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
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

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th class="hidden-xs">Location</th>
                                    <th>Round</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($eventrounds as $eventround)
                                    <tr onmouseover="this.style.backgroundColor='lightgrey'" onmouseout="this.style.backgroundColor='white'">
                                        <td>{{ ucwords($eventround->roundname) }}</td>
                                        <td class="hidden-xs">{{ ucwords($eventround->location) }}</td>
                                        <td>{{ ucwords($eventround->name) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 dtOnly">
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><strong>Scoring</strong></h3><BR>
                    <h3 class="box-title">{{ ucwords($event->name)}}</h3>

                </div>
                <div class="box-body">
                    Please enter only the scores that you wish to be submitted. Leave ALL other fields blank.<br>
                    Hits, 10s and Xs are used to decide winners in the event of a tie. You are encouraged to enter these if scored.
                </div>
                <hr>
                <div class="box-body">
                    <form class="form-horizontal" method="POST" action="{{ route('entereventscores', [$event->eventid])  }}" id="scoringform">
                        {{ csrf_field() }}

                        @foreach($eventrounds as $eventround)
                            @if ($eventround->usercount < 1)
                                @continue
                            @endif
                            <div class=" table-condensed table-striped table-responsive">
                                <h4>{!! $eventround->roundname !!}</h4>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="hidden">UserID</th>
                                    <th class="col-md-1 col-xs-1 col-sm-1">Archer</th>
                                    <th class="col-md-1 col-xs-1 col-sm-1">Division</th>

                                    @if (!is_null($eventround->dist1))
                                        <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">{!! $eventround->dist1 . $eventround->unit !!}*
                                        </th>
                                    @endif
                                    @if (!is_null($eventround->dist2))
                                        <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">{!! $eventround->dist2 . $eventround->unit !!}
                                        </th>
                                    @endif
                                    @if (!is_null($eventround->dist3))
                                        <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">{!! $eventround->dist3 . $eventround->unit !!}
                                        </th>
                                    @endif
                                    @if (!is_null($eventround->dist4))
                                        <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">{!! $eventround->dist4 . $eventround->unit !!}
                                        </th>
                                    @endif
                                    <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">Total*</th>
                                    <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">Hits</th>
                                    <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">10+X</th>
                                    <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">X</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        @if(!in_array($user->divisionid, $eventround->divisions))
                                            @continue
                                        @endif
                                        <tr>
                                            <td class="hidden">
                                                <input type="hidden" name="userid[{{$user->evententryid}}]" value="{{$user->userid}}">
                                                <input type="hidden" name="evententryid[{{$user->evententryid}}]" value="{{$user->evententryid}}">
                                                <input type="hidden" name="divisionid[{{$user->evententryid}}]" value="{{$user->divisionid}}">
                                            </td>
                                            <td>{{$user->fullname}}</td>
                                            <td>{{$user->divisionname}}</td>

                                            @if(isset($distances['Distance-1']))
                                                <td>
                                                    <input type="text" class="form-control distance" name="distance1[{{$user->evententryid}}][total]" data-formtype="1" data-userrow1="{!! md5($user->evententryid . $user->divisionname) !!}" value="{{ old("distance1.$user->evententryid.total") ?? $user->result->distance1_total ?? '' }}">
                                                    <span class="label label-warning addmoredetails" data-row="{{$user->evententryid}}-1">Add more details</span>
                                                    <input type="text" placeholder="Hits" class="form-control hidden" data-id="{{$user->evententryid}}-1" name="distance1[{{$user->evententryid}}][hits]" value="{{ old("distance1.$user->evententryid.hits") ?? $user->result->distance1_hits ?? '' }}">
                                                    <input type="text" placeholder="10s" class="form-control hidden" data-id="{{$user->evententryid}}-1" name="distance1[{{$user->evententryid}}][10]" value="{{ old("distance1.$user->evententryid.10") ?? $user->result->distance1_10 ?? '' }}">
                                                    <input type="text" placeholder="Xs" class="form-control hidden" data-id="{{$user->evententryid}}-1" name="distance1[{{$user->evententryid}}][x]" value="{{ old("distance1.$user->evententryid.x") ?? $user->result->distance1_x ?? '' }}">
                                                </td>
                                            @endif
                                            @if(isset($distances['Distance-2']))
                                                <td>
                                                    <input type="text" class="form-control distance" name="distance2[{{$user->evententryid}}][total]" data-formtype="1" data-userrow1="{!! md5($user->evententryid . $user->divisionname) !!}" value="{{ old("distance2.$user->evententryid.total") ?? $user->result->distance2_total ?? '' }}">
                                                    <span class="label label-warning addmoredetails" data-row="{{$user->evententryid}}-2">Add more details</span>
                                                    <input type="text" placeholder="Hits" class="form-control hidden" data-id="{{$user->evententryid}}-2" name="distance2[{{$user->evententryid}}][hits]" value="{{ old("distance2.$user->evententryid.hits") ?? $user->result->distance2_hits  ?? ''}}">
                                                    <input type="text" placeholder="10s" class="form-control hidden" data-id="{{$user->evententryid}}-2" name="distance2[{{$user->evententryid}}][10]" value="{{ old("distance2.$user->evententryid.10") ?? $user->result->distance2_10 ?? '' }}">
                                                    <input type="text" placeholder="Xs" class="form-control hidden" data-id="{{$user->evententryid}}-2" name="distance2[{{$user->evententryid}}][x]" value="{{ old("distance2.$user->evententryid.x") ?? $user->result->distance2_x ?? '' }}">
                                                </td>
                                            @endif
                                            @if(isset($distances['Distance-3']))
                                                <td>
                                                    <input type="text" class="form-control distance" name="distance3[{{$user->evententryid}}][total]" data-formtype="1" data-userrow1="{!! md5($user->evententryid . $user->divisionname) !!}" value="{{ old("distance3.$user->evententryid.total") ?? $user->result->distance3_total ?? '' }}">
                                                    <span class="label label-warning addmoredetails" data-row="{{$user->evententryid}}-3">Add more details</span>
                                                    <input type="text" placeholder="Hits" class="form-control hidden" data-id="{{$user->evententryid}}-3" name="distance3[{{$user->evententryid}}][hits]" value="{{ old("distance3.$user->evententryid.hits") ?? $user->result->distance3_hits ?? '' }}">
                                                    <input type="text" placeholder="10s" class="form-control hidden" data-id="{{$user->evententryid}}-3" name="distance3[{{$user->evententryid}}][10]" value="{{ old("distance3.$user->evententryid.10") ?? $user->result->distance3_10 ?? '' }}">
                                                    <input type="text" placeholder="Xs" class="form-control hidden" data-id="{{$user->evententryid}}-3" name="distance3[{{$user->evententryid}}][x]" value="{{ old("distance3.$user->evententryid.x") ?? $user->result->distance3_x ?? '' }}">
                                                </td>
                                            @endif
                                            @if(isset($distances['Distance-4']))
                                                <td>
                                                    <input type="text" class="form-control distance" name="distance4[{{$user->evententryid}}][total]" data-formtype="1" data-userrow1="{!! md5($user->evententryid . $user->divisionname) !!}" value="{{ old("distance4.$user->evententryid.total") ?? $user->result->distance4_total ?? '' }}">
                                                    <span class="label label-warning addmoredetails" data-row="{{$user->evententryid}}-4">Add more details</span>
                                                    <input type="text" placeholder="Hits" class="form-control hidden" data-id="{{$user->evententryid}}-4" name="distance4[{{$user->evententryid}}][hits]" value="{{ old("distance4.$user->evententryid.hits") ?? $user->result->distance4_hits ?? '' }}">
                                                    <input type="text" placeholder="10s" class="form-control hidden" data-id="{{$user->evententryid}}-4" name="distance4[{{$user->evententryid}}][10]" value="{{ old("distance4.$user->evententryid.10") ?? $user->result->distance4_10 ?? '' }}">
                                                    <input type="text" placeholder="Xs" class="form-control hidden" data-id="{{$user->evententryid}}-4" name="distance4[{{$user->evententryid}}][x]" value="{{ old("distance4.$user->evententryid.x") ?? $user->result->distance4_x ?? '' }}">
                                                </td>
                                            @endif

                                            <td><input type="text" class="form-control" name="total[{{$user->evententryid}}][total]" data-id="total-{!! md5($user->evententryid . $user->divisionname) !!}" data-usertotal="{!! md5($user->evententryid . $user->divisionname) !!}" value="{{ old("total.$user->evententryid.total") ?? $user->result->total_score ?? ''}}"></td>
                                            <td><input type="text" class="form-control" name="hits[{{$user->evententryid}}][hits]" placeholder="Optional" value="{{ old("hits.$user->evententryid.hits") ?? $user->result->total_hits ?? ''}}"></td>
                                            <td><input type="text" class="form-control" name="10[{{$user->evententryid}}][10]" placeholder="Optional" value="{{ old("10.$user->evententryid.10") ?? $user->result->total_10 ?? ''}}"></td>
                                            <td><input type="text" class="form-control" name="x[{{$user->evententryid}}][x]" placeholder="Optional" value="{{ old("x.$user->evententryid.x") ?? $user->result->total_x ?? ''}}"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endforeach
                        {{--end foreach here--}}

                        <button type="submit" class="btn btn-success pull-right" value="submit" name="submit">
                            Submit Scores
                        </button>

                    </form>
                </div>
            </div>{{-- .box--}}
        </div>


        <div class="col-md-12 mobOnly">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><strong>Scoring Mobile</strong></h3><BR>
                    <h3 class="box-title">{{ ucwords($event->name)}}</h3>

                </div>
            </div>

            @foreach ($users as $user)
                <div class="box">
                    <div class="box-body">
                        <form class="form-horizontal" method="POST" action="{{ route('enterscores', [$eventrounds[0]->eventroundid, $event->eventid,  $event->currentweek])  }}" id="scoringformmobile">
                            {{ csrf_field() }}

                            <div class=" table-condensed table-striped table-responsive">
                                <table class="table ">

                                    <tr>
                                        <th class="hidden">UserID</th>
                                        <td>
                                            <input type="hidden" name="userid[{{$user->evententryid}}]" value="{{$user->userid}}">
                                            <input type="hidden" name="evententryid[{{$user->evententryid}}]" value="{{$user->evententryid}}">
                                            <input type="hidden" name="divisionid[{{$user->evententryid}}]" value="{{$user->divisionid}}">

                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="col-md-1 col-xs-1 col-sm-1">Archer</th>
                                        <td>{{$user->fullname}}</td>
                                    </tr>

                                    <tr>
                                        <th class="col-md-1 col-xs-1 col-sm-1">Division</th>
                                        <td>{{$user->divisionname}}</td>
                                    </tr>

                                    @if (isset($distances['Distance-1']))
                                        <tr>
                                            <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">{!! $distances['Distance-1'] . $eventrounds[0]->unit !!}</th>
                                            <td>
                                                <input type="text" class="form-control distance" name="distance1[{{$user->evententryid}}][total]" data-formtype="2" data-userrow2="{!! md5($user->evententryid . $user->divisionname) !!}" value="{{ old("distance1.$user->evententryid.total") ?? $user->result->distance1_total ?? '' }}">
                                                <span class="label label-warning addmoredetails" data-row="{{$user->evententryid}}-1">Add more details</span>
                                                <input type="text" placeholder="Hits" class="form-control hidden" data-id="{{$user->evententryid}}-1" name="distance1[{{$user->evententryid}}][hits]" value="{{ old("distance1.$user->evententryid.hits") ?? $user->result->distance1_hits ?? '' }}">
                                                <input type="text" placeholder="10s" class="form-control hidden" data-id="{{$user->evententryid}}-1" name="distance1[{{$user->evententryid}}][10]" value="{{ old("distance1.$user->evententryid.10") ?? $user->result->distance1_10 ?? '' }}">
                                                <input type="text" placeholder="Xs" class="form-control hidden" data-id="{{$user->evententryid}}-1" name="distance1[{{$user->evententryid}}][x]" value="{{ old("distance1.$user->evententryid.x") ?? $user->result->distance1_x ?? '' }}">
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($distances['Distance-2']))
                                        <tr>
                                            <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">{!! $distances['Distance-2'] . $eventrounds[0]->unit !!}</th>
                                            <td>
                                                <input type="text" class="form-control distance" name="distance2[{{$user->evententryid}}][total]" data-formtype="2" data-userrow2="{!! md5($user->evententryid . $user->divisionname) !!}" value="{{ old("distance2.$user->evententryid.total") ?? $user->result->distance2_total ?? '' }}">
                                                <span class="label label-warning addmoredetails" data-row="{{$user->evententryid}}-2">Add more details</span>
                                                <input type="text" placeholder="Hits" class="form-control hidden" data-id="{{$user->evententryid}}-2" name="distance2[{{$user->evententryid}}][hits]" value="{{ old("distance2.$user->evententryid.hits") ?? $user->result->distance2_hits  ?? ''}}">
                                                <input type="text" placeholder="10s" class="form-control hidden" data-id="{{$user->evententryid}}-2" name="distance2[{{$user->evententryid}}][10]" value="{{ old("distance2.$user->evententryid.10") ?? $user->result->distance2_10 ?? '' }}">
                                                <input type="text" placeholder="Xs" class="form-control hidden" data-id="{{$user->evententryid}}-2" name="distance2[{{$user->evententryid}}][x]" value="{{ old("distance2.$user->evententryid.x") ?? $user->result->distance2_x ?? '' }}">
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($distances['Distance-3']))
                                        <tr>
                                            <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">{!! $distances['Distance-3'] . $eventrounds[0]->unit !!}</th>
                                            <td>
                                                <input type="text" class="form-control distance" name="distance3[{{$user->evententryid}}][total]" data-formtype="2" data-userrow2="{!! md5($user->evententryid . $user->divisionname) !!}" value="{{ old("distance3.$user->evententryid.total") ?? $user->result->distance3_total ?? '' }}">
                                                <span class="label label-warning addmoredetails" data-row="{{$user->evententryid}}-3">Add more details</span>
                                                <input type="text" placeholder="Hits" class="form-control hidden" data-id="{{$user->evententryid}}-3" name="distance3[{{$user->evententryid}}][hits]" value="{{ old("distance3.$user->evententryid.hits") ?? $user->result->distance3_hits ?? '' }}">
                                                <input type="text" placeholder="10s" class="form-control hidden" data-id="{{$user->evententryid}}-3" name="distance3[{{$user->evententryid}}][10]" value="{{ old("distance3.$user->evententryid.10") ?? $user->result->distance3_10 ?? '' }}">
                                                <input type="text" placeholder="Xs" class="form-control hidden" data-id="{{$user->evententryid}}-3" name="distance3[{{$user->evententryid}}][x]" value="{{ old("distance3.$user->evententryid.x") ?? $user->result->distance3_x ?? '' }}">
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($distances['Distance-4']))
                                        <tr>
                                            <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">{!! $distances['Distance-4'] . $eventrounds[0]->unit !!}</th>
                                            <td>
                                                <input type="text" class="form-control distance" name="distance4[{{$user->evententryid}}][total]" data-formtype="2" data-userrow2="{!! md5($user->evententryid . $user->divisionname) !!}" value="{{ old("distance4.$user->evententryid.total") ?? $user->result->distance4_total ?? '' }}">
                                                <span class="label label-warning addmoredetails" data-row="{{$user->evententryid}}-4">Add more details</span>
                                                <input type="text" placeholder="Hits" class="form-control hidden" data-id="{{$user->evententryid}}-4" name="distance4[{{$user->evententryid}}][hits]" value="{{ old("distance4.$user->evententryid.hits") ?? $user->result->distance4_hits ?? '' }}">
                                                <input type="text" placeholder="10s" class="form-control hidden" data-id="{{$user->evententryid}}-4" name="distance4[{{$user->evententryid}}][10]" value="{{ old("distance4.$user->evententryid.10") ?? $user->result->distance4_10 ?? '' }}">
                                                <input type="text" placeholder="Xs" class="form-control hidden" data-id="{{$user->evententryid}}-4" name="distance4[{{$user->evententryid}}][x]" value="{{ old("distance4.$user->evententryid.x") ?? $user->result->distance4_x ?? '' }}">
                                            </td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">Total</th>
                                        <td><input type="text" class="form-control" name="total[{{$user->evententryid}}][total]" data-id="total-{!! md5($user->evententryid . $user->divisionname) !!}" data-usertotal="{!! md5($user->evententryid . $user->divisionname) !!}" value="{{ old("total.$user->evententryid.total") ?? $user->result->total_score ?? ''}}"></td>
                                    </tr>

                                    <tr>
                                        <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">Hits</th>
                                        <td><input type="text" class="form-control" name="hits[{{$user->evententryid}}][hits]" placeholder="Optional" value="{{ old("hits.$user->evententryid.hits") ?? $user->result->total_hits ?? ''}}"></td>
                                    </tr>

                                    <tr>
                                        <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">10+X</th>
                                        <td><input type="text" class="form-control" name="10[{{$user->evententryid}}][10]" placeholder="Optional" value="{{ old("10.$user->evententryid.10") ?? $user->result->total_10 ?? ''}}"></td>
                                    </tr>

                                    <tr>
                                        <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">X</th>
                                        <td><input type="text" class="form-control" name="x[{{$user->evententryid}}][x]" placeholder="Optional" value="{{ old("x.$user->evententryid.x") ?? $user->result->total_x ?? ''}}"></td>
                                    </tr>

                                </table>
                            </div>

                            <button type="submit" class="btn btn-success pull-right" value="submit" name="submit">
                                Submit Scores
                            </button>

                        </form>
                    </div>
                </div>
            @endforeach
        </div>{{-- .box--}}
    </div>

    @endsection