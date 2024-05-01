<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admit Card Form</title>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;600&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0px;
            padding: 0px;
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
            width: 5.1in;
            height: 7.3in;
            position: relative;


        }

        .form {
            border: 2px solid var(--textback);
            height: 7.3in;
        }

        .contain {
            padding: 20px;
        }

        .logo-part,
        .admit {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .admit {
            margin-top: 15px;
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
            width: 60px;
            height: 62px;
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
            width: 70px;
            height: 90px;
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
            margin-top: -20px;
            width: 100%;
            border: 2px solid var(--textback);
            border-collapse: collapse;
        }

        .Description {
            width: 410px;
        }

        .table th {
            height: 20px;
            padding: 5px;
        }

        .table td,
        th {
            border: 1px solid var(--textback);
        }

        .table td {
            height: 40px;
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
</head>

<body>
<div style="display: flex;flex-wrap: wrap;">

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

    @foreach ($exam->batch->students as $student)

    <div style="flex: 45%;" class="container">

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
                        <div class="admit">
                            <h3>Admit Card</h3>
                        </div>

                        <div class="box">
                            @if($student->image) <img width="70" height="90" alt="photo" src=" {{ asset('storage/' . $student->image) }}"/>
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
                        <h3>Admit Card No <span>:</span>{{$exam->id."-".$student->roll}}</h3>

                    </div>
                    <div class="details-right">
                        <h3>Batch<span>:</span>{{$exam->batch->batch_name}}</h3>
                    </div>
                </div>
                <div class="details-bottom">

                    <h3>Admit Issue Date<span>:</span>{{$exam->created_at}}</h3>
                        <h3>Candidate's Name <span>:</span> {{$student->personal_details->name}}</h3>
                        <h3>Mobile No<span>:</span>{{$student->personal_details->smobile}}</h3>
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


            <div style="position: absolute;bottom:10px;" class="contain">

                <div class="note">
                    <div class="nb">
                        <h3>NB:</h3>
                    </div>
                    <div class="note-details">
                        <h3>* You must carry this admit card during the examination.</h3>
                        <h3>* This Admit card is not trasferable</h3>
                        <h3>* Admit card will be required for the above examination.</h3>
                    </div>
                    <div style="margin-left:100px" class="signature">

                        <hr class="border">
                        <h4>Exam Controller</h4>
                    </div>

                </div>
            </div>

        </div>
    </div>
    @endforeach

    @if(count($exam->batch->students)%2)

    <div style="flex: 45%;" class="container">

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
                        <div class="admit">
                            <h3>Admit Card</h3>
                        </div>

                        <div class="box">
                            @if($student->image) <img width="70" height="90" alt="photo" src=" {{ asset('storage/' . $student->image) }}"/>
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
                        <h3>Admit Card No :</h3>

                    </div>
                    <div class="details-right">
                        <h3>Batch<span>:</span></h3>
                    </div>
                </div>
                <div class="details-bottom">

                    <h3>Admit Issue Date<span>:</span>{{$exam->created_at}}</h3>
                        <h3>Candidate's Name <span>:</span> </h3>
                        <h3>Mobile No<span>:</span></h3>
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


            <div style="position: absolute;bottom:10px;" class="contain">

                <div class="note">
                    <div class="nb">
                        <h3>NB:</h3>
                    </div>
                    <div class="note-details">
                        <h3>* You must carry this admit card during the examination.</h3>
                        <h3>* This Admit card is not trasferable</h3>
                        <h3>* Admit card will be required for the above examination.</h3>
                    </div>
                    <div style="margin-left:100px" class="signature">

                        <hr class="border">
                        <h4>Exam Controller</h4>
                    </div>

                </div>
            </div>

        </div>
    </div>
    @endif


</div>
</body>

</html>
