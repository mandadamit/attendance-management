<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;
use DB;
use App\Exports\EmployeeAttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $allBranches = Branch::all();
        $allEmployees = Employee::orderBy('name', 'asc')->get();

        $branchId = $request->branch;
        $employeeId = $request->employee;
        $month = $request->month ?? Carbon::now()->format('F Y');

        $startDate = Carbon::parse('01 ' . $month)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $employeesQuery = Employee::query();

        if ($branchId) {
            $employeesQuery->where('branch_id', $branchId);
        }

        if ($employeeId) {
            $employeesQuery->where('id', $employeeId);
        }

        $filteredEmployees = $employeesQuery->orderBy('name')->get();
        $totalEmployees = $filteredEmployees->count();

        $attendanceData = Attendance::whereBetween('date', [$startDate, $endDate])
            ->when($branchId, fn($q) => $q->whereHas('employee', fn($query) => $query->where('branch_id', $branchId)))
            ->when($employeeId, fn($q) => $q->where('employee_id', $employeeId))
            ->get();

        $employeeAttendanceStats = $filteredEmployees->map(function ($employee) use ($attendanceData) {
            $empAttendance = $attendanceData->where('employee_id', $employee->id);
            $presentDays = $empAttendance->where('status', 'Present')->count();
            $totalDays = $empAttendance->count();
            $percentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0;

            return (object)[
                'employee' => $employee,
                'present_days' => $presentDays,
                'total_days' => $totalDays,
                'percentage' => $percentage,
            ];
        });

        // Calculate summary data
        $averageAttendance = round($employeeAttendanceStats->pluck('percentage')->avg(), 2);
        $employeeOfMonth = $employeeAttendanceStats->sortByDesc('percentage')->first()?->employee;
        $highAttendanceEmployees = $employeeAttendanceStats->filter(fn($e) => $e->percentage > 90);

        return view('dashboard', [
            'branches' => $allBranches,
            'employees' => $allEmployees,
            'selectedBranch' => $branchId,
            'selectedEmployee' => $employeeId,
            'selectedMonth' => $month,
            'totalEmployees' => $totalEmployees,
            'averageAttendance' => $averageAttendance,
            'employeeOfMonth' => $employeeOfMonth,
            'highAttendanceCount' => $highAttendanceEmployees->count(),
            'employeeAttendanceStats' => $employeeAttendanceStats,
        ]);
    }

    public function getEmployeeByBranch($branchId)
    {
        $employees = Employee::where('branch_id', $branchId)->orderBy('name')->get();
        return response()->json($employees);
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getEmployeeStats($request);
        return Excel::download(new EmployeeAttendanceExport($data), 'attendance.xlsx');
    }

    public function exportCsv(Request $request)
    {
        $data = $this->getEmployeeStats($request);
        return Excel::download(new EmployeeAttendanceExport($data), 'attendance.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getEmployeeStats($request);
        $pdf = Pdf::loadView('exports.employee_attendance', ['employeeAttendanceStats' => $data]);
        return $pdf->download('attendance.pdf');
    }

    protected function getEmployeeStats(Request $request)
    {
        $branchId = $request->branch;
        $employeeId = $request->employee;
        $month = $request->month ?? Carbon::now()->format('F Y');

        $startDate = Carbon::parse('01 ' . $month)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $employeesQuery = Employee::query();

        if ($branchId) {
            $employeesQuery->where('branch_id', $branchId);
        }

        if ($employeeId) {
            $employeesQuery->where('id', $employeeId);
        }

        $filteredEmployees = $employeesQuery->orderBy('name')->get();

        $attendanceData = Attendance::whereBetween('date', [$startDate, $endDate])
            ->when($branchId, fn($q) => $q->whereHas('employee', fn($query) => $query->where('branch_id', $branchId)))
            ->when($employeeId, fn($q) => $q->where('employee_id', $employeeId))
            ->get();

        return $filteredEmployees->map(function ($employee) use ($attendanceData) {
            $empAttendance = $attendanceData->where('employee_id', $employee->id);
            $presentDays = $empAttendance->where('status', 'Present')->count();
            $totalDays = $empAttendance->count();
            $percentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0;

            return (object)[
                'employee' => $employee,
                'present_days' => $presentDays,
                'total_days' => $totalDays,
                'percentage' => $percentage,
            ];
        });
    }

}
