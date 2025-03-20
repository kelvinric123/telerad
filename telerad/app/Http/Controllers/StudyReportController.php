<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Study;
use App\Models\StudyReport;
use Illuminate\Support\Facades\Auth;

class StudyReportController extends Controller
{
    /**
     * Show the form for creating a new report.
     */
    public function create(Request $request, $studyId)
    {
        $study = Study::with('patient')->findOrFail($studyId);
        
        // Check if a report already exists
        $existingReport = $study->reports()->first();
        
        if ($existingReport) {
            return redirect()->route('reports.edit', $existingReport->id)
                ->with('info', 'A report already exists for this study.');
        }
        
        // Get referrer URL to redirect back
        $referrer = $request->headers->get('referer');
        
        return view('reports.create', [
            'study' => $study,
            'referrer' => $referrer
        ]);
    }
    
    /**
     * Store a newly created report in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'study_id' => 'required|exists:studies,id',
            'findings' => 'nullable|string',
            'impression' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'status' => 'required|in:draft,final,amended',
        ]);
        
        $report = new StudyReport($validated);
        $report->user_id = Auth::id();
        
        if ($request->status === 'final' || $request->status === 'amended') {
            $report->finalized_at = now();
        }
        
        $report->save();
        
        // Use referrer if provided, otherwise default to study page
        $redirectUrl = $request->input('referrer') 
            ? $request->input('referrer') 
            : route('studies.show', $report->study->orthancId);
            
        return redirect($redirectUrl)
            ->with('success', 'Report created successfully.');
    }
    
    /**
     * Show the report details.
     */
    public function show(Request $request, $id)
    {
        $report = StudyReport::with(['study', 'study.patient', 'radiologist'])->findOrFail($id);
        
        // Get referrer URL to redirect back
        $referrer = $request->headers->get('referer');
        
        return view('reports.show', [
            'report' => $report,
            'referrer' => $referrer
        ]);
    }
    
    /**
     * Show the form for editing the report.
     */
    public function edit(Request $request, $id)
    {
        $report = StudyReport::with(['study', 'study.patient'])->findOrFail($id);
        
        // Get referrer URL to redirect back
        $referrer = $request->headers->get('referer');
        
        return view('reports.edit', [
            'report' => $report,
            'referrer' => $referrer
        ]);
    }
    
    /**
     * Update the specified report in storage.
     */
    public function update(Request $request, $id)
    {
        $report = StudyReport::findOrFail($id);
        
        $validated = $request->validate([
            'findings' => 'nullable|string',
            'impression' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'status' => 'required|in:draft,final,amended',
        ]);
        
        // Prevent changing back to draft if report was already finalized
        if (($report->status === 'final' || $report->status === 'amended') && $request->status === 'draft') {
            return redirect()->back()
                ->with('error', 'A finalized report cannot be changed back to draft status.')
                ->withInput();
        }
        
        // If the report is final and being updated, automatically set it to amended
        if ($report->status === 'final' && $report->finalized_at) {
            $validated['status'] = 'amended';
        }
        
        // If changing from draft to final/amended, set the finalized timestamp
        if ($report->status === 'draft' && 
            ($validated['status'] === 'final' || $validated['status'] === 'amended')) {
            $validated['finalized_at'] = now();
        }
        
        $report->update($validated);
        
        // Use referrer if provided, otherwise default to study page
        $redirectUrl = $request->input('referrer') 
            ? $request->input('referrer') 
            : route('studies.show', $report->study->orthancId);
        
        // Set appropriate success message
        $successMessage = 'Report updated successfully.';
        if ($validated['status'] === 'amended') {
            $successMessage = 'Report amended successfully.';
        }
            
        return redirect($redirectUrl)
            ->with('success', $successMessage);
    }
    
    /**
     * Remove the specified report from storage.
     */
    public function destroy(Request $request, $id)
    {
        $report = StudyReport::findOrFail($id);
        $studyOrthancId = $report->study->orthancId;
        
        $report->delete();
        
        // Use referrer if provided, otherwise default to study page
        $redirectUrl = $request->headers->get('referer') 
            ? $request->headers->get('referer') 
            : route('studies.show', $studyOrthancId);
            
        return redirect($redirectUrl)
            ->with('success', 'Report deleted successfully.');
    }
}
