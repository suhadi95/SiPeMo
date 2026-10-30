<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\PenyusunApplication;
use App\Models\ReviewerApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $penyusunApp = null;
        $reviewerApp = null;

        if ($user->is_penyusun) {
            $penyusunApp = PenyusunApplication::where('email', $user->email)->first();
        }

        if ($user->is_reviewer) {
            $reviewerApp = ReviewerApplication::where('email', $user->email)->first();
        }

        return view('profile.edit', [
            'user' => $user,
            'penyusunApp' => $penyusunApp,
            'reviewerApp' => $reviewerApp,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->safe()->only(['name', 'email']));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Update profil Penyusun jika aktor adalah penyusun
        if ($user->is_penyusun) {
            $penyusunApp = PenyusunApplication::where('email', $user->email)->first();
            if ($penyusunApp) {
                $penyusunApp->update([
                    'nama_penyusun' => $request->name,
                    'no_wa' => $request->no_wa ?? $penyusunApp->no_wa,
                    'nip' => $request->nip ?? $penyusunApp->nip,
                    'nidn' => $request->nidn ?? $penyusunApp->nidn,
                ]);
            }
        }

        // Update profil Reviewer jika aktor adalah reviewer
        if ($user->is_reviewer) {
            $reviewerApp = ReviewerApplication::where('email', $user->email)->first();
            if ($reviewerApp) {
                $reviewerApp->update([
                    'nama_reviewer' => $request->name,
                    'no_wa' => $request->no_wa ?? $reviewerApp->no_wa,
                    'nip' => $request->nip ?? $reviewerApp->nip,
                    'nidn' => $request->nidn ?? $reviewerApp->nidn,
                    'nomor_rekening' => $request->nomor_rekening ?? $reviewerApp->nomor_rekening,
                    'nama_bank' => $request->nama_bank ?? $reviewerApp->nama_bank,
                    'nama_pemilik_rekening' => $request->nama_pemilik_rekening ?? $reviewerApp->nama_pemilik_rekening,
                ]);
            }
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
