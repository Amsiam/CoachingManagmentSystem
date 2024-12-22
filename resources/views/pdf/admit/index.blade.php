<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admit Card Form</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin:1px;
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

            width: 200mm;
            height: 289mm;

            margin-left:auto;
            margin-right:auto;
            text-align:center;



        }

        .form {
        }

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


        .details-right h3 {

        }

        span {
            padding: 0 10px;
        }


        .details-upper h3, .details-bottom h3 {
            color: var(--textback);
            font-size: .9rem;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: 600;
        }

        .table {
            text-align: center;
            align-items: center;
            margin-top: -30px;
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
        .container:nth-child(odd){
            margin-right:20px;
        }
    </style>

    <style>
    @page {
      size: A4;
      margin: 0;
    }
    @media print {
      html, body {
        width: 200mm;
        height: 292mm;
      }
    }
    </style>
</head>

<body>
<div style="width:210px;height:297px" style="display: block;flex-wrap: wrap;">

   @php
       $routines = "";
       $i=1;
       foreach ($exam->exam_routines as $routine){

                   $routines .="<tr>
                        <td>".$i++."</td>
                        <td>".$routine->name."</td>
                        <td>".date("d-m-Y H:i a",strtotime($routine->date." ".$routine->time))."</td>
                    </tr>";
                }

   @endphp

    @foreach ($exam->batch->students->where("year",$exam->year) as $student)

    <div class="container">

        <div class="form">

            <div class="contain">

                <div class="header">
                    <div class="center">
                        <div class="logo-part">
                            {{-- <div class="logo">
                                <img src="images/tusharcare.png" alt="">
                            </div>
                            <div class="text">
                                <h1>তুষার'স কেয়ার</h1>
                                <h3>একাডেমি এন্ড এডমিশন কেয়ার</h3>
                            </div> --}}
                            @if ($exam->package_id==3)
                            <img src="{{asset('assets/pdf/form/images/d.png')}}" width="225" alt="" >
                            @else
                            <img src="{{asset('assets/pdf/form/images/l.png')}}" width="225" alt="" >
                            @endif</div> 
                            <br>
                           <div align="center"><h2> <font color="blue"> {{$exam->name}}<br> {{$exam->course?->name}}  </font></h2></div>
                        <div class="admit">
                            <h3>Admit Card</h3>
                        </div>

                        <div class="box">
                            @if($student->image) <img width="115" height="125" alt="photo" src=" {{ asset('storage/' . $student->image) }}"/>
                            @else
                        Photo
                            @endif
                        </div>

                    </div>
                </div>


            </div>

            <!-- header end -->


            <!-- horizontal end -->

            <div class="contain">
                <div class="details-upper">
                    <div class="detail-left">
                    <table>
                        <tr>
                            <th style="border:0;text-align:left">Admit Card No</th>
                            <td>:</td>
                            <td align="left">{{$exam->id."-".$student->roll}}</td>
                        </tr>
                         <tr>
                            <th style="border:0;text-align:left">Roll</th>
                            <td>:</td>
                            <td align="left">{{$student->roll}}</td>
                        </tr>
                         <tr>
                            <th style="border:0;text-align:left">Admit Issue Date</th>
                            <td>:</td>
                            <td align="left">{{$exam->created_at}}</td>
                        </tr>
                         <tr>
                            <th style="border:0;text-align:left">Candidate's Name</th>
                            <td>:</td>
                            <td align="left">{{$student->name}}</td>
                        </tr>
                         <tr>
                            <th style="border:0;text-align:left">Mobile No</th>
                            <td>:</td>
                            <td align="left">{{$student->personalDetails->smobile}}</td>
                        </tr>
                    </table>
                    </div>
                    <div class="details-right">
                        <h3> <font color="red">BATCH </font> <span>:</span>{{$exam->batch->name}}</h3>
                    </div>
                </div>
            </div>

            <!-- table star -->

            <div class="contain">
                <table class="table">
                    <tr>
                        <th width="10%">SL No</th>
                        <th width="60%">Exam Name</th>
                        <th width="30%">Exam Date</th>
                    </tr>

                    {!!$routines!!}


                </table>
            </div>


            <div style="position: absolute;bottom:20px;" class="contain">

                <div class="note">
                    <div class="nb">
                        <h2> <font color="red">NB:</font></font></h2>
                    </div>
                    <div class="note-details" align="left">
                        <h3>* You must carry this admit card during the examination.</h3>
                        <h3>* This Admit card is not trasferable</h3>
                        <h3>* Admit card will be required for the above examination.</h3>
                    </div>
                    <div style="margin-left:200px" class="signature" align="right">

                        <hr class="border">
                        <h4>Exam Controller</h4>
                    </div>

                </div>
            </div>

        </div>
    </div>
    @endforeach



</div>
</body>

</html>
