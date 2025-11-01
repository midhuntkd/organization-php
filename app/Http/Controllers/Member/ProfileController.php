<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\UserDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $organization = $user->organization;
        $details = $user->details ?: new UserDetail();

        return view('member.profile_edit', compact('user', 'organization', 'details'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();

        // Handle user image upload
        if ($request->hasFile('user_image')) {
            if ($user->user_image && Storage::disk('public')->exists($user->user_image)) {
                Storage::disk('public')->delete($user->user_image);
            }
            $user->user_image = $request->file('user_image')->store('user_images', 'public');
        }

        $user->update([
            'name' => $data['name'],
            'phone' => $data['phone'],
        ]);

        // Update or create user details
        $user->details()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'address_line1' => $data['address_line1'] ?? null,
                'address_line2' => $data['address_line2'] ?? null,
                'city' => $data['city'] ?? null,
                'zipcode' => $data['zipcode'] ?? null,
                'norka_registration_number' => $data['norka_registration_number'] ?? null,
                'permanent_home_address' => $data['permanent_home_address'] ?? null,
                'insurance_provider' => $data['insurance_provider'] ?? null,
                'policy_number' => $data['policy_number'] ?? null,
                'expiry_date' => $data['expiry_date'] ?? null,
                'amount' => $data['amount'] ?? null,
            ]
        );

        return redirect()->back()->with('status', 'Profile updated successfully!');
    }

    public function showIdCard()
    {
        $user = Auth::user();
        $organization = $user->organization;
        $details = $user->details;

        return view('member.id_card', compact('user', 'organization', 'details'));
    }

    public function downloadIdCard()
    {
        $user = Auth::user();
        $organization = $user->organization;
        $details = $user->details;

        // Render Blade view via Dompdf and download
        $pdf = Pdf::loadView('member.id_card_pdf', compact('user', 'organization', 'details'))
            ->setPaper('a4', 'portrait');

        return $pdf->download($user->name . '_id_card.pdf');
    }
}
