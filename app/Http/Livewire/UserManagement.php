<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Role;
use Livewire\Component;

use Illuminate\Support\Facades\Log;

class UserManagement extends Component
{

    public $search = '';
    public $statusFilter = '';
    public $roleFilter = '';





    public function render()
    {
        try {
            $query = User::with('role')
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('username', 'like', '%' . $this->search . '%')
                          ->orWhere('email', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->statusFilter, function ($query) {
                    $query->where('status', $this->statusFilter);
                })
                ->when($this->roleFilter, function ($query) {
                    $query->where('role_id', $this->roleFilter);
                });

            $users = $query->orderBy('created_at', 'desc')->get();
            $roles = Role::all();

            return view('livewire.user-management', compact('users', 'roles'));
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error in UserManagement render: ' . $e->getMessage());

            // Return view dengan data kosong jika terjadi error
            return view('livewire.user-management', [
                'users' => collect(),
                'roles' => collect()
            ]);
        }
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->roleFilter = '';
    }
}

