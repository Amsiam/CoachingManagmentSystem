<table width="100%">
    <thead style="text-align: center;">
        <tr>
            <td rowspan="2" colspan="3"><h1>Tusher's Care</h1></td>
        </tr>
        <tr></tr>
        <tr>
            <th>Date</th>

            <th>Total Book</th>

            <th>Price</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($sells as $sell)

        <tr>
            <td>{{$sell->date}}</td>


            <td>{{$sell->totalBook}}</td>

            <td>{{$sell->price}}</td>


        </tr>
        @endforeach
    </tbody>
</table>
