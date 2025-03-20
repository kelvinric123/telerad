@extends('layouts.orthanc')

@section('orthanc-content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Report Details</h1>
        <div class="space-x-2">
            <a href="{{ $referrer ?? route('studies.show', $report->study->orthancId) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition">
                Back
            </a>
            <a href="{{ route('reports.edit', $report->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition">
                Edit Report
            </a>
        </div>
    </div>
    
    <!-- Study Information Summary -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 bg-gray-50">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Study Information</h3>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4">
                    <div class="space-y-2">
                        <dt class="text-sm font-medium text-gray-500">Patient</dt>
                        <dd class="text-sm text-gray-900">{{ $report->study->patient->formatted_name }}</dd>
                    </div>
                    <div class="space-y-2">
                        <dt class="text-sm font-medium text-gray-500">Study Date</dt>
                        <dd class="text-sm text-gray-900">{{ $report->study->study_date ? $report->study->study_date->format('Y-m-d') : 'N/A' }}</dd>
                    </div>
                    <div class="space-y-2">
                        <dt class="text-sm font-medium text-gray-500">Modality</dt>
                        @php
                            // Map modality codes to full names for enhanced display
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
                            
                            $modalities = is_array($report->study->modalities) ? $report->study->modalities : json_decode($report->study->modalities ?? '[]', true);
                            $primaryModality = !empty($modalities) ? $modalities[0] : '';
                            $modalityFullName = $modalityMap[$primaryModality] ?? $primaryModality;
                        @endphp
                        <dd class="text-sm text-gray-900 font-semibold">
                            @if($primaryModality)
                                <span class="font-medium text-sm">{{ $primaryModality }}</span>
                                <span class="text-xs text-gray-500 block">{{ $modalityFullName }}</span>
                                @if($report->study->study_description)
                                    <span class="text-xs text-gray-700">{{ $report->study->study_description }}</span>
                                @endif
                            @else
                                <span class="text-sm text-gray-500">Unknown</span>
                            @endif
                        </dd>
                    </div>
                    <div class="space-y-2">
                        <dt class="text-sm font-medium text-gray-500">Accession Number</dt>
                        <dd class="text-sm text-gray-900">{{ $report->study->accession_number ?? 'N/A' }}</dd>
                    </div>
                    <div class="space-y-2">
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="text-sm text-gray-900">{{ $report->study->study_description ?? 'N/A' }}</dd>
                    </div>
                    <div class="space-y-2">
                        <dt class="text-sm font-medium text-gray-500">Referring Physician</dt>
                        <dd class="text-sm text-gray-900">{{ $report->study->referring_physician ?? 'N/A' }}</dd>
                    </div>
                </div>
            </dl>
        </div>
    </div>
    
    <!-- Report Details -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 bg-gray-50 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Report Details</h3>
            <div class="flex items-center space-x-3">
                <span class="bg-{{ $report->status === 'draft' ? 'yellow' : ($report->status === 'final' ? 'green' : 'blue') }}-100 text-{{ $report->status === 'draft' ? 'yellow' : ($report->status === 'final' ? 'green' : 'blue') }}-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                    {{ ucfirst($report->status) }}
                </span>
                @if($report->finalized_at)
                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                        Finalized {{ $report->finalized_at->format('Y-m-d H:i') }}
                    </span>
                @endif
                @if($report->radiologist)
                    <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                        By: {{ $report->radiologist->name }}
                    </span>
                @endif
            </div>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="px-4 py-5 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 border-b">
                    <dt class="text-sm font-medium text-gray-500 mb-2">Findings</dt>
                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $report->findings ?? 'No findings recorded.' }}</dd>
                </div>
                <div class="px-4 py-5 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 border-b">
                    <dt class="text-sm font-medium text-gray-500 mb-2">Impression</dt>
                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $report->impression ?? 'No impression recorded.' }}</dd>
                </div>
                <div class="px-4 py-5 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 mb-2">Recommendations</dt>
                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $report->recommendations ?? 'No recommendations recorded.' }}</dd>
                </div>
            </dl>
        </div>
    </div>
@endsection 