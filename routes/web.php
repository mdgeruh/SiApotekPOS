<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\ReceiptController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Profile Routes
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile/change-password', [UserController::class, 'changePasswordForm'])->name('profile.change-password');
    Route::put('/profile/change-password', [UserController::class, 'changePassword'])->name('profile.change-password.update');
    Route::get('/profile/activities', [UserController::class, 'activities'])->name('profile.activities');
    Route::get('/settings', [UserController::class, 'settings'])->name('settings');
    Route::put('/settings', [UserController::class, 'updateSettings'])->name('settings.update');

    // UI Components Demo
    Route::get('/components-demo', function () {
        return view('components-demo');
    })->name('components.demo');

    // Thumbnail Demo
    Route::get('/thumbnail-demo', function () {
        return view('app-settings.thumbnail-demo');
    })->name('thumbnail.demo');

    // Medicines
    Route::get('/medicines/add-stock', [MedicineController::class, 'addStockForm'])->name('medicines.add-stock');
    Route::post('/medicines/add-stock', [MedicineController::class, 'addStock'])->name('medicines.add-stock.store');
    Route::get('/medicines/log', [MedicineController::class, 'log'])->name('medicines.log');
    Route::resource('medicines', MedicineController::class);
    Route::patch('/medicines/{medicine}/archive', [MedicineController::class, 'archive'])->name('medicines.archive');
    Route::patch('/medicines/{medicine}/restore', [MedicineController::class, 'restore'])->name('medicines.restore');
    Route::get('/medicines/{medicine}/stock-update', [MedicineController::class, 'stockUpdateForm'])->name('medicines.stock-update');
    Route::patch('/medicines/{medicine}/stock-update', [MedicineController::class, 'stockUpdate'])->name('medicines.stock-update.update');

    // Categories
    Route::resource('categories', CategoryController::class);

    // Brands
    Route::resource('brands', BrandController::class);
    Route::post('/brands/{brand}/toggle-status', [BrandController::class, 'toggleStatus'])->name('brands.toggle-status');
    Route::get('/categories-test', function () {
        return view('categories.test-delete');
    })->name('categories.test');

    // Sales
    Route::get('/sales/log', [SaleController::class, 'log'])->name('sales.log');
    Route::resource('sales', SaleController::class);
    Route::patch('/sales/{sale}/archive', [SaleController::class, 'archive'])->name('sales.archive');
    Route::patch('/sales/{sale}/restore', [SaleController::class, 'restore'])->name('sales.restore');
    Route::get('/sales-test', function () {
        return view('sales.test');
    })->name('sales.test');

    Route::get('/simple-test', function () {
        return view('sales.simple-test');
    })->name('simple.test');

    // Reports
    Route::get('/reports/sales', [ReportController::class, 'salesReport'])->name('reports.sales');
    Route::get('/reports/statistics', [ReportController::class, 'salesStatistics'])->name('reports.statistics');
    Route::get('/reports/stock', [ReportController::class, 'stockReport'])->name('reports.stock');
    Route::get('/reports/export', [ReportController::class, 'exportSalesReport'])->name('reports.export');
    Route::get('/reports/export-stock', [ReportController::class, 'exportStockReport'])->name('reports.export-stock');
    Route::get('/reports/export-statistics', [ReportController::class, 'exportStatisticsReport'])->name('reports.export-statistics');



    // New export routes
    Route::get('/reports/export-excel', [ReportController::class, 'exportSalesReport'])->name('reports.export-excel');
    Route::get('/reports/export-pdf', [ReportController::class, 'exportSalesReport'])->name('reports.export-pdf');
    Route::get('/reports/export-stock-excel', [ReportController::class, 'exportStockReport'])->name('reports.export-stock-excel');
    Route::get('/reports/export-stock-pdf', [ReportController::class, 'exportStockReport'])->name('reports.export-stock-pdf');
    Route::get('/reports/export-statistics-excel', [ReportController::class, 'exportStatisticsReport'])->name('reports.export-statistics-excel');
    Route::get('/reports/export-statistics-pdf', [ReportController::class, 'exportStatisticsReport'])->name('reports.export-statistics-pdf');
    Route::get('/reports/print-sales', [ReportController::class, 'printSalesReport'])->name('reports.print-sales');
    Route::get('/reports/print-statistics', [ReportController::class, 'printStatisticsReport'])->name('reports.print-statistics');
    Route::get('/reports/print-stock', [ReportController::class, 'printStockReport'])->name('reports.print-stock');

    // Receipts
    Route::get('/receipts/{sale}/print', [ReceiptController::class, 'print'])->name('receipts.print');

    // Sales additional routes
    Route::get('/sales/{sale}/print-receipt', [SaleController::class, 'printReceipt'])->name('sales.print-receipt');
    Route::get('/sales/{sale}/print-view', [SaleController::class, 'printReceiptView'])->name('sales.print-view');
    Route::get('/receipts/{sale}/pdf', [ReceiptController::class, 'pdf'])->name('receipts.pdf');
    Route::get('/receipts/{sale}/data', [ReceiptController::class, 'getReceiptData'])->name('receipts.data');

    // Users (Admin only)
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

        // App Settings
        Route::get('/app-settings', [AppSettingController::class, 'index'])->name('app-settings.index');
        Route::put('/app-settings', [AppSettingController::class, 'update'])->name('app-settings.update');


    });


});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
