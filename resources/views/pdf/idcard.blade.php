<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>idcard</title>

</head>

<body>
    <div style="display: flex; gap: 3px;">
        <div
                        style="
                height: 85mm;
                width: 55mm;
                background: url('{{ asset('assets/img/idcard/front.png') }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;">


            <div style="z-index: 2;">
                <div style="position: relative;
            top: 59px;
            left: 60px;
            ">
                    <img style="border-radius: 10px;" width="90" height="90" src="{{ asset('storage/' . $student->image) }}" />
                </div>

                <div style="position: relative;
                            top: 50px;
                            left: 0;
                            text-align:center;
                            ">

                    <h4 style="color: #0e6699">{{ $student->name }}</h4>
                    <h6
                        style="
                            font-size: 0.8em;
                            text-align: center;
                            margin: 0;
                            margin-block-start: 0;
                            margin-top: -20px;
                            margin-block-end: 0;">
                        Student</h6>

                    <h6
                        style="
                            color:red;
                            font-size: 0.8em;
                            text-align: center;
                            margin: 0;
                            margin-block-start: 0;
                            margin-block-end: 0;">
                        Roll No: {{ $student->roll }}</h6>

                    <h6
                        style="
                            color:red;
                            font-size: 0.8em;
                            text-align: center;
                            margin: 0;
                            margin-block-start: 0;
                            margin-block-end: 0;">
                        Batch:
                        {{ $student->batches->pluck('name')->implode(',') }}
                    </h6>

                </div>


                <div style="position: relative;
                        top: 75px;
                        left: 25px;
                        ">
                    <img width="75%" src="data:image/png;base64,{{ $barCode }}" />
                </div>
            </div>
            <div style="z-index: 1;position: relative;
                        top: -60px;
                        opacity:.1;
                        left: 55px;">

            @if ($student->package_id == 3)

                <img width="100" height="90" src="{{ asset("assets/pdf/idcard/image/dmcscolar.png") }}" alt="">
            @else

            <img width="100" height="100" src="{{ asset("assets/pdf/idcard/image/tusharcare.png") }}" alt="">
            @endif
            </div>


        </div>
        <div
            style="
                height: 85mm;
                width: 55mm;
                background: url('{{ asset('assets/img/idcard/back.png') }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;">



<div style="position: fixed;
            top: 87px;
            opacity:.1;
            left: 283px;">

@if ($student->package_id == 3)

    <img width="100" height="100" src="{{ asset("assets/pdf/idcard/image/dmcscolar.png") }}" alt="">
@else

<img width="100" height="100" src="{{ asset("assets/pdf/idcard/image/tusharcare.png") }}" alt="">
@endif
</div>

<div style="position: fixed;
top: 90px;
left: 255px;">

@if ($student->package_id == 3)

    <img width="136" height="90" src="{{ asset("assets/img/idcard/DMC.png") }}" alt="">
@else

<img width="136" height="75" src="{{ asset("assets/img/idcard/TC.png") }}" alt="">
@endif
</div>



            <div style="position: fixed;
    top: 270px;
    left: 240px;
">
                @if ($student->package_id == 3)
                    <img width="185" height="60" src="{{ asset('assets/pdf/idcard/image/dmc.png') }}" />
                @else
                    <img width="185" height="48" src="{{ asset('assets/pdf/idcard/image/tc-logo.png') }}" />
                @endif
            </div>
        </div>
    </div>
</body>

</html>
