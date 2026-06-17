<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderAdminController;
use App\Http\Controllers\Admin\CategoryAdminController;
use App\Http\Controllers\Admin\MenuItemAdminController;
use App\Http\Controllers\Admin\PromotionAdminController;
use App\Http\Controllers\Admin\AnnouncementAdminController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Admin\ContactAdminController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Auth\GoogleAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', function () {
        if (auth()->user()?->is_admin) {
            return redirect()->route('admin.profile.edit');
        }

        return app(ProfileController::class)->edit(request());
    })->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::post('profile/send-otp', [AdminProfileController::class, 'sendOtp'])->name('profile.send-otp');
    Route::put('profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');
    Route::resource('orders', OrderAdminController::class)->only(['index', 'show', 'update']);
    Route::resource('categories', CategoryAdminController::class);
    Route::resource('menu-items', MenuItemAdminController::class);
    Route::resource('promotions', PromotionAdminController::class)->except(['show']);
    Route::resource('announcements', AnnouncementAdminController::class)->except(['show']);
    Route::post('announcements/{announcement}/send', [AnnouncementAdminController::class, 'send'])->name('announcements.send');
    Route::resource('contacts', ContactAdminController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::resource('users', UserAdminController::class)->except(['show']);
});

require __DIR__.'/auth.php';
