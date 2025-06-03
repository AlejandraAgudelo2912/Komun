<?php

namespace App\Http\Controllers;

use App\Models\AssistantVerificationDocument;
use Illuminate\Http\Request;

class AssistantVerificationController extends Controller
{
    public function index()
    {
        $verifications = AssistantVerificationDocument::where('status', 'pending')->with('assistant')->get();

        return view('verificator.verifications.index', compact('verifications'));
    }

    public function approve($id)
    {
        $verification = AssistantVerificationDocument::findOrFail($id);
        $verification->update([
            'status' => 'approved',
        ]);

        $user = $verification->assistant->user;
        $user->syncRoles('assistant');

        return back()->with('success', 'Asistente aprobado y rol asignado.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        $verification = AssistantVerificationDocument::findOrFail($id);
        $verification->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        return back()->with('error', 'Verificación rechazada.');
    }
}
