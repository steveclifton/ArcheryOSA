@if(session()->has('message'))
    <div class="alert alert-success">
        {!! session()->get('message') !!}
    </div>
@elseif (session()->has('failure'))
    <div class="alert alert-danger">
        {!! session()->get('failure') !!}
    </div>
@endif