
<style>
    table, th, tr, td {
        border: 2px solid black;
        border-collapse: collapse;

    }


    td {
        text-align:center;
        vertical-align:middle;
    }

</style>

@if($data->count() > 0)
    <table class="tableleft">
        <tr>
            <th width="15%">Target #</th>
            <th width="85%">Name</th>
        </tr>
    @foreach ($data as $archer)
        <tr>
            <td>{{$archer->targetallocation}}</td>
            <td>{{$archer->fullname}}</td>
        </tr>
    @endforeach
    </table>
@endif


