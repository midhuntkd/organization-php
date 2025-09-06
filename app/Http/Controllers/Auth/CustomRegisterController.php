<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RegisterOTPMail;
use App\Mail\RegisterSuccessMail;
use App\Models\EmailOtp;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomRegisterController extends Controller
{
    public function showRegisterForm(Organization $organization)
    {
        //$organization = Organization::where('is_default', true)->first();
        //return view('auth.custom-register', compact('organization'));
        return view('auth.custom-register', compact('organization'));
    }

    /**
     * Create & send a 6-digit OTP. Uses DB (email_otps) not cache.
     */
    public function sendOtp(Request $request, Organization $organization)
    {

        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->where(fn($q) => $q->where('organization_id', $organization->id)),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $email = strtolower($validated['email']);
        $otp   = (string) random_int(100000, 999999);
        $ttl   = 10; // minutes

        // simple resend throttle: 30s between sends
        $existing = EmailOtp::where('organization_id', $organization->id)
            ->where('email', $email)->first();

        if ($existing && $existing->expires_at && $existing->expires_at->isFuture() && !$existing->verified_at) {
            return response()->json(['message' => 'An OTP was already sent and is still valid. Please use that one or wait until it expires.'], 429);
        }

        // upsert (one row per org+email)
        EmailOtp::updateOrCreate(
            ['organization_id' => $organization->id, 'email' => $email],
            [
                'otp'          => $otp,
                'expires_at'   => now()->addMinutes($ttl),
                'verified_at'  => null,
                'attempts'     => 0,
                'last_sent_at' => now(),
                'resend_count' => DB::raw('resend_count + 1'),
            ]
        );

        Mail::to($email)->send(new RegisterOTPMail($organization, $otp));

        return response()->json(['message' => "OTP sent to your email. It expires in {$ttl} minutes.", 'success' => true]);
    }

    /**
     * Verify the OTP via AJAX. Marks row as verified if correct.
     */
    public function verifyOtp(Request $request, Organization $organization)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'otp'   => ['required', 'digits:6'],
        ]);

        $email = strtolower($validated['email']);

        $row = EmailOtp::where('organization_id', $organization->id)
            ->where('email', $email)
            ->first();

        if (!$row) {
            return response()->json(['message' => 'OTP not found. Send again.'], 404);
        }

        if ($row->isExpired()) {
            return response()->json(['message' => 'OTP expired. Please request a new one.'], 422);
        }

        // optional brute force throttle
        if ($row->attempts >= 5) {
            return response()->json(['message' => 'Too many attempts. Request a new OTP.'], 429);
        }

        $row->increment('attempts');

        if ($row->otp !== (string) $validated['otp']) {
            return response()->json(['message' => 'Invalid OTP.'], 422);
        }

        // success
        $row->update(['verified_at' => now()]);

        return response()->json(['message' => 'OTP verified. Continue registration.']);
    }

    public function register(Request $request, Organization $organization)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->where(fn($q) => $q->where('organization_id', $organization->id)),
            ],
            'phone' => 'required|string|max:20',
            'country_of_residence' => 'required|in:India,UAE',
            'verification_type'      => 'required|in:aadhaar,emirates_id',
            'verification_id_number' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $digitsOnly = preg_replace('/\D/', '', $value);

                    if ($request->verification_type === 'aadhaar' && strlen($digitsOnly) !== 12) {
                        $fail('Aadhaar number must be exactly 12 digits.');
                    }

                    if ($request->verification_type === 'emirates_id' && strlen($digitsOnly) !== 15) {
                        $fail('Emirates ID must be exactly 15 digits.');
                    }
                }
            ],
            'id_card_front' => ['required', File::image()->max(20 * 1024)],
            'id_card_back'  => ['required', File::image()->max(20 * 1024)],
        ]);

        $email = strtolower($validated['email']);

        $row = EmailOtp::where('organization_id', $organization->id)
            ->where('email', $email)
            ->first();
        //echo "<pre>";print_r($row); exit;
            

        if (!$row || $row->isExpired() || !$row->verified_at || $row->otp !== (string) $request->otp) {
            return back()->withErrors(['otp' => 'OTP not verified or expired. Please verify again.'])->withInput();
        }

        //$imagePath = $request->file('verification_image')->store('verifications', 'public');

        // store files (configure 'public' disk to your shared-hosting public_html/storage)
        $frontPath = $request->file('id_card_front')->store('verifications', 'public');
        $backPath  = $request->file('id_card_back')->store('verifications', 'public');

        // echo "<pre>";
        // print_r($validated); exit;
        $user = User::create([
            'organization_id'        => $organization->id,
            'name'                   => $validated['name'],
            'email'                  => $email,
            'phone'                  => $request->phone_prefix.$validated['phone'],
            'country_of_residence'   => $validated['country_of_residence'],
            'verification_type'      => $validated['verification_type'],
            'verification_id_number' => $validated['verification_id_number'],
            'id_card_front'          => $frontPath,
            'id_card_back'           => $backPath,
            'password'               => Hash::make(str()->password(12)), // temp, you’ll set via approve flow
            'approved'               => false,
            'rejected'               => false,
        ]);

        if (method_exists($user, 'assignRole')) {
            $user->assignRole('member');
        }

        // delete OTP row to avoid reuse
        $row->delete();

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        Mail::to($email)->send(new RegisterSuccessMail($organization, $user));
        //$user->sendEmailVerificationNotification();

        return redirect()->route('member_login', $organization->slug)->with('status', 'Your acount has been registred successfully. Please wait for admin approval before logging in.');
    }


    public function resubmit(Organization $organization, User $user)
    {
        //$organization = Organization::where('is_default', true)->first();
        //return view('auth.custom-register', compact('organization'));

        if(!$user){
            abort(404);
        }

        if($user->approved){
            return redirect()->route('member_login', $organization->slug)->with('status', 'Your acount already approved. Please login.');
        }

        return view('auth.custom-resubmit-register', compact('organization', 'user'));
    }

    public function resubmitAction(Request $request, Organization $organization, User $user)
    {

        if (!$user) {
            abort(404);
        }

        if ($user->approved) {
            return redirect()->route('member_login', $organization->slug)->with('status', 'Your acount already approved. Please login.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')
                ->ignore($user->id)
                ->where(fn($q) => $q->where('organization_id', $organization->id)),
            ],
            'phone' => 'required|string|max:20',
            'country_of_residence' => 'required|in:India,UAE',
            'verification_type'      => 'required|in:aadhaar,emirates_id',
            'verification_id_number' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $digitsOnly = preg_replace('/\D/', '', $value);

                    if ($request->verification_type === 'aadhaar' && strlen($digitsOnly) !== 12) {
                        $fail('Aadhaar number must be exactly 12 digits.');
                    }

                    if ($request->verification_type === 'emirates_id' && strlen($digitsOnly) !== 15) {
                        $fail('Emirates ID must be exactly 15 digits.');
                    }
                }
            ],
        ]);

        $email = strtolower($validated['email']);

        // $row = EmailOtp::where('organization_id', $organization->id)
        //     ->where('email', $email)
        //     ->first();

        // if (!$row || $row->isExpired() || !$row->verified_at || $row->otp !== (string) $request->otp) {
        //     return back()->withErrors(['otp' => 'OTP not verified or expired. Please verify again.'])->withInput();
        // }

        // store files (configure 'public' disk to your shared-hosting public_html/storage)

        $frontPath = $user->id_card_front;
        $backPath = $user->id_card_back;
        if ($request->hasFile('id_card_front')) {
            $frontPath = $request->file('id_card_front')->store('verifications', 'public');
        }
        if ($request->hasFile('id_card_back')) {
            $backPath  = $request->file('id_card_back')->store('verifications', 'public');
        }

        $phone = preg_replace('/\s+/', '', $request->input('phone')); // strip spaces

        if ($request->country_of_residence === 'UAE') {
            if (!str_starts_with($phone, '+971')) {
                // remove leading zeros if user typed "050..."
                $phone = ltrim($phone, '0');
                $phone = '+971' . $phone;
            }
        } elseif ($request->country_of_residence === 'India') {
            if (!str_starts_with($phone, '+91')) {
                $phone = ltrim($phone, '0');
                $phone = '+91' . $phone;
            }
        }


        $user->update([
            'name'                   => $validated['name'],
            'email'                  => $email,
            'phone'                  => $phone,
            'country_of_residence'   => $validated['country_of_residence'],
            'verification_type'      => $validated['verification_type'],
            'verification_id_number' => $validated['verification_id_number'],
            'id_card_front'          => $frontPath,
            'id_card_back'           => $backPath,
            'password'               => Hash::make(str()->password(12)), // temp, you’ll set via approve flow
            'approved'               => false,
            'rejected'               => false,
        ]);

        if (method_exists($user, 'assignRole')) {
            $user->assignRole('member');
        }

        // delete OTP row to avoid reuse
        //$row->delete();

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        $message = "Your resubmission successfully completed for **{{ $organization->name }}** . You will be notified once admin approved.";
        Mail::to($email)->send(new RegisterSuccessMail($organization, $user, $message, 'Your Resubmission Successful'));
        //$user->sendEmailVerificationNotification();

        return redirect()->route('member_register.resubmit_view', [$organization->slug, $user->id])->with('status', 'Your resubmit request has been registred successfully. Please wait for admin approval before logging in.');
    }

}
