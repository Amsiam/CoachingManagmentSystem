<table>
    <thead style="text-align: center;">
        <tr>
            <td rowspan="2" colspan="10"><h1>Tusher's Care</h1></td>
        </tr>
        <tr></tr>
        <tr>
            <th>Reg</th>
            <th>Roll</th>
            <th>Name</th>
            <th>Student Number</th>
            <th>Gurdian Number</th>
            <th>Batch</th>
            <th>Shift</th>
            <th>Admission Date</th>
            <th>Amount Payable</th>
            <th>Paid</th>
            <th>Due</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($students as $student)

        <tr>
            <td>{{ str_pad($student->id, 6, '0', STR_PAD_LEFT) }}</td>
            <td>{{$student->roll}}</td>
            <td>{{$student->name}}</td>
            <td>{{$student->personalDetails->smobile}}</td>
            <td>{{$student->personalDetails->gmobile}}</td>
              <td>{{$student->batches->pluck("name")->implode(",")}}</td>
            <td>{{$student->personalDetails?->shiftD?->name}}</td>
            <td>{{$student->created_at}}</td>
            <td>{{$student->payments_sum_total - $student->payments_sum_discount}}</td>
            <td>{{$student->payments_sum_paid}}</td>
            <td>{{$student->payments_sum_due}}</td>
            <td>{{$student->active?'Active':$student->deactive_reason}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
