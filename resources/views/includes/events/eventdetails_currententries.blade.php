<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div>
                <div class="box box-primary collapsed-box">
                    <div class="box-header with-border">
                        <div>
                            <h1 class="box-title">Current Entries</h1>
                        </div>
                        <div class="box-tools pull-right" >
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">Click to Open &nbsp;
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table">

                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Division</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ ucwords($user->fullname) }}</td>
                                            <td>{{ $user->division }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
