@section ('dashboard')
    <h1></h1>
@endsection

@include('layouts.title', ['title'=>'Divisions'])

@extends ('home')

@section ('content')

    {{-- <div class="container"> --}}
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="box">

                <div class="box-header">

                    <h3 class="box-title">Divisions</h3>

                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 300px;">
                            <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                            <div class="input-group-btn " style="padding-left: 20px">
                                <a href="{{route('createdivisionview')}}">
                                    <button type="submit" class="btn btn-default" id="addevent">
                                        <i class="fa fa-plus-square"> Add</i>
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Visible</th>
                        </tr>

                        @foreach($divisions as $division)
                            <tr>
                                <td>{{$division->divisionid}}</td>
                                <td><a href="{{ route('updatedivisionview', urlencode($division->name)) }}">{{$division->name}}</a></td>
                                <td>{!! mb_substr($division->description, 0, 60) !!}..</td>
                                <td>{!! ($division->visible) ? '<i class="fa fa-check"></i>' : '';  !!}</td>
                            </tr>
                        @endforeach



                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
    {{-- </div> --}}

@endsection

