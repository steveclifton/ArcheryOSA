@section ('dashboard')
    <h1></h1>
@endsection

@extends ('home')

@section ('title')Scoring @endsection

@section ('content')


    <div class="row">
        <div class="col-md-12 dtOnly">
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
                </div>
                <div class="box-body">
                    Please enter only the scores that you wish to be submitted. Leave ALL other fields blank.<br>
                    Hits, 10s and Xs are used to decide winners in the event of a tie. You are encouraged to enter these if scored.
                </div>
                <div class="box-body">
                    <form class="form-horizontal" method="POST" action="{{ route('enterscores', [$eventround[0]->eventroundid, $event->eventid, $event->currentweek])  }}" id="scoringform">
                        {{ csrf_field() }}

                        <div class=" table-condensed table-striped table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="hidden">UserID</th>
                                        <th class="col-md-1 col-xs-1 col-sm-1">Archer</th>
                                        <th class="col-md-1 col-xs-1 col-sm-1">Division</th>

                                        @if (isset($distances['Distance-1']))
                                            <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">{!! $distances['Distance-1'] . $eventround[0]->unit !!}*
                                            </th>
                                        @endif
                                        @if (isset($distances['Distance-2']))
                                            <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">{!! $distances['Distance-2'] . $eventround[0]->unit !!}
                                            </th>
                                        @endif
                                        @if (isset($distances['Distance-3']))
                                            <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">{!! $distances['Distance-3'] . $eventround[0]->unit !!}
                                            </th>
                                        @endif
                                        @if (isset($distances['Distance-4']))
                                            <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">{!! $distances['Distance-4'] . $eventround[0]->unit !!}
                                            </th>
                                        @endif
                                        <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">Total*</th>
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

                                        @if(isset($distances['Distance-1']))
                                            <td>
                                                <input type="text" class="form-control distance" name="distance1-total[]" data-userrow="{!! md5($user->userid) !!}">
                                                <span class="label label-warning addmoredetails" data-row="{{$user->userid}}-1">Add more details</span>
                                                <input type="text" placeholder="Hits" class="form-control hidden" data-id="{{$user->userid}}-1" name="distance1-hits[]">
                                                <input type="text" placeholder="10s" class="form-control hidden" data-id="{{$user->userid}}-1" name="distance1-10[]">
                                                <input type="text" placeholder="Xs" class="form-control hidden" data-id="{{$user->userid}}-1" name="distance1-x[]">
                                            </td>
                                        @endif
                                        @if(isset($distances['Distance-2']))
                                            <td>
                                                <input type="text" class="form-control distance" name="distance2-total[]" data-userrow="{!! md5($user->userid) !!}">
                                                <span class="label label-warning addmoredetails" data-row="{{$user->userid}}-2">Add more details</span>
                                                <input type="text" placeholder="Hits" class="form-control hidden" data-id="{{$user->userid}}-2" name="distance2-hits[]">
                                                <input type="text" placeholder="10s" class="form-control hidden" data-id="{{$user->userid}}-2" name="distance2-10[]">
                                                <input type="text" placeholder="Xs" class="form-control hidden" data-id="{{$user->userid}}-2" name="distance2-x[]">
                                            </td>
                                        @endif
                                        @if(isset($distances['Distance-3']))
                                            <td>
                                                <input type="text" class="form-control distance" name="distance3-total[]" data-userrow="{!! md5($user->userid) !!}">
                                                <span class="label label-warning addmoredetails" data-row="{{$user->userid}}-3">Add more details</span>
                                                <input type="text" placeholder="Hits" class="form-control hidden" data-id="{{$user->userid}}-3" name="distance3-hits[]">
                                                <input type="text" placeholder="10s" class="form-control hidden" data-id="{{$user->userid}}-3" name="distance3-10[]">
                                                <input type="text" placeholder="Xs" class="form-control hidden" data-id="{{$user->userid}}-3" name="distance3-x[]">
                                            </td>
                                        @endif
                                        @if(isset($distances['Distance-4']))
                                            <td>
                                                <input type="text" class="form-control distance" name="distance4-total[]" data-userrow="{!! md5($user->userid) !!}">
                                                <span class="label label-warning addmoredetails" data-row="{{$user->userid}}-4">Add more details</span>
                                                <input type="text" placeholder="Hits" class="form-control hidden" data-id="{{$user->userid}}-4" name="distance4-hits[]">
                                                <input type="text" placeholder="10s" class="form-control hidden" data-id="{{$user->userid}}-4" name="distance4-10[]">
                                                <input type="text" placeholder="Xs" class="form-control hidden" data-id="{{$user->userid}}-4" name="distance4-x[]">
                                            </td>
                                        @endif

                                        <td><input type="text" class="form-control" name="total[]" data-id="total-{!! md5($user->userid) !!}" data-usertotal="{!! md5($user->userid) !!}"></td>
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
            </div>{{-- .box--}}
        </div>


        <div class="col-md-12 mobOnly">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><strong>Scoring Mobile</strong></h3><BR>
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
                </div>
            </div>

            @foreach ($users as $user)
            <div class="box">
                <div class="box-body">
                    <form class="form-horizontal" method="POST" action="{{ route('enterscores', [$eventround[0]->eventroundid, $event->eventid,  $event->currentweek])  }}" id="scoringformmobile">
                        {{ csrf_field() }}

                        <div class=" table-condensed table-striped table-responsive">
                            <table class="table">

                                    <tr>
                                        <th class="hidden">UserID</th>
                                        <td><input type="hidden" name="userid" value="{{$user->userid}}"></td>
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
                                            <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">{!! $distances['Distance-1'] . $eventround[0]->unit !!}</th>
                                            <td>
                                                <input type="text" class="form-control distance" name="distance1-total" data-userrow="{!! md5($user->userid) !!}">
                                                <span class="label label-warning addmoredetails" data-row="{{$user->userid}}-1">Add more details</span>
                                                <input type="text" placeholder="Hits" class="form-control hidden" data-id="{{$user->userid}}-1" name="distance1-hits">
                                                <input type="text" placeholder="10s" class="form-control hidden" data-id="{{$user->userid}}-1" name="distance1-10">
                                                <input type="text" placeholder="Xs" class="form-control hidden" data-id="{{$user->userid}}-1" name="distance1-x">
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($distances['Distance-2']))
                                        <tr>
                                            <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">{!! $distances['Distance-2'] . $eventround[0]->unit !!}</th>
                                            <td>
                                                <input type="text" class="form-control distance" name="distance2-total" data-userrow="{!! md5($user->userid) !!}">
                                                <span class="label label-warning addmoredetails" data-row="{{$user->userid}}-2">Add more details</span>
                                                <input type="text" placeholder="Hits" class="form-control hidden" data-id="{{$user->userid}}-2" name="distance2-hits">
                                                <input type="text" placeholder="10s" class="form-control hidden" data-id="{{$user->userid}}-2" name="distance2-10">
                                                <input type="text" placeholder="Xs" class="form-control hidden" data-id="{{$user->userid}}-2" name="distance2-x">
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($distances['Distance-3']))
                                        <tr>
                                            <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">{!! $distances['Distance-3'] . $eventround[0]->unit !!}</th>
                                            <td>
                                                <input type="text" class="form-control distance" name="distance3-total" data-userrow="{!! md5($user->userid) !!}">
                                                <span class="label label-warning addmoredetails" data-row="{{$user->userid}}-3">Add more details</span>
                                                <input type="text" placeholder="Hits" class="form-control hidden" data-id="{{$user->userid}}-3" name="distance3-hits">
                                                <input type="text" placeholder="10s" class="form-control hidden" data-id="{{$user->userid}}-3" name="distance3-10">
                                                <input type="text" placeholder="Xs" class="form-control hidden" data-id="{{$user->userid}}-3" name="distance3-x">
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($distances['Distance-4']))
                                        <tr>
                                            <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">{!! $distances['Distance-4'] . $eventround[0]->unit !!}</th>
                                            <td>
                                                <input type="text" class="form-control distance" name="distance4-total" data-userrow="{!! md5($user->userid) !!}">
                                                <span class="label label-warning addmoredetails" data-row="{{$user->userid}}-4">Add more details</span>
                                                <input type="text" placeholder="Hits" class="form-control hidden" data-id="{{$user->userid}}-4" name="distance4-hits">
                                                <input type="text" placeholder="10s" class="form-control hidden" data-id="{{$user->userid}}-4" name="distance4-10">
                                                <input type="text" placeholder="Xs" class="form-control hidden" data-id="{{$user->userid}}-4" name="distance4-x">
                                            </td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">Total</th>
                                        <td><input type="text" class="form-control" name="total" data-id="total-{!! md5($user->userid) !!}" data-usertotal="{!! md5($user->userid) !!}"></td>

                                    </tr>

                                    <tr>
                                        <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">Hits</th>
                                        <td><input type="text" class="form-control" name="hit" placeholder="Optional"></td>
                                    </tr>

                                    <tr>
                                        <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">10s</th>
                                        <td><input type="text" class="form-control" name="10" placeholder="Optional"></td>
                                    </tr>

                                    <tr>
                                        <th class="col-md-1 col-xs-1 col-sm-1" style="text-align: center;">X</th>
                                        <td><input type="text" class="form-control" name="x" placeholder="Optional"></td>
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

