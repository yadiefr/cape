<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Helpers\HostingStorageHelper;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = AdminUser::query();

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('nip', 'like', '%' . $request->search . '%');
            });
        }

        $adminUsers = $query->orderBy('created_at', 'desc')->paginate(15);
        $roles = AdminUser::ROLES;

        return view('admin.admin-users.index', compact('adminUsers', 'roles'));
    }

    public function create()
    {
        $roles = AdminUser::ROLES;
        return view('admin.admin-users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admin_users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:' . implode(',', array_keys(AdminUser::ROLES)),
            'nip' => 'nullable|string|max:50|unique:admin_users,nip',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:L,P',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $data = $request->except(['password_confirmation', 'photo']);
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoPath = HostingStorageHelper::uploadFile($photo, 'admin_photos');
            
            if (!$photoPath) {
                return redirect()->back()->with('error', 'Gagal mengupload foto admin. Silakan coba lagi.');
            }
            
            // Extract filename from path for consistency with existing logic
            $data['photo'] = basename($photoPath);
        }

        AdminUser::create($data);

        return redirect()->route('admin.admin-users.index')
                        ->with('success', 'Admin user berhasil ditambahkan!');
    }

    public function show(AdminUser $adminUser)
    {
        return view('admin.admin-users.show', compact('adminUser'));
    }

    public function edit(AdminUser $adminUser)
    {
        $roles = AdminUser::ROLES;
        return view('admin.admin-users.edit', compact('adminUser', 'roles'));
    }

    public function update(Request $request, AdminUser $adminUser)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('admin_users')->ignore($adminUser->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:' . implode(',', array_keys(AdminUser::ROLES)),
            'nip' => ['nullable', 'string', 'max:50', Rule::unique('admin_users')->ignore($adminUser->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:L,P',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $data = $request->except(['password_confirmation', 'photo']);
        
        // Only update password if provided
        if (empty($data['password'])) {
            unset($data['password']);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($adminUser->photo) {
                Storage::disk('public')->delete('admin_photos/' . $adminUser->photo);
                // Also delete from hosting paths
                if (HostingStorageHelper::isHostingEnvironment()) {
                    $paths = HostingStorageHelper::getHostingPaths();
                    $hostingFile = $paths['public_storage'] . '/admin_photos/' . $adminUser->photo;
                    if (file_exists($hostingFile)) {
                        @unlink($hostingFile);
                    }
                }
            }
            
            $photo = $request->file('photo');
            $photoPath = HostingStorageHelper::uploadFile($photo, 'admin_photos');
            
            if (!$photoPath) {
                return redirect()->back()->with('error', 'Gagal mengupload foto admin. Silakan coba lagi.');
            }
            
            // Extract filename from path for consistency with existing logic
            $data['photo'] = basename($photoPath);
        }

        $adminUser->update($data);

        return redirect()->route('admin.admin-users.index')
                        ->with('success', 'Admin user berhasil diperbarui!');
    }

    public function destroy(AdminUser $adminUser)
    {
        // Prevent deleting self
        if ($adminUser->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }

        // Delete photo if exists
        if ($adminUser->photo) {
            Storage::disk('public')->delete('admin_photos/' . $adminUser->photo);
            // Also delete from hosting paths
            if (HostingStorageHelper::isHostingEnvironment()) {
                $paths = HostingStorageHelper::getHostingPaths();
                $hostingFile = $paths['public_storage'] . '/admin_photos/' . $adminUser->photo;
                if (file_exists($hostingFile)) {
                    @unlink($hostingFile);
                }
            }
        }

        $adminUser->delete();

        return redirect()->route('admin.admin-users.index')
                        ->with('success', 'Admin user berhasil dihapus!');
    }

    public function toggleStatus(AdminUser $adminUser)
    {
        // Prevent deactivating self
        if ($adminUser->id === auth()->id()) {
            return response()->json(['error' => 'Anda tidak dapat menonaktifkan akun sendiri!'], 400);
        }

        $adminUser->update([
            'status' => $adminUser->status === 'aktif' ? 'nonaktif' : 'aktif'
        ]);

        return response()->json([
            'success' => true,
            'status' => $adminUser->status,
            'message' => 'Status berhasil diubah!'
        ]);
    }

    public function resetPassword(AdminUser $adminUser)
    {
        $newPassword = 'password123'; // Default password
        
        $adminUser->update([
            'password' => $newPassword
        ]);

        return redirect()->back()->with('success', 'Password berhasil direset ke: ' . $newPassword);
    }
}
