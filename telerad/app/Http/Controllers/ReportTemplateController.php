<?php

namespace App\Http\Controllers;

use App\Models\ReportTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Apply modality filter if provided
        $query = ReportTemplate::orderBy('name');
        
        if ($request->filled('modality')) {
            $query->where(function($q) use ($request) {
                $q->where('modality', $request->modality)
                  ->orWhereNull('modality');
            });
        }
        
        $templates = $query->get();
        
        // Group templates by modality
        $groupedTemplates = $templates->groupBy('modality');
        
        return view('settings.report-templates.index', [
            'templates' => $templates,
            'groupedTemplates' => $groupedTemplates,
            'activeModality' => $request->modality,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $modalityMap = [
            'CR' => 'Computed Radiography',
            'CT' => 'Computed Tomography',
            'DX' => 'Digital Radiography',
            'MG' => 'Mammography',
            'MR' => 'Magnetic Resonance',
            'NM' => 'Nuclear Medicine',
            'OT' => 'Other',
            'PT' => 'Positron Emission Tomography',
            'RF' => 'Radio Fluoroscopy',
            'US' => 'Ultrasound',
            'XA' => 'X-Ray Angiography'
        ];
        
        return view('settings.report-templates.create', [
            'modalityMap' => $modalityMap,
            'preselectedModality' => $request->modality,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'modality' => 'nullable|string|max:10',
            'section' => 'required|in:findings,impression,recommendations',
            'content' => 'required|string',
            'is_default' => 'boolean',
        ]);
        
        $validated['user_id'] = Auth::id();
        
        // If this is set as default, unset any other defaults for this modality and section
        if ($request->is_default) {
            ReportTemplate::where('modality', $request->modality)
                ->where('section', $request->section)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }
        
        ReportTemplate::create($validated);
        
        return redirect()->route('report-templates.index')
            ->with('success', 'Template created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $template = ReportTemplate::findOrFail($id);
        
        $modalityMap = [
            'CR' => 'Computed Radiography',
            'CT' => 'Computed Tomography',
            'DX' => 'Digital Radiography',
            'MG' => 'Mammography',
            'MR' => 'Magnetic Resonance',
            'NM' => 'Nuclear Medicine',
            'OT' => 'Other',
            'PT' => 'Positron Emission Tomography',
            'RF' => 'Radio Fluoroscopy',
            'US' => 'Ultrasound',
            'XA' => 'X-Ray Angiography'
        ];
        
        return view('settings.report-templates.edit', [
            'template' => $template,
            'modalityMap' => $modalityMap
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $template = ReportTemplate::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'modality' => 'nullable|string|max:10',
            'section' => 'required|in:findings,impression,recommendations',
            'content' => 'required|string',
            'is_default' => 'boolean',
        ]);
        
        // If this is set as default, unset any other defaults for this modality and section
        if ($request->is_default) {
            ReportTemplate::where('modality', $request->modality)
                ->where('section', $request->section)
                ->where('is_default', true)
                ->where('id', '!=', $id)
                ->update(['is_default' => false]);
        }
        
        $template->update($validated);
        
        return redirect()->route('report-templates.index')
            ->with('success', 'Template updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $template = ReportTemplate::findOrFail($id);
        $template->delete();
        
        return redirect()->route('report-templates.index')
            ->with('success', 'Template deleted successfully.');
    }
    
    /**
     * Get templates by modality and section (for AJAX)
     */
    public function getTemplates(Request $request)
    {
        $modality = $request->modality;
        $section = $request->section;
        
        $templates = ReportTemplate::byModality($modality)
            ->bySection($section)
            ->orderBy('name')
            ->get();
            
        return response()->json([
            'templates' => $templates
        ]);
    }
}
