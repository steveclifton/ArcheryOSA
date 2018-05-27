@extends ('home')

@section ('title')Scoring @endsection

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
        {{--dtOnly--}}
        <div class="col-md-12 ">
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><strong>Scoring</strong></h3><BR>
                    <h3 class="box-title">{{ ucwords($event->name)}}</h3>

                </div>
                <div class="box-body">
                    Please enter only the scores that you wish to be submitted. Leave ALL other fields blank.<br>
                    Hits, 10s and Xs are used to decide winners in the event of a tie. You are encouraged to enter these if scored.<br>
                    Leave the fields blank to ignore scores

                </div>
                <hr>

                @if(empty($users))
                    <div class="box-body">
                    <h4>No entries so far</h4>
                    </div>
                @else
                <div class="box-body">
                    <form class="form-horizontal" method="POST" action="{{ route('entereventscores', [$event->eventid])  }}" id="scoringform">
                        {{ csrf_field() }}

                        @foreach($users as $key => $user)
                            <div class=" table-condensed table-striped table-responsive">
                                <h4>{!! $key !!}</h4>
                                <table class="table">
                                    <thead>
                                        <tr>
                                        <th class="hidden">UserID</th>
                                        <th class="col-md-1 col-xs-1 col-sm-1">Archer</th>
                                        {{--<th class="col-md-1 col-xs-1 col-sm-1">Division</th>--}}

                                        @if (!empty($user[0]->eventroundobj->dist1))
                                            <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">{!! $user[0]->eventroundobj->dist1 . $user[0]->eventroundobj->unit !!}*
                                            </th>
                                        @endif
                                        @if (!empty($user[0]->eventroundobj->dist2))
                                            <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">{!! $user[0]->eventroundobj->dist2 . $user[0]->eventroundobj->unit !!}*
                                            </th>
                                        @endif
                                        @if (!empty($user[0]->eventroundobj->dist3))
                                            <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">{!! $user[0]->eventroundobj->dist3 . $user[0]->eventroundobj->unit !!}*
                                            </th>
                                        @endif
                                        @if (!empty($user[0]->eventroundobj->dist4))
                                            <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">{!! $user[0]->eventroundobj->dist4 . $user[0]->eventroundobj->unit !!}*
                                            </th>
                                        @endif
                                        <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">Total*</th>
                                        <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">Hits</th>
                                        <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">10+X</th>
                                        <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">X</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user as $u)
                                            <tr>
                                                <td class="hidden">
                                                    <input type="hidden" name="userid[{{$u->evententryid}}]" value="{{$u->userid}}">
                                                    <input type="hidden" name="evententryid[{{$u->evententryid}}]" value="{{$u->evententryid}}">
                                                    <input type="hidden" name="divisionid[{{$u->evententryid}}]" value="{{$u->divisionid}}">
                                                </td>
                                                <td>{{$u->fullname}}</td>
                                                {{--<td>{{$u->divisionname}}</td>--}}

                                                @if (!empty($user[0]->eventroundobj->dist1))
                                                    <td>
                                                        <input type="text" class="form-control distance" name="distance1[{{$u->evententryid}}][total]" data-formtype="1" data-userrow1="{!! md5($u->evententryid . $u->divisionname) !!}" value="{{ old("distance1.$u->evententryid.total") ?? $u->result->distance1_total ?? '' }}">
                                                        <span class="label label-warning addmoredetails" data-row="{{$u->evententryid}}-1">Add more details</span>

                                                        <input type="text" placeholder="Hits" class="form-control hidden" data-id="{{$u->evententryid}}-1" name="distance1[{{$u->evententryid}}][hits]" value="{!!  old("distance1.$u->evententryid.hits") ?? !empty($u->result->distance1_hits) ? $u->result->distance1_hits : '' !!}">
                                                        <input type="text" placeholder="10s" class="form-control hidden" data-id="{{$u->evententryid}}-1" name="distance1[{{$u->evententryid}}][10]" value="{!! old("distance1.$u->evententryid.10") ?? !empty($u->result->distance1_10) ? $u->result->distance1_10 : ''  !!}">
                                                        <input type="text" placeholder="Xs" class="form-control hidden" data-id="{{$u->evententryid}}-1" name="distance1[{{$u->evententryid}}][x]" value="{!! old("distance1.$u->evententryid.x") ?? !empty($u->result->distance1_x) ? $u->result->distance1_x : ''  !!}">
                                                    </td>
                                                @endif
                                                @if (!empty($user[0]->eventroundobj->dist2))
                                                    <td>
                                                        <input type="text" class="form-control distance" name="distance2[{{$u->evententryid}}][total]" data-formtype="1" data-userrow1="{!! md5($u->evententryid . $u->divisionname) !!}" value="{{ old("distance2.$u->evententryid.total") ?? $u->result->distance2_total ?? '' }}">
                                                        <span class="label label-warning addmoredetails" data-row="{{$u->evententryid}}-2">Add more details</span>
                                                        <input type="text" placeholder="Hits" class="form-control hidden" data-id="{{$u->evententryid}}-2" name="distance2[{{$u->evententryid}}][hits]" value="{!!  old("distance2.$u->evententryid.hits") ?? !empty($u->result->distance2_hits) ? $u->result->distance2_hits : '' !!}">
                                                        <input type="text" placeholder="10s" class="form-control hidden" data-id="{{$u->evententryid}}-2" name="distance2[{{$u->evententryid}}][10]" value="{!! old("distance2.$u->evententryid.10") ?? !empty($u->result->distance2_10) ? $u->result->distance2_10 : ''!!}">
                                                        <input type="text" placeholder="Xs" class="form-control hidden" data-id="{{$u->evententryid}}-2" name="distance2[{{$u->evententryid}}][x]" value="{!! old("distance2.$u->evententryid.x") ?? !empty($u->result->distance2_x) ? $u->result->distance2_x : '' !!}">
                                                    </td>
                                                @endif
                                                @if (!empty($user[0]->eventroundobj->dist3))
                                                    <td>
                                                        <input type="text" class="form-control distance" name="distance3[{{$u->evententryid}}][total]" data-formtype="1" data-userrow1="{!! md5($u->evententryid . $u->divisionname) !!}" value="{{ old("distance3.$u->evententryid.total") ?? $u->result->distance3_total ?? '' }}">
                                                        <span class="label label-warning addmoredetails" data-row="{{$u->evententryid}}-3">Add more details</span>
                                                        <input type="text" placeholder="Hits" class="form-control hidden" data-id="{{$u->evententryid}}-3" name="distance3[{{$u->evententryid}}][hits]" value="{!! old("distance3.$u->evententryid.hits") ?? !empty($u->result->distance3_hits) ? $u->result->distance3_hits : ''!!}">
                                                        <input type="text" placeholder="10s" class="form-control hidden" data-id="{{$u->evententryid}}-3" name="distance3[{{$u->evententryid}}][10]" value="{!! old("distance3.$u->evententryid.10") ?? !empty($u->result->distance3_10) ? $u->result->distance3_10 : '' !!}">
                                                        <input type="text" placeholder="Xs" class="form-control hidden" data-id="{{$u->evententryid}}-3" name="distance3[{{$u->evententryid}}][x]" value="{!! old("distance3.$u->evententryid.x") ?? !empty($u->result->distance3_x) ? $u->result->distance3_x : '' !!}">
                                                    </td>
                                                @endif
                                                @if (!empty($user[0]->eventroundobj->dist4))
                                                    <td>
                                                        <input type="text" class="form-control distance" name="distance4[{{$u->evententryid}}][total]" data-formtype="1" data-userrow1="{!! md5($u->evententryid . $u->divisionname) !!}" value="{{ old("distance4.$u->evententryid.total") ?? $u->result->distance4_total ?? '' }}">
                                                        <span class="label label-warning addmoredetails" data-row="{{$u->evententryid}}-4">Add more details</span>
                                                        <input type="text" placeholder="Hits" class="form-control hidden" data-id="{{$u->evententryid}}-4" name="distance4[{{$u->evententryid}}][hits]" value="{!! old("distance4.$u->evententryid.hits") ?? !empty($u->result->distance4_hits) ? $u->result->distance4_hits : '' !!}">
                                                        <input type="text" placeholder="10s" class="form-control hidden" data-id="{{$u->evententryid}}-4" name="distance4[{{$u->evententryid}}][10]" value="{!! old("distance4.$u->evententryid.10") ?? !empty($u->result->distance4_10) ? $u->result->distance4_10 : '' !!}">
                                                        <input type="text" placeholder="Xs" class="form-control hidden" data-id="{{$u->evententryid}}-4" name="distance4[{{$u->evententryid}}][x]" value="{!! old("distance4.$u->evententryid.x") ?? !empty($u->result->distance4_x) ? $u->result->distance4_x  : '' !!}">
                                                    </td>
                                                @endif


                                                <td>
                                                    <input type="text" class="form-control" name="total[{{$u->evententryid}}][total]" data-id="total-{!! md5($u->evententryid . $u->divisionname) !!}" data-usertotal="{!! md5($u->evententryid . $u->divisionname) !!}" value="{{ old("total.$u->evententryid.total") ?? $u->result->total_score ?? ''}}">
                                                </td>
                                                <td><input type="text" class="form-control" name="hits[{{$u->evententryid}}][hits]" placeholder="Optional" value="{{ old("hits.$u->evententryid.hits") ?? $u->result->total_hits ?? ''}}"></td>
                                                <td><input type="text" class="form-control" name="10[{{$u->evententryid}}][10]" placeholder="Optional" value="{{ old("10.$u->evententryid.10") ?? $u->result->total_10 ?? ''}}"></td>
                                                <td><input type="text" class="form-control" name="x[{{$u->evententryid}}][x]" placeholder="Optional" value="{{ old("x.$u->evententryid.x") ?? $u->result->total_x ?? ''}}"></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach


                        {{--end foreach here--}}
                        @if(!empty($users))
                            <button type="submit" class="btn btn-success pull-right" value="submit" name="submit">
                                Submit Scores
                            </button>
                        @endif

                    </form>
                </div>
                @endif
            </div>{{-- .box--}}
        </div>


        {{--<div class="col-md-12 mobOnly">--}}
            {{--<div class="box">--}}
                {{--<div class="box-header">--}}
                    {{--<h3 class="box-title"><strong>Scoring Mobile</strong></h3><BR>--}}
                    {{--<h3 class="box-title">{{ ucwords($event->name)}}</h3>--}}

                {{--</div>--}}
            {{--</div>--}}

            {{--@foreach ($users as $user)--}}
                {{--<div class="box">--}}
                    {{--<div class="box-body">--}}
                        {{--<form class="form-horizontal" method="POST" action="{{ route('entereventscores', [$event->eventid])   }}" id="scoringformmobile">--}}
                            {{--{{ csrf_field() }}--}}

                            {{--<div class=" table-condensed table-striped table-responsive">--}}
                                {{--<table class="table ">--}}

                                    {{--<tr>--}}
                                        {{--<th class="hidden">UserID</th>--}}
                                        {{--<td>--}}
                                            {{--<input type="hidden" name="userid[{{$user->evententryid}}]" value="{{$user->userid}}">--}}
                                            {{--<input type="hidden" name="evententryid[{{$user->evententryid}}]" value="{{$user->evententryid}}">--}}
                                            {{--<input type="hidden" name="divisionid[{{$user->evententryid}}]" value="{{$user->divisionid}}">--}}

                                        {{--</td>--}}
                                    {{--</tr>--}}

                                    {{--<tr>--}}
                                        {{--<th class="col-md-1 col-xs-1 col-sm-1">Archer</th>--}}
                                        {{--<td>{{$user->fullname}}</td>--}}
                                    {{--</tr>--}}

                                    {{--<tr>--}}
                                        {{--<th class="col-md-1 col-xs-1 col-sm-1">Division</th>--}}
                                        {{--<td>{{$user->divisionname}}</td>--}}
                                    {{--</tr>--}}

                                    {{--@if (isset($distances['Distance-1']))--}}
                                        {{--<tr>--}}
                                            {{--<th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">{!! $distances['Distance-1'] . $eventrounds[0]->unit !!}</th>--}}
                                            {{--<td>--}}
                                                {{--<input type="text" class="form-control distance" name="distance1[{{$user->evententryid}}][total]" data-formtype="2" data-userrow2="{!! md5($user->evententryid . $user->divisionname) !!}" value="{{ old("distance1.$user->evententryid.total") ?? $user->result->distance1_total ?? '' }}">--}}
                                                {{--<span class="label label-warning addmoredetails" data-row="{{$user->evententryid}}-1">Add more details</span>--}}
                                                {{--<input type="text" placeholder="Hits" class="form-control hidden" data-id="{{$user->evententryid}}-1" name="distance1[{{$user->evententryid}}][hits]" value="{{ old("distance1.$user->evententryid.hits") ?? $user->result->distance1_hits ?? '' }}">--}}
                                                {{--<input type="text" placeholder="10s" class="form-control hidden" data-id="{{$user->evententryid}}-1" name="distance1[{{$user->evententryid}}][10]" value="{{ old("distance1.$user->evententryid.10") ?? $user->result->distance1_10 ?? '' }}">--}}
                                                {{--<input type="text" placeholder="Xs" class="form-control hidden" data-id="{{$user->evententryid}}-1" name="distance1[{{$user->evententryid}}][x]" value="{{ old("distance1.$user->evententryid.x") ?? $user->result->distance1_x ?? '' }}">--}}
                                            {{--</td>--}}
                                        {{--</tr>--}}
                                    {{--@endif--}}
                                    {{--@if (isset($distances['Distance-2']))--}}
                                        {{--<tr>--}}
                                            {{--<th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">{!! $distances['Distance-2'] . $eventrounds[0]->unit !!}</th>--}}
                                            {{--<td>--}}
                                                {{--<input type="text" class="form-control distance" name="distance2[{{$user->evententryid}}][total]" data-formtype="2" data-userrow2="{!! md5($user->evententryid . $user->divisionname) !!}" value="{{ old("distance2.$user->evententryid.total") ?? $user->result->distance2_total ?? '' }}">--}}
                                                {{--<span class="label label-warning addmoredetails" data-row="{{$user->evententryid}}-2">Add more details</span>--}}
                                                {{--<input type="text" placeholder="Hits" class="form-control hidden" data-id="{{$user->evententryid}}-2" name="distance2[{{$user->evententryid}}][hits]" value="{{ old("distance2.$user->evententryid.hits") ?? $user->result->distance2_hits  ?? ''}}">--}}
                                                {{--<input type="text" placeholder="10s" class="form-control hidden" data-id="{{$user->evententryid}}-2" name="distance2[{{$user->evententryid}}][10]" value="{{ old("distance2.$user->evententryid.10") ?? $user->result->distance2_10 ?? '' }}">--}}
                                                {{--<input type="text" placeholder="Xs" class="form-control hidden" data-id="{{$user->evententryid}}-2" name="distance2[{{$user->evententryid}}][x]" value="{{ old("distance2.$user->evententryid.x") ?? $user->result->distance2_x ?? '' }}">--}}
                                            {{--</td>--}}
                                        {{--</tr>--}}
                                    {{--@endif--}}
                                    {{--@if (isset($distances['Distance-3']))--}}
                                        {{--<tr>--}}
                                            {{--<th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">{!! $distances['Distance-3'] . $eventrounds[0]->unit !!}</th>--}}
                                            {{--<td>--}}
                                                {{--<input type="text" class="form-control distance" name="distance3[{{$user->evententryid}}][total]" data-formtype="2" data-userrow2="{!! md5($user->evententryid . $user->divisionname) !!}" value="{{ old("distance3.$user->evententryid.total") ?? $user->result->distance3_total ?? '' }}">--}}
                                                {{--<span class="label label-warning addmoredetails" data-row="{{$user->evententryid}}-3">Add more details</span>--}}
                                                {{--<input type="text" placeholder="Hits" class="form-control hidden" data-id="{{$user->evententryid}}-3" name="distance3[{{$user->evententryid}}][hits]" value="{{ old("distance3.$user->evententryid.hits") ?? $user->result->distance3_hits ?? '' }}">--}}
                                                {{--<input type="text" placeholder="10s" class="form-control hidden" data-id="{{$user->evententryid}}-3" name="distance3[{{$user->evententryid}}][10]" value="{{ old("distance3.$user->evententryid.10") ?? $user->result->distance3_10 ?? '' }}">--}}
                                                {{--<input type="text" placeholder="Xs" class="form-control hidden" data-id="{{$user->evententryid}}-3" name="distance3[{{$user->evententryid}}][x]" value="{{ old("distance3.$user->evententryid.x") ?? $user->result->distance3_x ?? '' }}">--}}
                                            {{--</td>--}}
                                        {{--</tr>--}}
                                    {{--@endif--}}
                                    {{--@if (isset($distances['Distance-4']))--}}
                                        {{--<tr>--}}
                                            {{--<th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">{!! $distances['Distance-4'] . $eventrounds[0]->unit !!}</th>--}}
                                            {{--<td>--}}
                                                {{--<input type="text" class="form-control distance" name="distance4[{{$user->evententryid}}][total]" data-formtype="2" data-userrow2="{!! md5($user->evententryid . $user->divisionname) !!}" value="{{ old("distance4.$user->evententryid.total") ?? $user->result->distance4_total ?? '' }}">--}}
                                                {{--<span class="label label-warning addmoredetails" data-row="{{$user->evententryid}}-4">Add more details</span>--}}
                                                {{--<input type="text" placeholder="Hits" class="form-control hidden" data-id="{{$user->evententryid}}-4" name="distance4[{{$user->evententryid}}][hits]" value="{{ old("distance4.$user->evententryid.hits") ?? $user->result->distance4_hits ?? '' }}">--}}
                                                {{--<input type="text" placeholder="10s" class="form-control hidden" data-id="{{$user->evententryid}}-4" name="distance4[{{$user->evententryid}}][10]" value="{{ old("distance4.$user->evententryid.10") ?? $user->result->distance4_10 ?? '' }}">--}}
                                                {{--<input type="text" placeholder="Xs" class="form-control hidden" data-id="{{$user->evententryid}}-4" name="distance4[{{$user->evententryid}}][x]" value="{{ old("distance4.$user->evententryid.x") ?? $user->result->distance4_x ?? '' }}">--}}
                                            {{--</td>--}}
                                        {{--</tr>--}}
                                    {{--@endif--}}

                                    {{--<tr>--}}
                                        {{--<th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">Total</th>--}}
                                        {{--<td><input type="text" class="form-control" name="total[{{$user->evententryid}}][total]" data-id="total-{!! md5($user->evententryid . $user->divisionname) !!}" data-usertotal="{!! md5($user->evententryid . $user->divisionname) !!}" value="{{ old("total.$user->evententryid.total") ?? $user->result->total_score ?? ''}}"></td>--}}
                                    {{--</tr>--}}

                                    {{--<tr>--}}
                                        {{--<th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">Hits</th>--}}
                                        {{--<td><input type="text" class="form-control" name="hits[{{$user->evententryid}}][hits]" placeholder="Optional" value="{{ old("hits.$user->evententryid.hits") ?? $user->result->total_hits ?? ''}}"></td>--}}
                                    {{--</tr>--}}

                                    {{--<tr>--}}
                                        {{--<th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">10+X</th>--}}
                                        {{--<td><input type="text" class="form-control" name="10[{{$user->evententryid}}][10]" placeholder="Optional" value="{{ old("10.$user->evententryid.10") ?? $user->result->total_10 ?? ''}}"></td>--}}
                                    {{--</tr>--}}

                                    {{--<tr>--}}
                                        {{--<th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">X</th>--}}
                                        {{--<td><input type="text" class="form-control" name="x[{{$user->evententryid}}][x]" placeholder="Optional" value="{{ old("x.$user->evententryid.x") ?? $user->result->total_x ?? ''}}"></td>--}}
                                    {{--</tr>--}}

                                {{--</table>--}}
                            {{--</div>--}}

                            {{--<button type="submit" class="btn btn-success pull-right" value="submit" name="submit">--}}
                                {{--Submit Scores--}}
                            {{--</button>--}}

                        {{--</form>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--@endforeach--}}
        {{--</div>--}}{{-- .box--}}
    </div>
    <script src="{{URL::asset('js/scoring.js')}}"></script>
@endsection