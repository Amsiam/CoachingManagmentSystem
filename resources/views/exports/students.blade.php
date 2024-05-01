<table>
    <thead style="text-align: center;">
        <tr>
            <td rowspan="2" colspan="8"><h1>Tusher's Care</h1></td>
        </tr>
        <tr></tr>
        <tr>
            <th>Roll</th>
            <th>Name</th>
            <th>Student Number</th>
            <th>Gurdian Number</th>
            <th>Admission Date</th>
            <th>Amount Payable</th>
            <th>Paid</th>
            <th>Due</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($students as $student)

        <tr>
            <td>{{$student->roll}}</td>
            <td>{{$student->name}}</td>
            <td>{{$student->personalDetails->smobile}}</td>
            <td>{{$student->personalDetails->gmobile}}</td>
            <td>{{$student->created_at}}</td>
            <td>{{$student->payments_sum_total - $student->payments_sum_discount}}</td>
            <td>{{$student->payments_sum_paid}}</td>
            <td>{{$student->payments_sum_due}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
