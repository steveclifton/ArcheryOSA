@include('layouts.title', ['title'=>'Events'])

@extends ('home')

@section ('content')

    {{-- <div class="container"> --}}
    <div class="row">
        <div class="col-xs-12">
            <div class="box">

                <div class="box-header">

                    <h3 class="box-title">Responsive Hover Table</h3>

                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 300px;">
                            <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                            <div class="input-group-btn" style="padding-left: 20px">
                                <a href="{{route('createevent')}}">
                                <button type="submit" class="btn btn-default" id="addevent">
                                    <i class="fa fa-plus-square"></i>
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
                            <th>Status</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Name</th>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td><span class="label label-success">Approved</span></td>
                            <td>17-07-2014</td>
                            <td>Auckland Archery Club</td>
                            <td>One Tree Hill Cup</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td><span class="label label-warning">Pending</span></td>
                            <td>10-07-2014</td>
                            <td>Mt Green Archery Club</td>
                            <td>North Island Champs</td>
                        </tr>

                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
    {{-- </div> --}}

@endsection

