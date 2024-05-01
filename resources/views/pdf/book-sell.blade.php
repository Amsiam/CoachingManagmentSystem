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
                <tr class="text-nowrap">
                    <th>#</th>
                    <th>description</th>
                    <th>amount</th>
                    <th>date</th>
                    <th>Added By</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $expensesTotal = 0;
                @endphp
                @foreach ($sells as $expense)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $expense->desciption }}</td>
                        <td>{{ $expense->amount }}
                            @php
                               $expensesTotal += $expense->amount;
                            @endphp
                        </td>
                        <td>{{ $expense->date }}</td>
                        <td>{{ $expense->user?$expense->user->name:"" }}</td>
                    </tr>
                @endforeach

                <tr>
                    <td></td>
                    <td>Total</td>
                    <td colspan="3">{{ $expensesTotal }}</td>
                </tr>
            </tbody>


    </table>

    <div style="border-bottom:1px solid #000;margin-top:50px; width:200px;">
        {{auth()->user()->name}}
    </div>
</body>
</html>
