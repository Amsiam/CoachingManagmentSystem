<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>idcard</title>
    <style>
        @media print{
            body{
                -webkit-print-color-adjust:exact;
                print-color-adjust:exact;
            }
        }
        .id-card {
            border: 1px solid #ddd;
            width: 55mm;
            max-height: 85mm !important;
            height: 85mm !important;
        }

        .id-header {
            display: flex;
            justify-content: center;
            position: relative;
            background-color: #02283D;
            border-bottom-left-radius: 50%;
            border-bottom-right-radius: 50%;
        }

        .id-header h1 {
            color: rgb(205, 196, 11);
            position: absolute;
            margin-top: 8%;
            text-align: center;
            font-size: 1.2rem;
            text-transform: uppercase;
            font-family: sans-serif;
        }


        .id-photo {
            border-radius: 50%;
            margin-top: 3rem;
            margin-bottom: -2rem;
            border: 8px solid #ddd;
            width: 100px;
            height: 100px;
        }

        .id-details {
            text-align: center;
            padding: 1rem;
            margin-top: 2rem;
            margin-bottom: -10px;
        }

        .id-details h1 {
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 0.4rem;
            font-family: sans-serif;
        }


        .id-details .m-info-group {
            margin-bottom: .5rem;
            text-transform: uppercase;
        }

        .id-details .m-info-group .info-value {
            font-family: sans-serif;
            font-weight: 500;
        }

        .id-details .m-info-group .info-batch {
            text-transform: uppercase;
            color: #ff0000;
            font-size: .7rem;
            font-family: sans-serif;
            font-weight: 800;

            line-height: 15px;
            white-space: nowrap;

        }

        .id-details .info-batch {
            font-size: .65rem;

        }


        .id-details .m-info-group h4 {
            margin-bottom: -5px;
            margin-top: 0.2rem;
            text-transform: none;
            font-family: sans-serif;
            font-weight: 700;
            font-size: .8rem;
        }

        .id-details .m-qrcode {
            display: flex;
            justify-content: center;
            align-items: center
        }

        .id-details .m-qrcode img {
            width: 100%;
            height: 68px;
        }

        .id-footer {
            background-color: #02283D;
            color: #FFFFFF;
            text-align: center;
        }

        /* font end */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .background {
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;

        }

        .id-card {
            border: 1px solid #ddd;
            position: relative;
            align-items: center;
            justify-content: center;
            margin: 5px 0;

        }

        .details {
            text-align: center;
        }

        .top-img {
            margin-top: 10px;
            position: relative;
        }

        .top-img img {
            align-items: center;
            justify-content: center;
        }

        .background::before {
            content: '';
            background-image: url(
                "@if($student->package_id==3){{asset('assets/pdf/idcard/image/dmcscolar.png')}}@else{{asset('assets/pdf/idcard/image/tusharcare.png')}}@endif"
                );
            background-position: center;
            background-repeat: no-repeat;
            position: absolute;
            background-size: 90px;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            opacity: .12;

        }

        h1 {
            font-family: sans-serif;
            position: relative;
        }

        .info-label {
            font-family: sans-serif;
            margin-top: -5px;
            text-align: center;
            font-weight: 700;
            font-size: .7rem;
        }

        .info-value {
            font-size: .7rem;
            font-weight: 500;
            text-align: center;
        }

        .m-name {
        font-size: 1.2rem;
            text-transform: uppercase;
            margin-bottom: -2px;
            font-family: sans-serif;
            color: #045682;
            white-space: nowrap;
        }
        .m-student-name {
        font-size: 0.8rem;
            text-transform: uppercase;
            margin-bottom: -2px;
            font-family: sans-serif;
            color: #045682;
            white-space: nowrap;
        }

        .m-info-group {
            text-align: center;
        }

        .bbinfo-value {
            font-size: .6rem;
            font-weight: 400;
            margin-bottom: 10px;

        }

        .info-valuedate {
            font-family: Helvetica;
            font-size: .6rem;

        }

        .info-valuedate span {
            color: #045682;
        }

        .border {
            background-color: #1b1d20;
            width: 50%;
            height: .5px;
            margin: -5px auto 0px auto;
            border: 0 none;

        }

        .athr img {
            margin: 5px 0;
        }

        .author h4 {
            font-family: sans-serif;
            font-size: .7rem;
            font-weight: 600;
            margin-top: 2px;
            margin-bottom: 15px;

        }

        .id-footer {
            background-color: #02283D;
            padding: 10px;
            text-align: center;
            display: flex;
            column-gap: .5rem;
        }

        .logo {
            margin: 12px 0 0 2px;
        }

        .logo img {
            width: 30px;
            height: 32px;
            border: .5px solid rgb(136, 136, 32);
            border-top-left-radius: 45%;
            border-top-right-radius: 45%;
            background-color: #FFFFFF;
        }

        .text {
            text-align: right;
        }

        .text h1 {
            font-size: 1.2rem;
            color: rgb(226, 215, 7);
            font-family: Helvetica;
        }

        .text h4 {
            font-size: .3rem;
            color: #FFFFFF;
            font-weight: 100;
        }

        .text h3 {
            font-size: .4rem;
            color: #FFFFFF;
            font-weight: 300;
        }

        .container {
            position: relative;
            display: flex;
            column-gap: .3rem;
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="id-card">
            <div class="id-header">
                <h1>Identity card</h1>
                <img class="id-photo" src="
                @if ($student->image)
                {{ url('storage/' . $student->image) }}
                @else
                {{asset('assets/img/avatars/1.png')}}
                @endif
                " alt="photo" />
            </div>
            <div class="id-details">
                <h1 class="m-student-name">{{$student->name}}</h1>
                <div class="m-info">
                    <div>
                        <div class="m-info-group">
                            <h4 class="info-label">Student</h4>
                            <h4 class="info-value">Roll: {{$student->roll}}</h4>
                            <h4 class="info-batch">Batch:
                                @foreach ($student->batches as $batch)
                                @if($loop->iteration!=1),@endif
                                    {{$batch->name}}
                                @endforeach</h4>
                        </div>

                    </div>
                    <div class="m-qrcode">
                        {!!$barCode!!}
                    </div>
                </div>
            </div>
            <div class="id-footer">

            </div>
        </div>
        <!-- font end -->


        <div class="id-card">
            <div class="details">


                <div class="top-img">
                    <img src="{{asset('assets/pdf/idcard/image/top3.png')}}" alt="" srcset="">
                    <h4 class="info-label">This card is not transferable</h4>
                    <h4 class="info-value">if you found it anywhere <br>
                        please return it to this address
                    </h4>
                </div>

                <div class="background">
                    <div class="m-info-group">
                        @if ($student->package_id==3)

                        <h1 class="m-name">dmc scholar</h1>
                        <h4 class="bbinfo-value">Medical Admission Programme<br>
                            Opposite side of Sonali Bank,Faridpur<br>
                            01734-500238, 01714160997</h4>
                        </h4>
                @else

                <h1 class="m-name">tusher's care</h1>
                <h4 class="bbinfo-value">University Admission Programme<br>
                    Opposite side of Sonali Bank,Faridpur<br>
                    01734-500238, 01714160997</h4>
                </h4>
                @endif

                    </div>
                </div>
                <h4 class="info-valuedate"><span>Expire Date </span> : 30-June-2024
                    <div class="athr">
                        <img src="{{asset('assets/pdf/idcard/image/author2.png')}}" alt="">
                    </div>
                    <hr class="border">

                    <div class="author">
                        <h4>Authoried sincerely</h4>
                    </div>


            </div>

            <div class="id-footer">
                @if ($student->package_id==3)
                <img height="52" src="{{asset('assets/pdf/idcard/image/dmc.png')}}" alt="">
                @else
                <img height="46" src="{{asset('assets/pdf/idcard/image/tc.png')}}" alt="">
                @endif

            </div>
        </div>

    </div>
</body>

</html>
