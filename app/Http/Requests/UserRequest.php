<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $userId = $this->route('user');
        $passwordRule = $userId ? 'nullable|string|min:8|confirmed' : 'required|string|min:8|confirmed';

        return [
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'phone' => 'nullable|string|max:20',
            'password' => $passwordRule,
            'role_id' => 'required|exists:roles,id',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female',
            'address' => 'nullable|string|max:500',
            'status' => 'nullable|in:active,inactive',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama harus diisi.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'username.required' => 'Username harus diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'username.max' => 'Username maksimal 255 karakter.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'email.max' => 'Email maksimal 255 karakter.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role_id.required' => 'Role harus dipilih.',
            'role_id.exists' => 'Role yang dipilih tidak valid.',
            'profile_photo.image' => 'File harus berupa gambar.',
            'profile_photo.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
            'profile_photo.max' => 'Ukuran gambar maksimal 2MB.',
            'birth_date.date' => 'Tanggal lahir harus berupa tanggal.',
            'birth_date.before' => 'Tanggal lahir harus sebelum hari ini.',
            'gender.in' => 'Gender harus male atau female.',
            'address.max' => 'Alamat maksimal 500 karakter.',
            'status.in' => 'Status harus active atau inactive.',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama',
            'username' => 'username',
            'email' => 'email',
            'phone' => 'nomor telepon',
            'password' => 'password',
            'role_id' => 'role',
            'profile_photo' => 'foto profil',
            'birth_date' => 'tanggal lahir',
            'gender' => 'gender',
            'address' => 'alamat',
            'status' => 'status',
        ];
    }
}
