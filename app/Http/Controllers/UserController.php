<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Role;
use App\Traits\HasProfilePhoto;
use App\Helpers\ImageResizeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends BaseController
{
    use HasProfilePhoto;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::with('role')->get();
            return view('users.index', compact('users'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat data user.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $roles = Role::all();
            return view('users.create', compact('roles'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat form.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        try {
            $data = $request->validated();

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                $data['profile_photo_path'] = ImageResizeHelper::resizeProfilePhoto($request->file('profile_photo'));
            }

            // Hash password
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $user = User::create($data);

            return redirect()->route('users.index')
                ->with('success', "User '{$user->name}' berhasil dibuat.");
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat membuat user.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {
            $user->load(['role', 'sales']);
            return view('users.show', compact('user'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat data user.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        try {
            $roles = Role::all();
            return view('users.edit', compact('user', 'roles'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat form edit.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        try {
            $data = $request->validated();

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                $data['profile_photo_path'] = ImageResizeHelper::resizeProfilePhoto($request->file('profile_photo'), $user->profile_photo_path, $user->id);
            }

            // Hash password if provided
            if (isset($data['password']) && $data['password']) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $user->update($data);

            return redirect()->route('users.index')
                ->with('success', "Data user '{$user->name}' berhasil diperbarui.");
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui user.');
        }
    }

        /**
     * Toggle user status
     */
    public function toggleStatus(User $user)
    {
        try {
            // Prevent deactivating own account
            if ($user->id === auth()->id()) {
                return back()->with('warning', 'Anda tidak dapat menonaktifkan akun sendiri.');
            }

            $oldStatus = $user->status;
            $newStatus = $user->status === 'active' ? 'inactive' : 'active';
            $user->update(['status' => $newStatus]);

            $statusText = $newStatus === 'active' ? 'diaktifkan' : 'dinonaktifkan';
            $statusChange = $oldStatus === 'active' ? 'dinonaktifkan' : 'diaktifkan';

            // Redirect back to the previous page with success message
            return back()->with('success', "Status user '{$user->name}' berhasil {$statusChange}.");
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengubah status user.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            // Prevent deleting own account
            if ($user->id === auth()->id()) {
                return back()->with('warning', 'Anda tidak dapat menghapus akun sendiri.');
            }

            // Delete profile photo if exists
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $userName = $user->name;
            $user->delete();

            return redirect()->route('users.index')
                ->with('success', "User '{$userName}' berhasil dihapus.");
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus user.');
        }
    }

    /**
     * API method to get users
     */
    public function apiIndex()
    {
        try {
            $users = User::with('role')->get();
            return $this->successResponse($users, 'Users retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve users');
        }
    }

    /**
     * API method to get specific user
     */
    public function apiShow(User $user)
    {
        try {
            $user->load('role');
            return $this->successResponse($user, 'User retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve user');
        }
    }

    /**
     * API method to store user
     */
    public function apiStore(UserRequest $request)
    {
        try {
            $data = $request->validated();

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                $path = $request->file('profile_photo')->store('profile-photos', 'public');
                $data['profile_photo_path'] = $path;
            }

            // Hash password
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $user = User::create($data);
            $user->load('role');

            return $this->successResponse($user, 'User created successfully', 201);
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to create user');
        }
    }

    /**
     * API method to update user
     */
    public function apiUpdate(UserRequest $request, User $user)
    {
        try {
            $data = $request->validated();

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                $data['profile_photo_path'] = ImageResizeHelper::resizeProfilePhoto($request->file('profile_photo'), $user->profile_photo_path, $user->id);
            }

            // Hash password if provided
            if (isset($data['password']) && $data['password']) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $user->update($data);
            $user->load('role');

            return $this->successResponse($user, 'User updated successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to update user');
        }
    }

    /**
     * API method to delete user
     */
    public function apiDestroy(User $user)
    {
        try {
            // Delete profile photo if exists
            if ($user->profile_photo_path) {
                ImageResizeHelper::deleteImage($user->profile_photo_path);
            }

            $user->delete();

            return $this->successResponse(null, 'User deleted successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to delete user');
        }
    }

    /**
     * Show user profile page
     */
    public function profile()
    {
        try {
            $user = auth()->user()->load('role');
            return view('profile.index', compact('user'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat profil.');
        }
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = auth()->user();

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $data = $request->only(['name', 'email', 'phone', 'address']);

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                $data['profile_photo_path'] = ImageResizeHelper::resizeProfilePhoto($request->file('profile_photo'), $user->profile_photo_path, $user->id);
            }

            $user->update($data);

            return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui profil.');
        }
    }

    /**
     * Show change password form
     */
    public function changePasswordForm()
    {
        return view('profile.change-password');
    }

    /**
     * Update user password
     */
    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required|current_password',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = auth()->user();
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return redirect()->route('profile')->with('success', 'Password berhasil diubah.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan saat mengubah password.');
        }
    }

    /**
     * Show user activities
     */
    public function activities()
    {
        try {
            $user = auth()->user();
            $activities = collect(); // You can implement activity logging here

            return view('profile.activities', compact('user', 'activities'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat aktivitas.');
        }
    }

    /**
     * Show user settings
     */
    public function settings()
    {
        try {
            $user = auth()->user();
            return view('profile.settings', compact('user'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat pengaturan.');
        }
    }

    /**
     * Update user settings
     */
    public function updateSettings(Request $request)
    {
        try {
            $user = auth()->user();

            $request->validate([
                'notification_email' => 'boolean',
                'notification_sms' => 'boolean',
                'language' => 'in:en,id',
                'timezone' => 'string|max:50'
            ]);

            $user->update([
                'settings' => array_merge($user->settings ?? [], $request->only([
                    'notification_email', 'notification_sms', 'language', 'timezone'
                ]))
            ]);

            return redirect()->route('settings')->with('success', 'Pengaturan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui pengaturan.');
        }
    }
}
