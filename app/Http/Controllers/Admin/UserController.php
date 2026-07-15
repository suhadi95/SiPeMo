<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            match ($request->role) {
                'admin' => $query->where('is_admin', true),
                'penyusun' => $query->where('is_penyusun', true),
                'lpm' => $query->where('is_lpm', true),
                'reviewer' => $query->where('is_reviewer', true),
                'user' => $query->where('is_admin', false)
                    ->where('is_penyusun', false)
                    ->where('is_lpm', false)
                    ->where('is_reviewer', false),
                default => null,
            };
        }

        $perPage = (int) $request->get('per_page', 15);
        $allowedPerPage = [15, 30, 60, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 15;
        }

        $users = $query->orderBy('name')->paginate($perPage)->withQueryString();

        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'is_admin' => 'boolean',
            'is_penyusun' => 'boolean',
            'is_lpm' => 'boolean',
            'is_reviewer' => 'boolean',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_admin' => $request->has('is_admin'),
                'is_penyusun' => $request->has('is_penyusun'),
                'is_lpm' => $request->has('is_lpm'),
                'is_reviewer' => $request->has('is_reviewer'),
            ]);

            Log::info('User created by admin', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name
            ]);

            return redirect()->route('admin.user.index')
                ->with('success', 'User berhasil dibuat.');
                
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membuat user: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'is_admin' => 'boolean',
            'is_penyusun' => 'boolean',
            'is_lpm' => 'boolean',
            'is_reviewer' => 'boolean',
        ]);

        try {
            $wasReviewer = (bool) $user->is_reviewer;
            $isReviewer = $request->has('is_reviewer');

            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'is_admin' => $request->has('is_admin'),
                'is_penyusun' => $request->has('is_penyusun'),
                'is_lpm' => $request->has('is_lpm'),
                'is_reviewer' => $isReviewer,
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            // Lepas penugasan mata kuliah jika role reviewer dicabut
            if ($wasReviewer && !$isReviewer) {
                MataKuliah::where('reviewer_id', $user->id)->update(['reviewer_id' => null]);
            }

            Log::info('User updated by admin', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name
            ]);

            return redirect()->route('admin.user.index')
                ->with('success', 'User berhasil diperbarui.');
                
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui user: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(User $user)
    {
        try {
            // Jangan biarkan admin menghapus dirinya sendiri
            if ($user->id === auth()->id()) {
                return redirect()->back()
                    ->with('error', 'Anda tidak dapat menghapus akun sendiri.');
            }

            $userName = $user->name;
            $userEmail = $user->email;
            
            $user->delete();

            Log::info('User deleted by admin', [
                'deleted_user_name' => $userName,
                'deleted_user_email' => $userEmail,
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name
            ]);

            return redirect()->route('admin.user.index')
                ->with('success', 'User berhasil dihapus.');
                
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus user: ' . $e->getMessage());
        }
    }
}
