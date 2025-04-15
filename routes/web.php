<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/get-employees/{branchId}', [DashboardController::class, 'getEmployeeByBranch'])->name('employees.by.branch');
Route::get('/export/excel', [DashboardController::class, 'exportExcel'])->name('export.excel');
Route::get('/export/csv', [DashboardController::class, 'exportCsv'])->name('export.csv');
Route::get('/export/pdf', [DashboardController::class, 'exportPdf'])->name('export.pdf');

