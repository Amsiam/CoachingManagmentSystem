<table>
    <thead style="text-align: center;">
        <tr>
            <td rowspan="2" colspan="{{iterator_count($periods)+8}}"><h1> <font face="hind siliguri" size="6"> তুষার’স কেয়ার </font>  <br>   <font face="hind siliguri" size="5"> একাডেমিক এন্ড এডমিশন  </font></h1></td>
        </tr>
        <tr></tr>
        <tr>
            <th>#</th>
            <th>Roll</th>
            <th>Name</th>
            <th>Student Mobile</th>
            <th>Guardian Mobile</th>
            <th>Admission Date</th>
            <th>Admission Amount</th>
            <th>DUE</th>

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
            <td>{{$student->personalDetails->gmobile}}</td>
            <td>{{$student->created_at}}</td>

            <td>{{$student->payments_sum_total}}</td>
            <td>{{$student->payments_sum_due}}</td>

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
