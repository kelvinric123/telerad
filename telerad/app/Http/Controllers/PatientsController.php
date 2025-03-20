<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Services\OrthancSyncService;

class PatientsController extends Controller
{
    protected $orthancSyncService;
    
    public function __construct(OrthancSyncService $orthancSyncService)
    {
        $this->orthancSyncService = $orthancSyncService;
    }
    
    /**
     * Display a listing of all patients
     */
    public function index(Request $request)
    {
        $query = Patient::query();
        
        // Apply search filter if provided
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('patient_id', 'like', "%{$search}%")
                  ->orWhere('mrn', 'like', "%{$search}%");
            });
        }
        
        // Apply date of birth filter if provided
        if ($request->has('birth_date')) {
            $query->whereDate('birth_date', $request->input('birth_date'));
        }
        
        // Apply gender filter if provided
        if ($request->has('sex') && $request->input('sex') !== '') {
            $query->where('sex', $request->input('sex'));
        }
        
        // Get patients with their studies count
        $patients = $query->withCount('studies')
                         ->orderBy('name')
                         ->paginate(15);
        
        return view('patients.index', [
            'patients' => $patients,
            'filters' => $request->only(['search', 'birth_date', 'sex'])
        ]);
    }
    
    /**
     * Display specific patient with their studies
     */
    public function show($id)
    {
        $patient = Patient::with(['studies' => function($query) {
            $query->orderBy('study_date', 'desc');
        }])->findOrFail($id);
        
        return view('patients.show', ['patient' => $patient]);
    }
    
    /**
     * Show the form for editing patient information
     */
    public function edit($id)
    {
        $patient = Patient::findOrFail($id);
        return view('patients.edit', ['patient' => $patient]);
    }
    
    /**
     * Update patient information
     */
    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'mrn' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'sex' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);
        
        $patient->update($validated);
        
        return redirect()->route('patients.show', $patient->id)
                         ->with('success', 'Patient information updated successfully');
    }
    
    /**
     * Sync patients from Orthanc
     */
    public function sync()
    {
        $result = $this->orthancSyncService->syncAll();
        
        if ($result) {
            return redirect()->route('patients.index')
                             ->with('success', 'Patients synchronized successfully from Orthanc');
        } else {
            return redirect()->route('patients.index')
                             ->with('error', 'Failed to synchronize patients from Orthanc');
        }
    }
}
