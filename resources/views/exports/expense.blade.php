<table>
    <thead style="text-align: center;">
        <tr>
            <td rowspan="2" colspan="8"><h1>Tusher's Care</h1></td>
        </tr>
        <tr></tr>
        <tr>
            <th>Date</th>
            @foreach ($expenseCategories as $ec)
            <th>{{$ec->name}}</th>
            @endforeach
            <th>Total</th>
        </tr>
    </thead>

    <tbody>
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


        </tr>
        @endforeach
    </tbody>
</table>
