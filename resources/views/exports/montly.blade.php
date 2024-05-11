<table>
    <thead style="text-align: center;">
        <tr>
            <td rowspan="2" colspan="{{iterator_count($periods)+6}}"><h1>Tusher's Care</h1></td>
        </tr>
        <tr></tr>
        <tr>
            <th>#</th>
            <th>Roll</th>
            <th>Name</th>

            @foreach ($periods as $date)
                <th>{{$date->format("F")}}</th>
            @endforeach

        </tr>
    </thead>

    <tbody>
        @php
            $total = 0;
        @endphp
        @foreach ($students as $student)

        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$student->roll}}</td>
            <td>{{$student->name}}</td>

            <td>{{$student->personalDetails->smobile}}</td>
            <td>{{$student->personalDetails->smobile}}</td>
            <td>{{$student->created_at}}</td>

            @foreach ($periods as $date)

                <td>
                    {{
                   $student->payments
                   ->whereBetween("month",[$date->format("Y-m")."-01",$date->format("Y-m")."-31"])
                   ->sum("paid")
                    }}</td>
            @endforeach
        </tr>

        @endforeach


    </tbody>
</table>
