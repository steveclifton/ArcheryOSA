    {{ csrf_field() }}

    <input type="text" name="userid" hidden value="{{ $archerentry->user->userid }}">

    <div class="form-group">
        @php
            $status = 'Not Entered';
            if (!empty($archerentry->eventregistration)) {
                $status = $archerentry->eventregistration->first()->status;
            }
        @endphp

        <label for="name" class="col-md-4 control-label">Entry Status</label>

        <div class="col-md-6">
            <input id="name" type="text" class="form-control" name="name" value="{{$status}}" disabled readonly="">
        </div>
    </div>


    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        <label for="name" class="col-md-4 control-label">Name</label>

        <div class="col-md-6">
            @php
                $name = ucwords($archerentry->user->firstname) . " " . ucwords($archerentry->user->lastname);
                if (!empty($archerentry->eventregistration)) {
                    $name = $archerentry->eventregistration->first()->fullname;
                }
            @endphp
            <input id="name" type="text" class="form-control" name="name" value="{!! old('name') ??  $name !!}" required autofocus>

            @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group {{ $errors->has('clubid') ? ' has-error' : '' }}" id="club">
        <label for="organisation" class="col-md-4 control-label">Club</label>

        <div class="col-md-6">
            <select name="clubid" class="form-control" id="clubselect" required autofocus>
                <option value="0">None</option>

                @foreach ($clubs as $club)
                    <option value="{{$club->clubid}}" {!! $club->clubid == $archerentry->eventregistration->first()->clubid ?? '' ? 'selected':'' !!}>{{$club->name}}</option>
                @endforeach

            </select>
            @if ($errors->has('clubid'))
                <span class="help-block">
                    <strong>{{ $errors->first('clubid') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <label for="name" class="col-md-4 control-label">Email</label>

        <div class="col-md-6">

            @php
                $email = $archerentry->user->email ?? Auth::user()->email;
                if (!empty($archerentry->eventregistration)) {
                    $email = $archerentry->eventregistration->first()->email;
                }
            @endphp

            <input id="email" type="text" class="form-control" name="email" value="{{  $email }}" required autofocus>

            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
    </div>

    @if ($event->dob == 1)
        <div class="form-group {{ $errors->has('dateofbirth') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label">Date of Birth</label>

            <div class="col-md-6">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" name="dateofbirth" class="form-control pull-right" id="dateofbirth" >
                </div>
                @if ($errors->has('dateofbirth'))
                    <span class="help-block">
                        <strong>{{ $errors->first('dateofbirth') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <script src="{{URL::asset('bower_components/moment/min/moment.min.js')}}"></script>
        <script src="{{URL::asset('bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
        <!-- datepicker -->
        <script src="{{URL::asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
        <?php
            $date = time();
            if (!empty($archerentry->eventregistration)) {
                $date = strtotime($archerentry->eventregistration->first()->dateofbirth);
            }
        ?>
        <script>
            $(function () {
                //Initialize Select2 Elements
                $('.select2').select2();

                $('#dateofbirth').datepicker({
                    startView: 2,
                    format: 'dd/mm/yyyy',
                    autoclose: true

                }).datepicker("update", "<?= date('d/m/Y', $date) ?>"); //

            });

        </script>
    @endif


    @if ($event->multipledivisions == 0)
        <div class="form-group {{ $errors->has('division') ? ' has-error' : '' }}" id="organisation">
            <label for="organisation" class="col-md-4 control-label">Division</label>

            <div class="col-md-6">
                <select name="divisions" class="form-control" id="organisation" required>
                    <option value="" disabled selected>Please select</option>

                    @foreach ($divisions as $division)
                        <option value="{{$division->divisionid}}" <?= (in_array($division->divisionid, $archerentry->userdivisions)) ? 'selected' : '' ?>>{{$division->name}}</option>
                    @endforeach

                </select>
                @if ($errors->has('division'))
                    <span class="help-block">
                        <strong>{{ $errors->first('division') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    @else
        <div class="form-group {{ $errors->has('division') ? ' has-error' : '' }}" id="organisation">
            <label for="organisation" class="col-md-4 control-label">Division</label>

            <div class="col-md-6" style="overflow-y:scroll; height:200px; margin-bottom:10px;">

                @foreach ($divisions as $division)
                    <input class="form-check-input" type="checkbox" name="divisions[]" value="{{$division->divisionid}}" <?= (in_array($division->divisionid, $archerentry->userdivisions)) ? 'checked' : '' ?>>
                    <label class="form-check-label" style="margin-left: 10px" >{{$division->name}}</label><br>
                @endforeach
            </div>
        </div>
    @endif

    @if ($event->ignoregenders == 0)
        <div class="form-group {{ $errors->has('division') ? ' has-error' : '' }}" id="gender">

            <label for="gender" class="col-md-4 control-label">Gender</label>
            <div class="col-md-6">
                @php
                    $gender = '';
                    if (!empty($archerentry->eventregistration)) {
                        $gender = $archerentry->eventregistration->first()->gender;
                    }
                @endphp
                <input class="form-check-input" type="radio" name="gender" value="M" {!! ($gender == 'M' ? 'checked' : '') !!}>
                <label class="form-check-label" style="margin-left: 10px">Mens</label><br>
                <input class="form-check-input" type="radio" name="gender" value="F" {!! ($gender == 'F' ? 'checked' : '') !!}>
                <label class="form-check-label" style="margin-left: 10px">Womens</label>
            </div>

        </div>
    @endif

    <div class="form-group {{ $errors->has('division') ? ' has-error' : '' }}" id="Rounds">

        @if ($event->eventtype == 1)
            <label for="Rounds" class="col-md-4 control-label">Round</label>
            <div class="col-md-6">
                @foreach ($eventround as $round)
                    <input class="form-check-input" type="checkbox" checked disabled name="eventroundid" value="{{$round->eventroundid}}" >
                    <label class="form-check-label" style="margin-left: 10px">{!! $round->name !!}</label>
                @endforeach
            </div>

        @else
            <label for="Rounds" class="col-md-4 control-label">Rounds</label>
            <div class="col-md-6">
                @foreach ($eventround as $round)
                    <input class="form-check-input" type="checkbox" name="eventroundid[]" value="{{$round->eventroundid}}" <?= (in_array($round->eventroundid, $archerentry->usereventrounds)) ? 'checked' : '' ?> >
                    <label class="form-check-label" style="margin-left: 10px" >{!! date('d F', strtotime($round->date)) . " - " .$round->name!!}</label><br>
                @endforeach
            </div>
        @endif
    </div>

    <div class="form-group{{ $errors->has('membershipcode') ? ' has-error' : '' }}">
        <label for="name" class="col-md-4 control-label">{{$organisationname}} Membership Code</label>

        <div class="col-md-6">
            @php
                $membershipcode = $archerentry->user->membershipcode ?? '';
                if (!empty($archerentry->eventregistration)) {
                    $membershipcode = $archerentry->eventregistration->first()->membershipcode;
                }
            @endphp

            <input id="membershipcode" type="text" class="form-control" name="membershipcode" value="{{$membershipcode}}">

            @if ($errors->has('membershipcode'))
                <span class="help-block">
                    <strong>{{ $errors->first('membershipcode') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
        <label for="name" class="col-md-4 control-label">Phone</label>

        <div class="col-md-6">

            @php
                $phone = $archerentry->user->phone ?? '';
                if (!empty($archerentry->eventregistration)) {
                    $phone = $archerentry->eventregistration->first()->phone;
                }
            @endphp
            <input id="phone" type="text" class="form-control" name="phone" value="{{$phone}}" >

            @if ($errors->has('phone'))
                <span class="help-block">
                    <strong>{{ $errors->first('phone') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
        <label for="address" class="col-md-4 control-label">Address</label>

        <div class="col-md-6">
            @php
                $address = $archerentry->user->address ?? '';
                if (!empty($archerentry->eventregistration)) {
                    $address = $archerentry->eventregistration->first()->address;
                }
            @endphp

            <textarea rows="3" id="address" type="text" class="form-control" name="address" >{{ $address }}</textarea>
            @if ($errors->has('address'))
                <span class="help-block">
                    <strong>{{ $errors->first('address') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('notes') ? ' has-error' : '' }}">
        <label for="address" class="col-md-4 control-label">Notes</label>

        <div class="col-md-6">
            @php
                $notes = $archerentry->user->notes ?? '';
                if (!empty($archerentry->eventregistration)) {
                    $notes = $archerentry->eventregistration->first()->notes;
                }
            @endphp

            <textarea rows="5" id="address" type="text" class="form-control" name="notes" >{!! $notes !!}</textarea>
            @if ($errors->has('notes'))
                <span class="help-block">
                    <strong>{{ $errors->first('notes') }}</strong>
                </span>
            @endif
        </div>
    </div>




