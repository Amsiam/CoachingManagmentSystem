<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <table border="1" style="border-collapse:collapse;width:100%;">
        <caption>
            <img src="{{ asset('assets/pdf/form/images/l.png') }}" width="225" alt="">
        </caption>
        <thead style="margin: 10px">

            <tr>
                <td colspan="8" style="text-align: center;">
                    <strong>Admission Report</strong>
                </td>
            </tr>
            <tr class="text-nowrap">
                <th>#</th>
                <th>Roll</th>
                <th>Name</th>
                <th>Batch</th>
                <th>Course</th>
                <th>Package</th>
                <th>Admitted Date</th>
                <th>Admitted BY</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($admission as $student)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $student->roll }}</td>
                    <td>{{ $student->personal_details->name }}</td>
                    <td>
                        @foreach ($student->batches as $batch)
                            @if ($loop->iteration != 1)
                                ,
                            @endif
                            {{ $batch->batch_name }}
                        @endforeach
                    </td>
                    <td>
                        @foreach ($student->courses as $course)
                            @if ($loop->iteration != 1)
                                ,
                            @endif
                            {{ $course->name }}
                        @endforeach
                    </td>
                    <td>{{ $student->package ? $student->package->name : '' }}</td>
                    <td>{{ $student->created_at }}</td>

                    <td>{{ $student->added_by ? $student->added_by->name : '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="border-bottom:1px solid #000;margin-top:50px; width:200px;">
        {{ auth()->user()->name }}
    </div>
</body>

</html>
