<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class HospitalController extends Controller
{
    /**
     * Display a listing of the hospitals.
     */
    public function index()
    {
        // Only system admins can access this
        $hospitals = Hospital::all();
        return view('hospitals.index', compact('hospitals'));
    }

    /**
     * Show the form for creating a new hospital.
     */
    public function create()
    {
        return view('hospitals.create');
    }

    /**
     * Store a newly created hospital in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'fax' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'tax_id' => 'nullable|string|max:50',
            'registration_number' => 'nullable|string|max:50',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255|unique:users,email',
            'admin_password' => 'required|string|min:8',
        ]);

        // Handle logo upload if exists
        $logoPath = null;
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $logoPath = $request->file('logo')->store('hospital-logos', 'public');
        }

        // Create the hospital
        $hospital = Hospital::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'state' => $validated['state'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
            'country' => $validated['country'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'fax' => $validated['fax'] ?? null,
            'email' => $validated['email'] ?? null,
            'website' => $validated['website'] ?? null,
            'logo_path' => $logoPath,
            'tax_id' => $validated['tax_id'] ?? null,
            'registration_number' => $validated['registration_number'] ?? null,
        ]);

        // Create the hospital admin user
        $user = User::create([
            'name' => $validated['admin_name'],
            'email' => $validated['admin_email'],
            'password' => Hash::make($validated['admin_password']),
            'hospital_id' => $hospital->id,
        ]);

        // Assign the hospital admin role
        $adminRole = Role::where('slug', 'hospital-admin')->first();
        $user->roles()->attach($adminRole);

        return redirect()->route('hospitals.index')->with('success', 'Hospital created successfully with admin user');
    }

    /**
     * Display the specified hospital.
     */
    public function show(Hospital $hospital)
    {
        $users = User::where('hospital_id', $hospital->id)->with('roles')->get();
        return view('hospitals.show', compact('hospital', 'users'));
    }

    /**
     * Show the form for editing the specified hospital.
     */
    public function edit(Hospital $hospital)
    {
        return view('hospitals.edit', compact('hospital'));
    }

    /**
     * Update the specified hospital in storage.
     */
    public function update(Request $request, Hospital $hospital)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'fax' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'tax_id' => 'nullable|string|max:50',
            'registration_number' => 'nullable|string|max:50',
        ]);

        // Handle logo upload if exists
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            // Delete old logo if exists
            if ($hospital->logo_path && Storage::disk('public')->exists($hospital->logo_path)) {
                Storage::disk('public')->delete($hospital->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('hospital-logos', 'public');
        }

        $hospital->update($validated);

        return redirect()->route('hospitals.index')->with('success', 'Hospital updated successfully');
    }

    /**
     * Display the users management page for hospital admins.
     */
    public function manageUsers()
    {
        // Only hospital admins can access this page
        if (!Auth::user()->isHospitalAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access');
        }

        $hospitalId = Auth::user()->hospital_id;
        $hospital = Hospital::findOrFail($hospitalId);
        $users = User::where('hospital_id', $hospitalId)->with('roles')->get();
        $roles = Role::all();

        return view('hospitals.manage-users', compact('hospital', 'users', 'roles'));
    }

    /**
     * Show the form for creating a new user in the hospital.
     */
    public function createUser()
    {
        // Only hospital admins can access this page
        if (!Auth::user()->isHospitalAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access');
        }

        $roles = Role::all();
        return view('hospitals.create-user', compact('roles'));
    }

    /**
     * Store a newly created user in the hospital.
     */
    public function storeUser(Request $request)
    {
        // Only hospital admins can create users
        if (!Auth::user()->isHospitalAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'hospital_id' => Auth::user()->hospital_id,
        ]);

        // Assign the role
        $user->roles()->attach($validated['role_id']);

        return redirect()->route('hospitals.manage-users')->with('success', 'User created successfully');
    }

    /**
     * Show the form for hospital admin to edit their hospital information.
     */
    public function editHospitalProfile()
    {
        // Only hospital admins can access this page
        if (!Auth::user()->isHospitalAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access');
        }

        $hospitalId = Auth::user()->hospital_id;
        if (!$hospitalId) {
            return redirect()->route('dashboard')->with('error', 'No hospital associated with your account');
        }

        $hospital = Hospital::findOrFail($hospitalId);
        return view('hospitals.edit-profile', compact('hospital'));
    }

    /**
     * Update the hospital information for the authenticated hospital admin.
     */
    public function updateHospitalProfile(Request $request)
    {
        // Only hospital admins can update hospital information
        if (!Auth::user()->isHospitalAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access');
        }

        $hospitalId = Auth::user()->hospital_id;
        if (!$hospitalId) {
            return redirect()->route('dashboard')->with('error', 'No hospital associated with your account');
        }

        $hospital = Hospital::findOrFail($hospitalId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'fax' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'tax_id' => 'nullable|string|max:50',
            'registration_number' => 'nullable|string|max:50',
        ]);

        // Handle logo upload if exists
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            // Delete old logo if exists
            if ($hospital->logo_path && Storage::disk('public')->exists($hospital->logo_path)) {
                Storage::disk('public')->delete($hospital->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('hospital-logos', 'public');
        }

        $hospital->update($validated);

        return redirect()->route('profile.edit')->with('success', 'Hospital information updated successfully');
    }
} 