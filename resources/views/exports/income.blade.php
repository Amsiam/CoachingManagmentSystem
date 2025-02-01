<table border="1" style="width:100%;border-collapse: collapse;text-align:center">
    <thead style="text-align: center;">
        <tr>
            <td rowspan="2" colspan="8">
                 <font face="hind siliguri" size="6"> <b> তুষার’স কেয়ার</b> </font>  <br>   <font face="hind siliguri" size="5"> </font>
                </td>
        </tr>
        <tr></tr>

        <tr>
            <td colspan="8"><h1>Daily Report</h1></td>
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
            <td>{{$payment?->student?->roll}}</td>
            <td>{{$payment?->student?->name}}</td>
            <td>{{$payment?->paid}}</td>
            <td>{{$payment?->payType}}</td>
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
            <td>{{$payment?->recievedBy?->name}}</td>
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
            <td>{{$bookSell?->addedBy?->name}}</td>

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
            <td  colspan="5">{{$totalIn = $bookTotal + $total}}</td>
         </tr>

    </tbody>
</table>

<table border="1" style="width:100%;border-collapse: collapse;text-align:center">
    <thead style="text-align: center;">
        <tr>
            <th>Date</th>
            @foreach ($expenseCategories as $ec)
            <th>{{$ec->name}}</th>
            @endforeach
            <th>Total</th>
        </tr>
    </thead>

    <tbody>
        @php
            $gtotal=0;
        @endphp
        @foreach ($dates as $date)

        <tr>
        @php
            $total=0
        @endphp
            <td>{{$date->date}}</td>

            @foreach ($expenseCategories as $ec)
            @php
                $totalExpense = $expenses
                ->where("date",$date->date)
                ->where("type",$ec->name)
                ->sum("amount");


                if(!isset($totalExpense)){
                    $totalExpense = 0;
                }
                $total += $totalExpense ;
            @endphp
            <td>{{$totalExpense}}</td>
            @endforeach
            <td>{{$total}}</td>

@php
    $gtotal += $total;
@endphp
        </tr>
        @endforeach

        <tr>
            <td colspan="{{count($expenseCategories)}}"></td>

            <th>Total</th>
            <th>{{$gtotal}}</th>
        </tr>
    </tbody>

</table>


<table border="1" style="text-align:center;border-collapse: collapse;text-align:center;width:25%;margin-left:auto;margin-top:20px;margin-bottom:20px;">

<tr>
<th>Total Income </th>
<th>{{$totalIn}}</th>
</tr>

<tr>
    <th>Total Expense </th>
    <th>{{$gtotal}}</th>
    </tr>

    <tr>
        <th>Net Income </th>
        <th>{{$totalIn-$gtotal}}</th>
        </tr>
</table>

