<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>tusharcare</title>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@500;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0px;
            padding: 0px;
            box-sizing: border-box;
            font-family: 'Times New Roman', Times, serif, sans-serif;
            text-decoration: none;
            --textwhite: white;
            --textback: #000000;
            --border: #333131;
        }

        .container {
            /* background: rgba(246, 247, 248, 0.8); */
            /* min-height: 11.69in; */
        }

        .form {
        }

        .contain {
            padding: 20px;
        }

        .top-header,
        .left {
            display: flex;
            justify-content: space-between;
            column-gap: .7rem;
        }

        /* logopart start  */
        .logopart {
            display: flex;
            column-gap: .7rem;
        }

        .logopart .logo {
            padding: 5px;
            border-radius: 10px;
            border: 2px solid var(--border);
        }

        .logopart .logo h4 {
            font-size: .5rem;
            font-weight: 600;
            text-align: center;
        }

        .logopart .logo img {
            height: 50px;
            width: 65px;
        }

        .logopart .text h3 {
            font-family: 'Times New Roman', Times, serif, sans-serif;
            font-size: .9rem;
            color: var(--textback);
        }

        .logopart .text h1 {
            font-family: 'Times New Roman', Times, serif, sans-serif;
            font-size: 1.4rem;
            color: var(--bg-color);
            margin: -5px 0;
            font-weight: 800;
        }


        /* logopart end */
        h1 {
            font-size: 1.3rem;
            font-weight: 800;
        }

        .text {
            text-align: left;
        }

        .text h1 {
            font-family: 'Times New Roman', Times, serif, sans-serif;
        }

        .text h3 {
            font-size: .8rem;
        }

        .text h4 {
            font-size: .47rem;
        }

        .horizontal {
            display: flex;
        }

        .border {
            background-color: var(--border);
            height: 3px;
            margin: 2px 0;
            border: 0 none;
            width: 100%;
        }

        .money {
            width: 100%;
            padding: 2px 0;
            border-radius: 10px;
            border: 1px solid var(--textback);
            text-align: center;
            margin-top: -10px;
        }

        .money h1 {
            font-size: 13px;
            font-family: 'Times New Roman', Times, serif, sans-serif;
            text-transform: uppercase;
        }

        .details-upper {
            margin-top: -10px;
            display: flex;
            justify-content: space-between;
        }

        .detail-right img {
            height: 45px;
            width: 160px;
        }

        .details-right h3 {
            width: 160px;
        }

        span {
            padding: 0 10px;
        }

        .details-lower {

            display: flex;
            justify-content: space-between;
        }

        .details-upper h3,
        .details-lower h3 {
            color: var(--textback);
            font-size: .8rem;
            font-family: 'Times New Roman', Times, serif, sans-serif;
            font-weight: 600;
        }

        .table {
            font-size: .8rem;
            margin-top: -30px;
            width: 100%;
            border: 2px solid var(--textback);
            border-collapse: collapse;
            text-align: center;
        }

        .table th {
            padding: 2px;
            height: 5px;
        }

        .table td,
        .table th {
            border: 1px solid var(--textback);
        }

        .table2 {
            font-size: .8rem;
            margin-top: -30px;
            color: #000000;
            font-weight: 600;
            text-align: center;
            border-collapse: collapse;
        }

        .table2 td {
            border: 1px solid var(--textback);
            width: 90px;
            padding: 2px 0px;
        }

        .detail {
            margin-top: -20px;
        }

        /* signature start */

        .sign {
            font-size: .8rem;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .signature {

            text-align: center;

        }

        .footer {
            text-align: center;
            padding: 9px;
            background-color: var(--border);
        }

        .footer h3 {
            color: var(--textwhite);
            font-size: .9rem;
        }
    </style>
</head>

<body>

    <table width="100%">
        <thead>
            <tr style="height: 1.7in;">
                <th></th>
            </tr>
            <tr>
                <th>
                    <span style="border: 1px solid #000;padding: 5px 20px;">INVOICE</span>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="container">

                        <div class="form">

                            <div class="contain">

                                <table style="width: 100%;font-size: 0.8rem;font-weight: 600;font-family: 'Times New Roman', Times, serif, sans-serif;">
                                    <tr>
                                        <td width="5%">Bill NO</td>
                                        <td>:</td>
                                        <td>TC{{date("Y", strtotime($payment->created_at)).str_pad($payment->id, 4, '0', STR_PAD_LEFT)}}</td>

                                        <td width="30%"></td>
                                        <td  colspan="3" rowspan="4">
                                            {!! $barCode !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Bill Date</td>
                                        <td>:</td>
                                        <td>{{ $payment->created_at }}</td>
                                        <td width="30%"></td>
                                    </tr>
                                    <tr>
                                        <td>Name</td>
                                        <td>:</td>
                                        <td>{{ $payment->student->name }}</td>
                                        <td width="30%"></td>
                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <td>:</td>
                                        <td>{{ $payment->student->personalDetails->paddess }}</td>
                                        <td width="30%"></td>
                                    </tr>
                                    <tr>
                                        <td>Mobile No</td>
                                        <td>:</td>
                                        <td>{{ $payment->student->personalDetails->smobile }}</td>
                                        <td width="30%"></td>
                                        <td width="5%">Roll No</td>
                                        <td>:</td>
                                        <td>{{ $payment->student->roll }}</td>
                                    </tr>
                                    <tr>
                                        <td>Batch No</td>
                                        <td>:</td>
                                        <td>
                                            @foreach ($payment->student->batches as $batch)
                                                @if ($loop->iteration != 1)
                                                    ,
                                                @endif
                                                {{ $batch->name }}
                                            @endforeach
                                        </td>
                                        <td width="30%"></td>
                                        <td>Reg No</td>
                                        <td>:</td>
                                        <td>{{ str_pad($payment->student->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- table star -->

                            <div class="contain">
                                <table class="table">
                                    <tr>
                                        <th width="10%">SL No</th>
                                        <th width="70%">Description</th>
                                        <th width="20%">Price(TK.)</th>
                                    </tr>
                                    @php
                                        $total = 0;
                                        $numOfCourses = 0;
                                    @endphp
                                    @if ($payment->paymentType == 2)

                                        @foreach ($payment->student->courses as $course)

                                        @if ($payment->student->courses->contains('parent_id', $course->id))

                                            @continue
                                        @endif
                                            @php
                                                $numOfCourses++;
                                            @endphp

                                            <tr>
                                                <td>{{ $numOfCourses }}</td>
                                                <td>{{ $course->name }}</td>
                                                <td>
                                                    {{ $course->price }}
                                                    @php
                                                        $total += $course->price;
                                                    @endphp
                                                </td>
                                            </tr>
                                        @endforeach

                                        @if ($numOfCourses >= 2)
                                            @php
                                                $total = $payment->total;
                                            @endphp
                                        @endif
                                    @elseif ($payment->paymentType == 0)
                                        <tr>
                                            <td>1</td>
                                            <td>Monthly Salary ({{ date('F', strtotime($payment->month)) }})</td>
                                            <td>
                                                {{ $payment->total }}
                                                @php
                                                    $total += $payment->total;

                                                @endphp
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>1</td>
                                            <td>Previous Due</td>
                                            <td>{{ $prevDue }}
                                                @php
                                                    $total += $prevDue;
                                                @endphp
                                            </td>
                                        </tr>

                                    @endif
                                </table>
                            </div>


                            <div class="contain">
                                <div class="details-upper">
                                    <div class="detail">
                                        <h3>Remark: <span>:</span> {{ $payment->remarks }}</h3>
                                        @if ($payment->due_date)
                                            <h3>Due Date: <span>:</span> {{ $payment->due_date }}</h3>
                                        @endif

                                        <br>
                                        <h1><strong>
                                                @if ($total - $payment->paid - $payment->discount > 0)
                                                    Partially Paid
                                                @else
                                                    Paid
                                                @endif

                                            </strong></h1>

                                    </div>
                                    <div class="detail-right">

                                        <table class="table2">
                                            <tr>
                                                <td>Sub Total Tk.</td>
                                                <td class="auto">{{ $total }}</td>
                                            </tr>
                                            <tr>
                                                <td>Discount Tk.</td>
                                                <td class="auto">{{ $payment->discount }}</td>
                                            </tr>
                                            <tr>
                                                <td>Payment Tk.</td>
                                                <td class="auto">{{ $payment->paid }}</td>
                                            </tr>
                                            <tr>
                                                <td>Due. Tk.</td>
                                                <td class="auto">

                                                    @if ($total - $payment->paid - $payment->discount > 0)
                                                        {{ $total - $payment->paid - $payment->discount }}
                                                    @else
                                                        0
                                                    @endif
                                                </td>

                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div style="padding-bottom: 0; margin-top:10px;" class="contain">
                                <div class="sign">
                                    <div class="name">
                                        <h3>Prepared By <span>:</span> {{ $payment?->recievedBy?->name }}</h3>
                                    </div>
                                    <div class="signature">
                                        <hr class="border">
                                        <h3>Signature of acceptor</h3>
                                    </div>
                                </div>
                            </div>


                            @if (!$prevPayments->isEmpty())
                                <div class="contain">
                                    <table class="table">
                                        <tr>
                                            <td colspan="6">Previous Payments</td>
                                        </tr>
                                        <tr>
                                            <th width="10%">SL No</th>
                                            <th width="20%">Description</th>
                                            <th width="20%">Date</th>
                                            <th width="15%">Amount</th>
                                            <th width="15%">Received BY</th>
                                            <th width="20%">Remarks</th>
                                        </tr>
                                        @php
                                            $total = 0;
                                        @endphp





                                        @foreach ($prevPayments as $payment)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                @if ($payment->paymentType == 2)
                                                    <td>Admission</td>
                                                @elseif ($payment->paymentType == 0)
                                                    <td>Monthly Salary ({{ date('F', strtotime($payment->month)) }})</td>
                                                @else
                                                    <td>Previous Due</td>
                                                @endif
                                                <td>{{ $payment->created_at->format('d-m-Y') }}</td>

                                                <td>
                                                    {{ $payment->paid }}
                                                    @php
                                                        $total += $payment->paid;
                                                    @endphp
                                                </td>
                                                <td>{{ $payment->recievedBy->name }}</td>
                                                <td>{{ $payment->remarks }}</td>
                                            </tr>
                                        @endforeach


                                        <tr>
                                            <td colspan="2"></td>
                                            <td><strong>Total</strong></td>
                                            <td><strong>{{ $total }}</strong></td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </table>
                                </div>

                            @endif

                            <div class="contain">


                            <div style="
                            background: #000000;
                            color: #fff;
                            text-align: center;
                            text-transform: capitalize;
                            padding: 5px;
                        ">
                                Transaction Related to admission is not refundable.
                            </div>
                        </div>
                            <!-- alltable end -->



                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr style="height: 0.8in;">
                <td></td>
            </tr>
        </tfoot>
    </table>


</body>

</html>
