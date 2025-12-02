<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\MembershipPaymentLedger;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function store(Request $request, Organization $organization)
    {
        $user = Auth::user();

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'string', 'in:online_transfer,by_hand,cheque_draft,other'],
            'payment_date' => ['required', 'date'],
            'handover_person' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'proof' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($data['payment_method'] !== 'by_hand') {
            $request->validate([
                'proof' => ['required', 'image', 'max:4096'],
            ]);
        }

        if ($data['payment_method'] === 'by_hand') {
            $request->validate([
                'handover_person' => ['required', 'string', 'max:255'],
            ]);
        }

        $proofPath = null;
        if ($request->hasFile('proof')) {
            $proofPath = $request->file('proof')->store('payment_proofs', 'public');
        }

        MembershipPaymentLedger::create([
            'organization_id' => $organization->id,
            'user_id' => $user->id,
            'membership_id' => $user->membership_id,
            'entry_type' => 'payment',
            'reason' => 'other',
            'amount' => $data['amount'],
            'payment_method' => $data['payment_method'],
            'payment_date' => $data['payment_date'],
            'handover_person' => $data['handover_person'] ?? null,
            'proof_path' => $proofPath,
            'description' => $data['description'] ?? null,
            'status' => 'pending',
        ]);

        return back()->with('status', 'Payment submitted for approval.');
    }
}
