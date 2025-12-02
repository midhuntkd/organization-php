<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipPaymentLedger;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentApprovalController extends Controller
{
    public function index(Organization $organization)
    {
        $payments = MembershipPaymentLedger::query()
            ->with(['user', 'membership'])
            ->where('organization_id', $organization->id)
            ->where('entry_type', 'payment')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.payments.index', compact('payments', 'organization'));
    }

    public function show(Organization $organization, MembershipPaymentLedger $ledger)
    {
        $this->ensureBelongsToOrganization($ledger, $organization);

        $ledger->load(['user', 'membership']);

        return view('admin.payments.show', [
            'payment' => $ledger,
            'organization' => $organization,
        ]);
    }

    public function approve(Organization $organization, MembershipPaymentLedger $ledger)
    {
        $this->ensureBelongsToOrganization($ledger, $organization);

        if ($ledger->status !== 'pending') {
            return back()->with('error', 'This payment has already been processed.');
        }

        $ledger->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'rejected_by' => null,
            'rejected_at' => null,
            'rejected_reason' => null,
        ]);

        return back()->with('status', 'Payment approved and applied to balance.');
    }

    public function reject(Request $request, Organization $organization, MembershipPaymentLedger $ledger)
    {
        $this->ensureBelongsToOrganization($ledger, $organization);

        if ($ledger->status !== 'pending') {
            return back()->with('error', 'This payment has already been processed.');
        }

        $data = $request->validate([
            'reason' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $ledger->update([
            'status' => 'rejected',
            'rejected_by' => Auth::id(),
            'rejected_at' => now(),
            'rejected_reason' => $data['reason'] . (!empty($data['description']) ? "\n\n" . $data['description'] : ''),
        ]);

        return back()->with('status', 'Payment rejected.');
    }

    protected function ensureBelongsToOrganization(MembershipPaymentLedger $ledger, Organization $organization): void
    {
        if ($ledger->organization_id !== $organization->id) {
            abort(404);
        }
    }
}
