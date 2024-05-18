<table>
    <thead style="text-align: center;">
        <tr>
            <td rowspan="2" colspan="8"><h1>Tusher's Care</h1></td>
        </tr>
        <tr></tr>

        <tr>
            <td colspan="8"><h1>Pay Report</h1></td>
        </tr>
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
           <td colspan="3">Total Recieved</td>
           <td colspan="5">{{$total}}</td>
        </tr>
        <tr>
            <td colspan="8">--</td>

            @php
                $bookTotal = 0;
            @endphp
        </tr>

        <tr>
            <td  colspan="8">Book sell Report</td>
        </tr>
        <tr>
            <th></th>
            <th>Date</th>
            <th>Total Book</th>
            <th>Price</th>
            <th colspan="2">Description</th>
            <th>Created Date</th>
            <th>Added By</th>
        </tr>

        @foreach ($bookSells as $bookSell)
        <tr>
            <td>{{$loop->iteration}}</td>

            <td>{{$bookSell->date}}</td>
            <td>{{$bookSell->totalBook}}</td>

            <td>
                @php
                    $bookTotal+=$bookSell->price;
                @endphp
                {{$bookSell->price}}
            </td>
            <td colspan="2">
                {{$bookSell->description}}
            </td>

            <td>{{$bookSell->created_at}}</td>
            <td>{{$bookSell->added_by}}</td>

        </tr>
        @endforeach
        <tr>
            <td colspan="3">Total Book Sell</td>
            <td colspan="5">{{$bookTotal}}</td>
         </tr>

         <tr>
            <td colspan="8">--</td>
        </tr>
        <tr >
            <td  colspan="3">Total</td>
            <td  colspan="5">{{$bookTotal + $total}}</td>
         </tr>

    </tbody>
</table>
