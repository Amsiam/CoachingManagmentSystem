<table>
    <thead style="text-align: center;">
        <tr>
            <td rowspan="2" colspan="8"><h1>Tusher's Care</h1></td>
        </tr>
        <tr></tr>
        <tr>
            <th>#</th>
            <th>Roll</th>
            <th>Name</th>
            <th>Amount</th>
            <th>Pay Type</th>
            <th>Payment Type</th>
            <th>Created Date</th>
            <th>Recieved By</th>
        </tr>
    </thead>

    <tbody>
        @php
            $total = 0;
        @endphp
        @foreach ($payments as $payment)

        @php
            $total += $payment->paid;
        @endphp

        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$payment->student->roll}}</td>
            <td>{{$payment->student->name}}</td>
            <td>{{$payment->paid}}</td>
            <td>{{$payment->payType}}</td>
            <td>
                @if ($payment->paymentType==0)
                Montly({{date("F",strtotime($payment->month))}})
                @elseif($payment->paymentType==1)
                Due Payment
                @elseif($payment->paymentType==2)
                Admission
                @endif
            </td>
            <td>{{$payment->created_at}}</td>
            <td>{{$payment->recieved_by}}</td>
        </tr>

        @endforeach

        <tr>
           <td colspan="3">Total</td>
           <td colspan="5">{{$total}}</td>
        </tr>
    </tbody>
</table>
