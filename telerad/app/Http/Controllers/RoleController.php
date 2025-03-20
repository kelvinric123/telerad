<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Show the list of users with their roles.
     */
    public function index()
    {
        // Only hospital admins can access this page
        if (!Auth::user()->isHospitalAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access');
        }

        $hospitalId = Auth::user()->hospital_id;
        $users = User::where('hospital_id', $hospitalId)->with('roles')->get();
        $roles = Role::all();

        return view('roles.index', compact('users', 'roles'));
    }

    /**
     * Assign a role to a user.
     */
    public function assignRole(Request $request, User $user)
    {
        // Only hospital admins can assign roles
        if (!Auth::user()->isHospitalAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access');
        }

        // Hospital admins can only assign roles to users in their hospital
        if (Auth::user()->hospital_id !== $user->hospital_id) {
            return redirect()->route('roles.index')->with('error', 'You can only assign roles to users in your hospital');
        }

        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->roles()->sync([$request->role_id], false);

        return redirect()->route('roles.index')->with('success', 'Role assigned successfully');
    }

    /**
     * Remove a role from a user.
     */
    public function removeRole(Request $request, User $user)
    {
        // Only hospital admins can remove roles
        if (!Auth::user()->isHospitalAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access');
        }

        // Hospital admins can only remove roles from users in their hospital
        if (Auth::user()->hospital_id !== $user->hospital_id) {
            return redirect()->route('roles.index')->with('error', 'You can only manage roles for users in your hospital');
        }

        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->roles()->detach($request->role_id);

        return redirect()->route('roles.index')->with('success', 'Role removed successfully');
    }
} 