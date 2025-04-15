<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Present Days</th>
            <th>Total Days</th>
            <th>Attendance %</th>
        </tr>
    </thead>
    <tbody>
        @foreach($employeeAttendanceStats as $row)
            <tr>
                <td>{{ $row->employee->name }}</td>
                <td>{{ $row->present_days }}</td>
                <td>{{ $row->total_days }}</td>
                <td>{{ $row->percentage }}%</td>
            </tr>
        @endforeach
    </tbody>
</table>
