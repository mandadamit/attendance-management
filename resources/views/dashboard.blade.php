<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Branch Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
    }
    .card {
      border-radius: 16px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }
    .dashboard-title {
      margin-top: 30px;
      margin-bottom: 20px;
    }
    .filter-section {
      margin-bottom: 25px;
    }
  </style>
</head>
<body>

<div class="container py-4">
  <!-- Flash Message -->
  <!-- <div id="flash-message">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      Operation completed successfully!
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div> -->

  <!-- Dashboard Title -->
  <h2 class="dashboard-title text-center">Branch Dashboard</h2>

  <!-- Filter Section -->
  <div class="row filter-section">
    <form method="GET" action="{{ route('dashboard') }}">
      <div class="row">
        <div class="col-md-3">
          <label for="branch" class="form-label">Branch</label>
          <select name="branch" id="branch" class="form-select">
            <option value="" disabled selected>All Branches</option>
            @forelse($branches as $branch)
              <option value="{{ $branch->id }}" @selected($selectedBranch == $branch->id)>{{ $branch->name }}</option>
            @empty
              <option disabled>No branches found</option>
            @endforelse
          </select>
        </div>

        <div class="col-md-3">
          <label for="month" class="form-label">Month</label>
          <select name="month" id="month" class="form-select">
            <option value="April 2025" {{ $selectedMonth == 'April 2025' ? 'selected' : '' }}>April 2025</option>
            <option value="March 2025" {{ $selectedMonth == 'March 2025' ? 'selected' : '' }}>March 2025</option>
            <option value="February 2025" {{ $selectedMonth == 'February 2025' ? 'selected' : '' }}>February 2025</option>
            <!-- Add more months as needed -->
          </select>
        </div>

        <div class="col-md-3">
          <label for="employee" class="form-label">Employee</label>
          <select name="employee" id="employee" class="form-select">
            <option value="" disabled selected>All Employees</option>
            @forelse($employees as $employee)
              <option value="{{ $employee->id }}" @selected($selectedEmployee == $employee->id)>{{ $employee->name }}</option>
            @empty
              <option disabled>No employee found</option>
            @endforelse
          </select>
        </div>

        <div class="col-md-3 d-flex align-items-end gap-2">
          <button type="submit" class="btn btn-primary w-50">Search</button>
          <a href="{{ route('dashboard') }}" class="btn btn-danger w-50">Reset</a>
        </div>
      </div>
    </form>

  </div>

  <!-- Dashboard Cards -->
  <div class="row g-4">
    <!-- Total Employees -->
    <div class="col-md-3">
      <div class="card text-white bg-primary h-100">
        <div class="card-body text-center">
          <h6 class="card-title">Total Employees</h6>
          <h2>{{@$totalEmployees}}</h2>
        </div>
      </div>
    </div>

    <!-- Avg Attendance -->
    <div class="col-md-3">
      <div class="card text-white bg-success h-100">
        <div class="card-body text-center">
          <h6 class="card-title">Avg Attendance %</h6>
          <h2>{{@$averageAttendance}}%</h2>
        </div>
      </div>
    </div>

    <!-- Employee of the Month -->
    <div class="col-md-3">
      <div class="card text-white bg-secondary h-100">
        <div class="card-body text-center">
          <h6 class="card-title">Employee of the Month</h6>
          <h4>{{@$employeeOfMonth->name??'---'}}</h4>
        </div>
      </div>
    </div>

    <!-- >90% Attendance -->
    <div class="col-md-3">
      <div class="card text-white bg-warning h-100">
        <div class="card-body text-center">
          <h6 class="card-title">>90% Attendance</h6>
          <h2>{{@$highAttendanceCount}}</h2>
        </div>
      </div>
    </div>
  </div>
  <div class='mt-4'>
    <div class="row">
      <div class="col-md-8">
        <h4>Employee Attendance List ({{ $selectedMonth }})</h4>
      </div>
      <div class="col-md-4">
        <div class="btn-group my-3 mt-0">
          <a href="{{ route('export.excel', request()->query()) }}" class="btn btn-success">Export Excel</a>
          <a href="{{ route('export.csv', request()->query()) }}" class="btn btn-primary">Export CSV</a>
          <a href="{{ route('export.pdf', request()->query()) }}" class="btn btn-danger">Export PDF</a>
        </div>
      </div>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Branch</th>
                <th>Present Days</th>
                <th>Total Days</th>
                <th>Attendance %</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employeeAttendanceStats as $data)
                <tr>
                    <td>{{ $data->employee->name }}</td>
                    <td>{{ $data->employee->branch->name ?? 'N/A' }}</td>
                    <td>{{ $data->present_days }}</td>
                    <td>{{ $data->total_days }}</td>
                    <td>{{ $data->percentage }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No employee data available for selected filters.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $('#branch').change(function() {
    var branchId = $(this).val();
    if (branchId) {
      $.ajax({
        url: '/get-employees/' + branchId,
        type: 'GET',
        success: function(data) {
          $('#employee').empty().append('<option value="" disabled selected>All Employees</option>');
          $.each(data, function(key, employee) {
            $('#employee').append('<option value="' + employee.id + '">' + employee.name + '</option>');
          });
        }
      });
    } else {
      $('#employee').empty().append('<option value="">All Employees</option>');
    }
  });
</script>

</body>
</html>
