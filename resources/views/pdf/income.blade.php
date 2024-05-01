<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body >
    <table border="1" style="border-collapse:collapse;width:100%;">
        <caption>
            <img src="{{asset('assets/pdf/form/images/l.png')}}" width="225" alt="" >
        </caption>
        <thead style="margin: 10px">

            <tr>
                <td colspan="8" style="text-align: center;">
                <strong>Income Report</strong>
                </td>
            </tr>
            <tr>
                <th>#</th>
                <th>Roll</th>
                <th>Name</th>
                <th>Amount</th>
                <th>Pay Type</th>
                <th>Type</th>
                <th>Admitted Date</th>
                <th>Added BY</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
            @foreach ($income as $payment)
            <tr>
                <th>{{ $loop->iteration }}</th>
                <td>{{ $payment->student->roll }}</td>
                <td >{{ $payment->student->personal_details->name }}</td>
                <td>
                    {{$payment->amount}}
                    @php
                    $total += $payment->amount
                    @endphp
                </td>
                <td>
                    @if ($payment->pay_type==0)
                    By Hand
                    @elseif ($payment->pay_type==1)
                    Bkash
                    @elseif ($payment->pay_type==2)
                        Nagad
                    @endif
                </td>
                <td>
                    @if ($payment->first_time==1)
                        Admission
                    @elseif ($payment->first_time==2)
                    Montly({{ date('F', mktime(0, 0, 0, str_split($payment->month, 4)[1], 10)) }})
                    @endif
                </td>
                <td>{{ $payment->created_at }}</td>

                <td>{{ $payment->added_by?$payment->added_by->name:"" }}</td>
            </tr>
        @endforeach
        <tr style="text-align: center">
            <td colspan="3">Total</td>
            <td colspan="5">{{$total}} tk</td>
        </tr>
        </tbody>
    </table>

    <div style="border-bottom:1px solid #000;margin-top:50px; width:200px;">
        {{auth()->user()->name}}
    </div>
</body>
</html>
