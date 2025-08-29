<?php

use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\MembershipBenefitController;
use App\Http\Controllers\Admin\MembershipCategoryController;
use App\Http\Controllers\Admin\MembershipController;
use App\Http\Controllers\Admin\MembershipRuleController;
use App\Http\Controllers\Auth\CustomLoginController;
use App\Http\Controllers\Auth\CustomRegisterController;
use App\Http\Controllers\Auth\CustomVerifyEmailController;
use App\Http\Controllers\Member\MemberAccountController;
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

// AJAX: send OTP to email
Route::post('/member/register/send-otp/{organization}', [CustomRegisterController::class, 'sendOtp'])
    ->name('custom_register.send_otp');

// NEW: verify OTP (AJAX)
Route::post('/member/register/verify-otp/{organization}', [CustomRegisterController::class, 'verifyOtp'])
    ->name('custom_register.verify_otp');    

Route::get('/admin/login/', [CustomLoginController::class, 'showLoginForm'])->name('admin_login');
Route::post('/admin/login', [CustomLoginController::class, 'login'])->name('admin_login.submit');

Route::get('/member/login/{organization}', [CustomLoginController::class, 'showMemberLoginForm'])->name('member_login');
Route::post('/member/login/{organization}', [CustomLoginController::class, 'organizationLogin'])->name('custom_member_login.submit');

Route::get('/account/{organization:slug}/logout', [CustomLoginController::class, 'logout'])->name('custom_logout');

Route::get('/email/verify/{id}/{hash}', CustomVerifyEmailController::class)
    ->middleware(['signed', 'throttle:6,1'])   // no 'auth' here
    ->name('custom.verification.verify');

Route::post('/auth/precheck/{organization:slug?}', [CustomLoginController::class, 'check'])
    ->middleware('throttle:10,1') // prevent abuse
    ->name('auth.precheck');


Route::middleware(['auth', 'role:organization-admin'])
    ->prefix('{organization:slug}/admin')    
    ->name('orgadmin.')
    ->scopeBindings()
    ->group(function () {
        Route::middleware(['org.context'])    // custom, see below
            ->group(function () {

                Route::get('/dashboard', [MemberController::class, 'showMemberList'])
                    ->name('dashboard');
                //Route::resource('/members', OrgUserController::class);
                Route::get('/member/list', [MemberController::class, 'showMemberList'])->name('members');
                Route::get('/member/list/approvals', [MemberController::class, 'showApprovalMemberList'])->name('members.myapprovals');
                Route::get('/member/approve/{user}', [MemberController::class, 'approve'])->name('member.approve');
                
                Route::post('/member/approve/{user}', [MemberController::class, 'approve'])
                    ->name('member.approve.post'); // for AJAX

                Route::post('/members/{user}/reject',  [MemberController::class, 'reject'])
                    ->name('member.reject');

                Route::get('/members/{user}/view',  [MemberController::class, 'view'])
                    ->name('member.view');

                Route::post('/members/{user}/update',  [MemberController::class, 'update'])
                    ->name('member.update');    

                Route::resource('membership-categories', MembershipCategoryController::class);
                Route::resource('memberships', MembershipController::class);

                // Nested resources for rules & benefits
                Route::resource('memberships.rules', MembershipRuleController::class)
                    ->shallow()->except(['show']); 
                Route::resource('memberships.benefits', MembershipBenefitController::class)
                    ->shallow()->except(['show']);    
                    });
    });


// This is for organization members who need to change their password on first login
// They will be redirected here if they haven't changed their password yet.

Route::middleware(['auth', 'role:member'])
    ->prefix('{organization:slug}/member')     // e.g. /acme/admin/...
    ->name('member.')
    ->group(function () {
        Route::middleware(['org.context'])    // custom, see below
            ->group(function () {

                Route::get('/password/change', [MemberAccountController::class, 'showChangePassword'])
                    ->name('password.change');
                Route::post('/password/change', [MemberAccountController::class, 'updatePassword'])
                    ->name('password.change.submit');

                // Member dashboard (protect with "force.password.change" so they must change it first)
                Route::middleware(['force.password.change'])->group(function () {

                    Route::get('/dashboard', [MemberAccountController::class, 'dashboard'])
                        ->name('dashboard');
                        
                });     

            });
    });


    




Route::get('/admin/member/list', [MemberController::class, 'showMemberList'])->name('admin.member.list');

Route::delete('/admin/member/reject/{id}', [MemberController::class, 'reject'])->name('admin.member.reject');
Route::resource('admin/memership', Membership::class);
Route::resource('admin/memershipCategory', MembershipCategory::class);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
