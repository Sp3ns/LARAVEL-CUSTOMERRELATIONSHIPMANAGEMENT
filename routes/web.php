<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FollowUpController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportExportController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
Web Routes — CRM System
*/

Route::get('/', function () {
    return auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// ── Authenticated Routes ─────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── Customers ────────────────────────────────────────────────────────
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/',           [CustomerController::class, 'index'])->name('index');
        Route::get('/create',     [CustomerController::class, 'create'])->name('create');
        Route::post('/',          [CustomerController::class, 'store'])->name('store');
        Route::get('/{customer}',      [CustomerController::class, 'show'])->name('show');
        Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::put('/{customer}',      [CustomerController::class, 'update'])->name('update');
        Route::delete('/{customer}',   [CustomerController::class, 'destroy'])->name('destroy');
    });

    // ── Leads ────────────────────────────────────────────────────────────
    Route::prefix('leads')->name('leads.')->group(function () {
        Route::get('/',          [LeadController::class, 'index'])->name('index');
        Route::get('/create',    [LeadController::class, 'create'])->name('create');
        Route::post('/',         [LeadController::class, 'store'])->name('store');
        Route::get('/{lead}',         [LeadController::class, 'show'])->name('show');
        Route::get('/{lead}/edit',    [LeadController::class, 'edit'])->name('edit');
        Route::put('/{lead}',         [LeadController::class, 'update'])->name('update');
        Route::delete('/{lead}',      [LeadController::class, 'destroy'])->name('destroy');
        Route::post('/{lead}/convert', [LeadController::class, 'convert'])->name('convert');
    });

    // ── Activities ───────────────────────────────────────────────────────
    Route::prefix('activities')->name('activities.')->group(function () {
        Route::get('/',              [ActivityController::class, 'index'])->name('index');
        Route::get('/create',        [ActivityController::class, 'create'])->name('create');
        Route::post('/',             [ActivityController::class, 'store'])->name('store');
        Route::get('/{activity}',         [ActivityController::class, 'show'])->name('show');
        Route::get('/{activity}/edit',    [ActivityController::class, 'edit'])->name('edit');
        Route::put('/{activity}',         [ActivityController::class, 'update'])->name('update');
        Route::delete('/{activity}',      [ActivityController::class, 'destroy'])->name('destroy');
    });

    // ── Follow-Ups ───────────────────────────────────────────────────────
    Route::prefix('follow-ups')->name('follow-ups.')->group(function () {
        Route::get('/',               [FollowUpController::class, 'index'])->name('index');
        Route::get('/create',         [FollowUpController::class, 'create'])->name('create');
        Route::post('/',              [FollowUpController::class, 'store'])->name('store');
        Route::get('/{followUp}',          [FollowUpController::class, 'show'])->name('show');
        Route::get('/{followUp}/edit',     [FollowUpController::class, 'edit'])->name('edit');
        Route::put('/{followUp}',          [FollowUpController::class, 'update'])->name('update');
        Route::delete('/{followUp}',       [FollowUpController::class, 'destroy'])->name('destroy');
        Route::patch('/{followUp}/complete', [FollowUpController::class, 'complete'])->name('complete');
        Route::patch('/{followUp}/reopen',   [FollowUpController::class, 'reopen'])->name('reopen');
    });

    // ── Reports ──────────────────────────────────────────────────────────
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/',              [ReportController::class, 'index'])->name('index');
        Route::get('/customers',     [ReportController::class, 'customers'])->name('customers');
        Route::get('/leads',         [ReportController::class, 'leads'])->name('leads');
        Route::get('/pipeline',      [ReportController::class, 'pipeline'])->name('pipeline');
        Route::get('/user-activity', [ReportController::class, 'userActivity'])->name('user-activity');
        Route::get('/follow-ups',    [ReportController::class, 'followUps'])->name('follow-ups');
    });

    // ── User Management  ─────────────────────────────────────
    Route::prefix('users')->name('users.')->middleware('role:admin')->group(function () {
        Route::get('/',          [UserManagementController::class, 'index'])->name('index');
        Route::get('/create',    [UserManagementController::class, 'create'])->name('create');
        Route::post('/',         [UserManagementController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
        Route::put('/{user}',      [UserManagementController::class, 'update'])->name('update');
        Route::delete('/{user}',   [UserManagementController::class, 'destroy'])->name('destroy');
    });
    
});

require __DIR__.'/auth.php';
