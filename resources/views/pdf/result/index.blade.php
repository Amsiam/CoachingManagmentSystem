<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admit Card Form</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        * {
            margin: 1px;
            box-sizing: border-box;
            font-family: 'Source Sans Pro', sans-serif;
            text-decoration: none;
            --textwhite: white;
            --textback: #000000;
            --border: #333131;
            --name: #0A4877;
            --picture: #989696;
        }

        .container {
            //background: rgba(246, 247, 248, 0.8);
            position: relative;

            height: 289mm;

            margin-left: auto;
            margin-right: auto;
            text-align: center;



        }

        .form {}

        .contain {
            padding: 30px;
        }

        .logo-part,
        .admit {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .admit {
            margin-top: 30px;
        }

        .admit h3 {
            text-transform: uppercase;
            padding: 4px 15px;
            color: var(--textwhite);
            background-color: var(--textback);
            border-radius: 5px;
            font-family: Helvetica;
        }


        .logo img {
            width: 100px;
            height: 72px;
            border: 1px solid var(--textback);
            border-top-left-radius: 45%;
            border-top-right-radius: 45%;
            background-color: #FFFFFF;
        }

        h1 {
            font-size: 2rem;
            font-weight: 900;
            color: var(--name);
        }

        .text {
            padding-left: 10px;
            text-align: right;
        }

        .text h1,
        h3 {
            font-family: sans-serif;
        }

        .text h3 {
            font-weight: 800;
            font-size: .8rem;
        }

        .text h4 {
            font-size: .47rem;
        }

        .box {
            margin-top: -14%;
            float: right;
            display: grid;
            place-items: center;
            width: 120px;
            height: 130px;
            border: 1px solid var(--textback);
        }

        .box h3 {

            color: var(--picture);
            font-size: .8rem;

        }


        .details-upper {
            margin-top: -10px;
            display: flex;
            justify-content: space-between;
        }


        .details-right h3 {}

        span {
            padding: 0 10px;
        }


        .details-upper h3,
        .details-bottom h3 {
            color: var(--textback);
            font-size: .9rem;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: 600;
        }

        table {
            text-align: center;
            align-items: center;
            width: 100%;
            border: 2px solid var(--textback);
            border-collapse: collapse;
        }

        .Description {
            width: 410px;
        }

        .table th {
            height: 17px;
            padding: 5px;
        }

        .table td,
        th {
            border: 1px solid var(--textback);
        }

        .table td {
            height: 28px;
        }

        .detail {
            margin-top: -20px;
        }

        /* signature start */
        .note {
            display: flex;
            justify-content: space-between;
        }

        .note h3 {
            font-size: .7rem;
            color: var(--border);
            font-family: sans-serif;
            font-weight: 400;
        }

        .note-details {
            /* margin-left: -30%; */
        }

        .sign {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .signature {

            text-align: center;

        }

        .footer {
            text-align: center;

        }

        .container:nth-child(odd) {
            margin-right: 20px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        td {
            padding: 5px 5px;
        }
    </style>

    <style>
        @page {
            size: A4;
            margin: 0;
        }

        @media print {

            html,
            body {

                height: 292mm;
            }
        }
    </style>

</head>

<body>
    <div style="height:297px" style="display: block;flex-wrap: wrap;">



        @foreach ($resultStudents as $student)
            <div class="container">

                <div class="form">

                    <div>
                        <div id="result_display">
                            <div class="table-container">
                                <table style="width: 100%" class="table-striped">
                                    <thead>
                                        <tr>
                                            <th colspan="4">Student Information Summary</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Roll No</td>
                                            <td colspan="3">{{ $student->roll }}</td>

                                        </tr>
                                        <tr>
                                            <td>Name of Student</td>
                                            <td colspan="3">{{ $student->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>Father's Name</td>
                                            <td colspan="3">{{ $student->personalDetails->fname }}</td>
                                        </tr>
                                        <tr>
                                            <td>Mother's Name</td>
                                            <td colspan="3">{{ $student->personalDetails->mname }}</td>
                                        </tr>
                                        <tr>
                                            <td>Package</td>
                                            <td>{{ $student->package->name }}</td>
                                            <td>Session</td>
                                            <td>{{ $student->year }}</td>
                                        </tr>


                                    </tbody>
                                </table>
                                <div class="alert alert-info text-center" id="err_msg" style="display:none"></div>
                                <div class="text-center">
                                    <h4>Subject-wise Grade/Marks</h4>
                                </div>
                                <table style="width: 100%" class="table-striped">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">Subject Code</th>
                                            <th rowspan="2">Subject Name</th>
                                            <th colspan="4">Marks</th>
                                            <th rowspan="2">Grade</th>
                                        </tr>
                                        <tr>
                                            <th>Theory</th>
                                            <th>Practical</th>
                                            <th>MCQ</th>
                                            <th>Total </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $gradeMark = 0;
                                            $totalSub = 0;
                                            $totalSubprev = 0;
                                            $gotFail=0;
                                        @endphp
                                        @foreach ($result->resultSubjects as $subject)
                                            <tr>
                                                <td class="cent-align">

                                                    {{ $subject->code }}

                                                    @if ($subject->has2ndPart)
                                                        <br>{{ $subject->has2ndPart->code }}
                                                    @endif

                                                </td>
                                                <td>
                                                    {{ $subject->name }}

                                                    @if ($subject->has2ndPart)
                                                        <br>{{ $subject->has2ndPart->name }}
                                                    @endif
                                                </td>
                                                <td class="right-align">
                                                    {{ $subject->marks->where('student_id', $student->id)->first()->cq }}

                                                    @if ($subject->has2ndPart)
                                                        <br>{{ $subject->has2ndPart->marks->where('student_id', $student->id)->first()->cq }}
                                                    @endif
                                                </td>
                                                <td class="right-align">
                                                    {{ $subject->marks->where('student_id', $student->id)->first()->practical }}

                                                    @if ($subject->has2ndPart)
                                                        <br>{{ $subject->has2ndPart->marks->where('student_id', $student->id)->first()->practical }}
                                                    @endif
                                                </td>
                                                <td class="right-align">
                                                    {{ $subject->marks->where('student_id', $student->id)->first()->mcq }}

                                                    @if ($subject->has2ndPart)
                                                        <br>{{ $subject->has2ndPart->marks->where('student_id', $student->id)->first()->mcq }}
                                                    @endif
                                                </td>
                                                <td class="right-align">

                                                    @php
                                                        $total = $subject->marks
                                                            ->where('student_id', $student->id)
                                                            ->first()->cq;
                                                        $total += $subject->marks
                                                            ->where('student_id', $student->id)
                                                            ->first()->practical;
                                                        $total += $subject->marks
                                                            ->where('student_id', $student->id)
                                                            ->first()->mcq;
                                                        if ($subject->has2ndPart) {
                                                            $total += $subject->has2ndPart->marks
                                                                ->where('student_id', $student->id)
                                                                ->first()->cq;
                                                            $total += $subject->has2ndPart->marks
                                                                ->where('student_id', $student->id)
                                                                ->first()->practical;
                                                            $total += $subject->has2ndPart->marks
                                                                ->where('student_id', $student->id)
                                                                ->first()->mcq;
                                                        }
                                                    @endphp
                                                    {{ $total }}
                                                </td>
                                                <td class="cent-align">
                                                    @php
                                                        $grade = getPoint($subject,$student);

                                                        if(!$gotFail){
                                                            if($subject->marks
                                                                ->where('student_id', $student->id)
                                                                ->first()->is_optional){
                                                                $gradeMark+= max(0,$grade-2);
                                                            $totalSubprev = $totalSub;

                                                            }else{
                                                                $gradeMark+=$grade;
                                                                $totalSubprev++;

                                                                if($grade==0){
                                                                    $gradeMark=0;
                                                                    $gotFail = 1;
                                                                }
                                                            }
                                                        }



                                                    @endphp


                                                {{getGrade($grade)}}
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                    <tfoot >
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td colspan="4">Result</td>
                                            <td>{{number_format((float)($gradeMark/$totalSubprev), 2, '.', '')}}</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td colspan="4">Total Marks</td>
                                            <td>{{($totalSub)}}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                    </div>


                </div>
            </div>
        @endforeach



    </div>
</body>

</html>
