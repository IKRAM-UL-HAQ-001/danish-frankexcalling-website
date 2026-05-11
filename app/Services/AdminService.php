<?php

namespace App\Services;

use App\Models\User;

class AdminService
{
    /**
     * Create a new admin user.
     *
     * @param array $data
     * @return User
     */
    public function createAdmin(array $data): User
    {
        // Assuming you have User model and 'is_admin' is a column in your users table
        $data['is_admin'] = true; // Set the user as admin
        return User::create($data);
    }

    /**
     * Update admin user information.
     *
     * @param int $adminId
     * @param array $data
     * @return User
     */
    public function updateAdmin(int $adminId, array $data): User
    {
        $admin = User::findOrFail($adminId);
        $admin->update($data);
        return $admin;
    }

    /**
     * Delete an admin user.
     *
     * @param int $adminId
     * @return bool
     */
    public function deleteAdmin(int $adminId): bool
    {
        $admin = User::findOrFail($adminId);
        return $admin->delete();
    }

    /**
     * List all admin users.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function listAdmins()
    {
        return User::where('is_admin', true)->get();
    }

    /**
     * Change the status of an admin user (e.g., active or inactive).
     *
     * @param int $adminId
     * @param bool $status
     * @return User
     */
    public function changeAdminStatus(int $adminId, bool $status): User
    {
        $admin = User::findOrFail($adminId);
        $admin->is_active = $status; // Assuming you have an 'is_active' column
        $admin->save();
        return $admin;
    }
}
