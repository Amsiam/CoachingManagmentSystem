<table>
    <thead style="text-align: center;">
        <tr>
            <td rowspan="2" colspan="9">
                <h1>Tusher's Care</h1></td>
        </tr>
        <tr></tr>
        <tr>
            <th>#</th>
            <th>Roll</th>
            <th>Name</th>
            <th>Batch</th>
            <th>Course</th>
            <th>Package</th>
            <th>Admitted Date</th>
            <th>Admitted By</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>

        @foreach ($students as $student)

        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$student->roll}}</td>
            <td>{{$student->name}}</td>
            <td>{{$student->batches->pluck("name")->implode(",")}}</td>
            <td>{{$student->courses->pluck("name")->implode(",")}}</td>
            <td>{{$student->package->name}} </td>
            <td>{{$student->created_at}}</td>
            <td>{{$student->addedBy ? $student->addedBy->name : ""}}</td>
            <td>{{$student->active?'Active':$student->deactive_reason}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
