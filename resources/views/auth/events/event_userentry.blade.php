@section ('dashboard')
    <h1></h1>
@endsection

@extends ('home')

@section ('title')User Entry @endsection

@section ('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            @include('includes.session_errors')
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">User Entry
                    <a href="{!! route('updateeventview', $event->url) !!}">
                        <button type="submit" class="btn btn-default pull-right" id="addevent">
                            <i class="fa fa-backward" > Back</i>
                        </button>
                    </a>
                </div>





                <div class="panel-body">

                    <form class="form-horizontal" method="POST" action="{{ route('updateuserentry', ['eventurl' => $event->url]) }}" >
                        {{ csrf_field() }}


                        <input type="text" name="eventid" hidden value="{{$event->eventid}}">

                        <div id="formdata">
                            @include('includes.forms.entryform')
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-warning" value="create" name="submit">
                                    Update
                                </button>

                                <button type="submit" class="btn btn-danger pull-right" value="remove" name="submit" id="deleteBtn">
                                    Remove Entry
                                </button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
    {{-- </div> --}}



@endsection

