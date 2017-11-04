@section ('dashboard')
    <h1></h1>
@endsection

@extends ('home')

@section ('title')Confirm Archer Relation @endsection

@section ('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            @if(!empty($success))
                                <div class="alert alert-success" style="text-align: center">
                                    {{ $success }}
                                </div>
                            @elseif(!empty($failure))
                                <div class="alert alert-danger" style="text-align: center">
                                    {{ $failure }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

