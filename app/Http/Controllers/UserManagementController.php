<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RecruitmentBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin_hrd',
            'is_active' => true,
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'Akun Admin HRD berhasil dibuat.');
    }

    public function edit(User $user)
    {
        // Akun Super Admin tidak dapat dikelola dari menu ini.
        abort_if($user->isSuperAdmin(), 403);

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        abort_if($user->isSuperAdmin(), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'is_active' => ['required', 'boolean'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        // Mencegah Super Admin menonaktifkan akunnya sendiri.
        if ($user->id === auth()->id() && !$validated['is_active']) {
            return back()
                ->withInput()
                ->withErrors([
                    'is_active' => 'Anda tidak dapat menonaktifkan akun sendiri.',
                ]);
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->is_active = $validated['is_active'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'Data Admin HRD berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Tidak boleh menghapus akun sendiri atau Super Admin.
        if ($user->id === auth()->id() || $user->isSuperAdmin()) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Akun ini tidak dapat dihapus.');
        }

        // Pertahankan akun yang sudah memiliki riwayat batch.
        $hasRecruitmentHistory = RecruitmentBatch::where(
            'created_by',
            $user->id
        )->exists();

        if ($hasRecruitmentHistory) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Akun memiliki riwayat Recruitment Batch. Nonaktifkan akun jika tidak lagi digunakan.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Akun Admin HRD berhasil dihapus.');
    }
}
