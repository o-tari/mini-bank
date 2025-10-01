<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoanController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::post('/stripe/webhook', [App\Http\Controllers\StripeWebhookController::class, 'handleWebhook']);

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');

    // Notifications
    Route::get('notifications', function() {
        return view('notifications.index');
    })->name('notifications');

    // Loan application routes
    Route::get('loans/apply', function() {
        return view('loans.apply');
    })->name('loans.apply');

    // Transaction routes
    Route::get('transactions', function() {
        return view('transactions.index');
    })->name('transactions.index');
    Route::get('/deposit/success', [App\Http\Controllers\DepositController::class, 'success'])->name('deposit.success');
    Route::get('/deposit/cancel', [App\Http\Controllers\DepositController::class, 'cancel'])->name('deposit.cancel');

    // Loan management routes
    Route::middleware(['role:manager|admin'])->group(function () {
        Route::get('loans', [LoanController::class, 'index'])->name('loans.index');
        Route::get('loans/{loan}', [LoanController::class, 'show'])->name('loans.show');
        Route::post('loans/{loan}/approve', [LoanController::class, 'approve'])->name('loans.approve');
        Route::post('loans/{loan}/reject', [LoanController::class, 'reject'])->name('loans.reject');
        Route::post('loans/{loan}/disburse', [LoanController::class, 'disburse'])->name('loans.disburse');
        Route::patch('loans/{loan}/disbursement-date', [LoanController::class, 'updateDisbursementDate'])->name('loans.update-disbursement-date');
    });

    Route::post('/deposit/initiate', [App\Http\Controllers\DepositController::class, 'initiate'])->name('api.deposit.initiate');

    // Admin routes
    Route::middleware(['role:admin|manager'])->group(function () {
        Route::get('admin/transactions', function() {
            return view('admin.transactions.index');
        })->name('admin.transactions.index');
    });

    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');
});

require __DIR__.'/auth.php';
