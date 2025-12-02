<?php

use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\MembershipBenefitController;
use App\Http\Controllers\Admin\MembershipCategoryController;
use App\Http\Controllers\Admin\MembershipPlanUpgradeController;
use App\Http\Controllers\Admin\MembershipController;
use App\Http\Controllers\Admin\MembershipRuleController;
use App\Http\Controllers\Auth\CustomLoginController;
use App\Http\Controllers\Auth\CustomRegisterController;
use App\Http\Controllers\Auth\CustomVerifyEmailController;
use App\Http\Controllers\Member\MemberAccountController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\OrganizationController;
use App\Models\Membership;
use App\Models\MembershipBenefits;
use App\Models\MembershipCategory;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SuperAdmin\LoginController as SuperAdminLoginController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\Auth\OrgPasswordResetLinkController;
use App\Http\Controllers\Auth\OrgNewPasswordController;
use App\Http\Controllers\UserViewModeController;
use App\Http\Controllers\AccountPasswordController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/member/register/{organization}', [CustomRegisterController::class, 'showRegisterForm'])->name('custom_register');
Route::post('/member/register/{organization}', [CustomRegisterController::class, 'register'])->name('custom_register.submit');

Route::post('/member/register/{organization}/ajax', [CustomRegisterController::class, 'registerAjax'])->name('custom_register.ajax_submit');

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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/member/id-card', [App\Http\Controllers\Member\ProfileController::class, 'showIdCard'])->name('member.id-card');
    Route::get('/member/id-card/download', [App\Http\Controllers\Member\ProfileController::class, 'downloadIdCard'])->name('member.id-card.download');
});

Route::post('/auth/precheck/{organization:slug?}', [CustomLoginController::class, 'check'])
    ->middleware('throttle:10,1') // prevent abuse
    ->name('auth.precheck');


Route::get('/member/register/{organization}/resubmit/{user}', [CustomRegisterController::class, 'resubmit'])->name('member_register.resubmit_view');
Route::post('/member/register/{organization}/resubmit/{user}', [CustomRegisterController::class, 'resubmitAction'])->name('member_register.resubmit');

Route::middleware(['auth', 'role:organization-admin|member'])
    ->prefix('{organization:slug}/admin')    
    ->name('orgadmin.')
    ->scopeBindings()
    ->group(function () {
        Route::middleware(['org.context'])    // custom, see below
            ->group(function () {

                Route::get('/dashboard', [MemberController::class, 'showMemberList'])
                    ->middleware('org.permission:access.members|access.memberships')
                    ->name('dashboard');

                Route::middleware('org.permission:access.members')->group(function () {
                    Route::get('/member/list', [MemberController::class, 'showMemberList'])->name('members');
                    Route::get('/member/list/approvals', [MemberController::class, 'showApprovalMemberList'])->name('members.myapprovals');
                    Route::get('/member/permissions', [MemberController::class, 'permissionIndex'])->name('members.permissions');
                    Route::get('/member/approve/{user}', [MemberController::class, 'approve'])->name('member.approve');
                    
                    Route::post('/member/approve/{user}', [MemberController::class, 'approve'])
                        ->name('member.approve.post'); // for AJAX

                    Route::post('/members/{user}/reject',  [MemberController::class, 'reject'])
                        ->name('member.reject');

                    Route::get('/members/{user}/view',  [MemberController::class, 'view'])
                        ->name('member.view');

                    Route::post('/members/{user}/update',  [MemberController::class, 'update'])
                        ->name('member.update');    

                    Route::post('/members/{user}/permissions', [MemberController::class, 'updatePermissions'])
                        ->name('member.permissions');
                });

                Route::middleware('org.permission:access.memberships')->group(function () {
                    Route::resource('membership-categories', MembershipCategoryController::class);
                    Route::resource('memberships', MembershipController::class);
                    Route::get('/membership-upgrade-requests', [MembershipPlanUpgradeController::class, 'index'])
                        ->name('membership_upgrades.index');
                    Route::get('/membership-upgrade-requests/{upgradeRequest}', [MembershipPlanUpgradeController::class, 'show'])
                        ->name('membership_upgrades.show');
                    Route::post('/membership-upgrade-requests/{upgradeRequest}/approve', [MembershipPlanUpgradeController::class, 'approve'])
                        ->name('membership_upgrades.approve');
                    Route::post('/membership-upgrade-requests/{upgradeRequest}/reject', [MembershipPlanUpgradeController::class, 'reject'])
                        ->name('membership_upgrades.reject');

                    // Nested resources for rules & benefits
                    Route::resource('membership-rules', MembershipRuleController::class)
                    ->except(['show'])
                    ->names('membership_rules');

                    Route::resource('membership-benefits', MembershipBenefitController::class)
                        ->except(['show'])
                        ->names('membership_benefits');
                });

                Route::middleware('org.permission:access.payments')->group(function () {
                    Route::get('/payments', [\App\Http\Controllers\Admin\PaymentApprovalController::class, 'index'])
                        ->name('payments.index');
                    Route::get('/payments/{ledger}', [\App\Http\Controllers\Admin\PaymentApprovalController::class, 'show'])
                        ->name('payments.show');
                    Route::post('/payments/{ledger}/approve', [\App\Http\Controllers\Admin\PaymentApprovalController::class, 'approve'])
                        ->name('payments.approve');
                    Route::post('/payments/{ledger}/reject', [\App\Http\Controllers\Admin\PaymentApprovalController::class, 'reject'])
                        ->name('payments.reject');

                    Route::get('/transactions', [MemberAccountController::class, 'transactions'])
                        ->name('transactions.index');
                });

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
                            Route::post('/payments', [\App\Http\Controllers\Member\PaymentController::class, 'store'])
                                ->name('payments.store');
                            Route::get('/transactions', [MemberAccountController::class, 'transactions'])
                                ->name('transactions.index');

                            Route::get('/memberships/upgrade', [MemberAccountController::class, 'showUpgradeMembership'])
                                ->name('membership.upgrade');
                            Route::get('/memberships/{membership}/details', [MemberAccountController::class, 'getMembershipDetails'])
                                ->name('membership.details');
                    Route::post('/memberships/change', [MemberAccountController::class, 'changeMembership'])
                        ->name('membership.change');
                        
                });     

                Route::get('/profile', [\App\Http\Controllers\Member\ProfileController::class, 'edit'])->name('profile.edit');
                Route::post('/profile', [\App\Http\Controllers\Member\ProfileController::class, 'update'])->name('profile.update');


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
    Route::post('/user/view-mode', UserViewModeController::class)->name('user.view-mode');
    Route::get('/account/password', [AccountPasswordController::class, 'edit'])->name('account.password.change');
    Route::post('/account/password', [AccountPasswordController::class, 'update'])->name('account.password.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::prefix('super-admin')->name('superadmin.')->group(function () {
    Route::get('/login', [SuperAdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [SuperAdminLoginController::class, 'login'])->name('login.submit');
    Route::post('/logout', [SuperAdminLoginController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'role:super-admin'])->group(function () {
        Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
    });

    Route::post('/auth/precheck', [SuperAdminLoginController::class, 'check'])
    ->middleware('throttle:10,1') // prevent abuse
    ->name('auth.precheck');
});

Route::middleware(['auth', 'role:super-admin'])
    ->prefix('super-admin')
    ->name('superadmin.')
    ->group(function () {
        Route::resource('organizations', OrganizationController::class);
        Route::post('organizations/{organization}/resend-invite', [OrganizationController::class, 'resendInvite'])
            ->name('organizations.resendInvite');
        Route::post('organizations/{organization}/reset-password', [OrganizationController::class, 'resetAdminPassword'])
            ->name('organizations.resetPassword');
    });

require __DIR__.'/auth.php';

// Guest, organization-scoped password reset (custom design)
Route::middleware('guest')->group(function () {
    Route::get('/member/forgot-password/{organization:slug}', [OrgPasswordResetLinkController::class, 'create'])
        ->name('org.password.request');

    Route::post('/member/forgot-password/{organization:slug}', [OrgPasswordResetLinkController::class, 'store'])
        ->name('org.password.email');

    Route::get('/member/reset-password/{organization:slug}/{token}', [OrgNewPasswordController::class, 'create'])
        ->name('org.password.reset');

    Route::post('/member/reset-password/{organization:slug}', [OrgNewPasswordController::class, 'store'])
        ->name('org.password.store');
});
