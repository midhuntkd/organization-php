<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserViewModeController extends Controller
{
    /**
     * Persist the authenticated user's preferred view mode.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'view_mode' => ['required', Rule::in(['dark', 'light'])],
        ]);

        $user = $request->user();

        if (!$user) {
            abort(403);
        }

        $user->update(['view_mode' => $request->input('view_mode')]);

        return response()->json(['status' => 'ok']);
    }
}
