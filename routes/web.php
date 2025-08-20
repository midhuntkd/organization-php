<?php

use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Auth\CustomLoginController;
use App\Http\Controllers\Auth\CustomRegisterController;
use App\Http\Controllers\Auth\CustomVerifyEmailController;
use App\Http\Controllers\ProfileController;
use App\Models\Membership;
use App\Models\MembershipBenefits;
use App\Models\MembershipCategory;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/member/register/{organization}', [CustomRegisterController::class, 'showRegisterForm'])->name('custom_register');
Route::post('/member/register/{organization}', [CustomRegisterController::class, 'register'])->name('custom_register.submit');

Route::get('/admin/login/', [CustomLoginController::class, 'showLoginForm'])->name('admin_login');
Route::post('/admin/login', [CustomLoginController::class, 'login'])->name('admin_login.submit');

Route::get('/member/login/{organization}', [CustomLoginController::class, 'showMemberLoginForm'])->name('member_login');
Route::post('/member/login/{organization}', [CustomLoginController::class, 'organizationLogin'])->name('custom_member_login.submit');

Route::post('/account/logout', [CustomLoginController::class, 'logout'])->name('custom_logout');

Route::get('/email/verify/{id}/{hash}', CustomVerifyEmailController::class)
    ->middleware(['signed', 'throttle:6,1'])   // no 'auth' here
    ->name('custom.verification.verify');










Route::get('/admin/member/list', [MemberController::class, 'showMemberList'])->name('admin.member.list');
Route::get('/admin/member/approve/{id}', [MemberController::class, 'approve'])->name('admin.member.approve');
Route::delete('/admin/member/reject/{id}', [MemberController::class, 'reject'])->name('admin.member.reject');
Route::resource('admin/memership', Membership::class);
Route::resource('admin/memershipCategory', MembershipCategory::class);
Route::resource('admin/memershipBenefits', MembershipBenefits::class);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
