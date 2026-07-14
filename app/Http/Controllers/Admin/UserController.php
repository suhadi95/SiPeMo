<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->paginate(10);
        
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
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_admin' => $request->has('is_admin'),
                'is_penyusun' => $request->has('is_penyusun'),
                'is_lpm' => $request->has('is_lpm'),
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
        ]);

        try {
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'is_admin' => $request->has('is_admin'),
                'is_penyusun' => $request->has('is_penyusun'),
                'is_lpm' => $request->has('is_lpm'),
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

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
