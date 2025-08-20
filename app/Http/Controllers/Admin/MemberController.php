<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function showMemberList()
    {
        // $organizations = Organization::all();
        // return view('auth.login', compact('organizations'));
        $members = User::whereHas('organization', function ($query) {
            $query->where('admin_organization', 0);
        })->get();

        return view('admin.member_list', compact('members'));
    }
    
    public function approve($id)
    {
        $member = User::findOrFail($id);
        // Logic to approve the member
        // For example, you might set a status or send a notification
        $member->approved = true; // Assuming 'approved' is a boolean field
        $member->save();

        return redirect()->route('admin.member.list')->with('success', 'Member approved successfully.');
    }   
    
    public function reject($id)
    {
        $member = User::findOrFail($id);
        // Logic to reject the member
        // For example, you might delete the member or set a status
        $member->delete(); // Assuming you want to delete the member

        return redirect()->route('admin.member.list')->with('success', 'Member rejected successfully.');
    }


}
