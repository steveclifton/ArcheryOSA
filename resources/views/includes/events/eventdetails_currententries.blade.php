<div class="col-md-3">
    <div class="row">
        <div class="col-md-12">
            <div>
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div>
                            <h1 class="box-title">Current Entries</h1>
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
                                    @foreach (array_slice($users, 0, 14) as $user)
                                        <tr>
                                            <td><a href="{!! route('getpublicuserview', $user->username) !!}">{{ ucwords($user->fullname) }}</a></td>
                                            <td>{{ $user->division }}</td>
                                        </tr>
                                    @endforeach
                                    @foreach (array_slice($users, 14) as $user)
                                        <tr class="item hidden">
                                            <td>{{ ucwords($user->fullname) }}</td>
                                            <td>{{ $user->division }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>

                        <div class="box-footer text-center showmore">
                            <a href="javascript:;" class="uppercase" id="showmoreentries">View More Entries</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
