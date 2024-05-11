<table width="100%">
    <thead style="text-align: center;">
        <tr>
            <td rowspan="2" colspan="5"><h1>Tusher's Care</h1></td>
        </tr>
        <tr></tr>
        <tr>
            <th>Description</th>
            <th>Price</th>
            <th>Date</th>
            <th>Total Book</th>
            <th>Added By</th>
        </tr>
    </thead>

    <tbody>
        @php
            $total=0;
        @endphp
        @foreach ($sells as $sell)

        @php
            $total += $sell->price;
        @endphp

        <tr>
            <td>{{$sell->description}}</td>

            <td>{{$sell->price}}</td>

            <td>{{$sell->date}}</td>
            <td>{{$sell->totalBook}}</td>

            <td>{{$sell->added_by}}</td>

        </tr>
        @endforeach
        <tr>
            <td>Total</td>
            <td>{{$total}}</td>
            <td colspan="3"></td>
        </tr>
    </tbody>


</table>
