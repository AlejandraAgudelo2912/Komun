<?php

namespace App\Http\Controllers;

use App\Models\AssistantVerificationDocument;
use App\Events\AssistantVerificationDocumentEvent;
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
        $verification->assistant->is_verified = true;
        $verification->assistant->save();

        event(new AssistantVerificationDocumentEvent($verification));

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

        $verification->assistant->is_verified = false;
        $verification->assistant->save();

        event(new AssistantVerificationDocumentEvent($verification));

        return back()->with('error', 'Verificación rechazada.');
    }
}
