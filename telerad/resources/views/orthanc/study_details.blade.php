@extends('layouts.orthanc')

@section('orthanc-content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Study Details</h1>
        <div class="space-x-2">
            @php
                // Try to find the study in our database to link reporting
                $dbStudy = \App\Models\Study::where('orthancId', $study['ID'])->first();
                
                // Get the primary modality if available
                $modalitiesArray = [];
                if(isset($study['MainDicomTags']['ModalitiesInStudy'])) {
                    $modalitiesArray = explode('\\', $study['MainDicomTags']['ModalitiesInStudy']);
                } elseif(!empty($modalitiesInStudy)) {
                    $modalitiesArray = $modalitiesInStudy;
                } else {
                    $modalitiesArray = $study['Modalities'] ?? [];
                }
                $primaryModality = !empty($modalitiesArray) ? $modalitiesArray[0] : '';
            @endphp
        </div>
    </div>
    
    <!-- Display success or info messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    
    @if(session('info'))
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('info') }}</span>
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
    
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 bg-gray-50">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Patient Information</h3>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4">
                    <div class="space-y-2">
                        <dt class="text-sm font-medium text-gray-500">Patient Name</dt>
                        <dd class="text-sm text-gray-900">{{ $study['PatientMainDicomTags']['PatientName'] ?? 'N/A' }}</dd>
                    </div>
                    <div class="space-y-2">
                        <dt class="text-sm font-medium text-gray-500">Patient ID</dt>
                        <dd class="text-sm text-gray-900">{{ $study['PatientMainDicomTags']['PatientID'] ?? 'N/A' }}</dd>
                    </div>
                    <div class="space-y-2">
                        <dt class="text-sm font-medium text-gray-500">Birth Date</dt>
                        <dd class="text-sm text-gray-900">{{ $study['PatientMainDicomTags']['PatientBirthDate'] ?? 'N/A' }}</dd>
                    </div>
                    <div class="space-y-2">
                        <dt class="text-sm font-medium text-gray-500">Gender</dt>
                        <dd class="text-sm text-gray-900">{{ $study['PatientMainDicomTags']['PatientSex'] ?? 'N/A' }}</dd>
                    </div>
                    <div class="space-y-2">
                        <dt class="text-sm font-medium text-gray-500">Patient Age</dt>
                        <dd class="text-sm text-gray-900">{{ $study['MainDicomTags']['PatientAge'] ?? 'N/A' }}</dd>
                    </div>
                </div>
            </dl>
        </div>
    </div>
    
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 bg-gray-50">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Study Information</h3>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4">
                    <div class="space-y-2">
                        <dt class="text-sm font-medium text-gray-500">Study Date</dt>
                        <dd class="text-sm text-gray-900">{{ $study['MainDicomTags']['StudyDate'] ?? 'N/A' }}</dd>
                    </div>
                    <div class="space-y-2">
                        <dt class="text-sm font-medium text-gray-500">Accession Number</dt>
                        <dd class="text-sm text-gray-900">{{ $study['MainDicomTags']['AccessionNumber'] ?? 'N/A' }}</dd>
                    </div>
                    <div class="space-y-2">
                        <dt class="text-sm font-medium text-gray-500">Study ID</dt>
                        <dd class="text-sm text-gray-900">{{ $study['MainDicomTags']['StudyID'] ?? 'N/A' }}</dd>
                    </div>
                    <div class="space-y-2">
                        <dt class="text-sm font-medium text-gray-500">Study Description</dt>
                        <dd class="text-sm text-gray-900">{{ $study['MainDicomTags']['StudyDescription'] ?? 'N/A' }}</dd>
                    </div>
                    <div class="space-y-2">
                        <dt class="text-sm font-medium text-gray-500">Referring Physician</dt>
                        <dd class="text-sm text-gray-900">{{ $study['MainDicomTags']['ReferringPhysicianName'] ?? 'N/A' }}</dd>
                    </div>
                    <div class="space-y-2">
                        <dt class="text-sm font-medium text-gray-500">Modalities</dt>
                        <dd class="text-sm text-gray-900">
                            @php
                                $modalitiesArray = [];
                                if(isset($study['MainDicomTags']['ModalitiesInStudy'])) {
                                    $modalitiesArray = explode('\\', $study['MainDicomTags']['ModalitiesInStudy']);
                                } elseif(!empty($modalitiesInStudy)) {
                                    $modalitiesArray = $modalitiesInStudy;
                                } else {
                                    $modalitiesArray = $study['Modalities'] ?? [];
                                }
                                
                                // Map modality codes to full names
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
                            @endphp
                            <div class="flex flex-wrap gap-2 mt-1">
                                @foreach($modalitiesArray as $mod)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-blue-600 text-white" title="{{ $modalityMap[$mod] ?? $mod }}">
                                        {{ $mod }}
                                    </span>
                                @endforeach
                            </div>
                            <div class="flex flex-wrap gap-2 mt-2">
                                @foreach($modalitiesArray as $mod)
                                    <span class="text-xs text-gray-600">{{ $mod }}: {{ $modalityMap[$mod] ?? $mod }}</span>
                                @endforeach
                            </div>
                        </dd>
                        @php
                            // Try to find the study in our database to fetch modality descriptions
                            $dbStudy = \App\Models\Study::where('orthancId', $study['ID'])->first();
                        @endphp
                        @if($dbStudy)
                            <dd class="text-xs text-gray-600 mt-1">{{ $dbStudy->modality_description }}</dd>
                        @endif
                    </div>
                </div>
            </dl>
        </div>
    </div>
    
    <!-- Report Status Section (if a report exists) -->
    @if($dbStudy && $dbStudy->reports()->exists())
        @php
            $report = $dbStudy->reports()->latest()->first();
        @endphp
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6 bg-gray-50 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Report Status</h3>
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
            <div class="border-t border-gray-200 p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">
                            This study has a {{ $report->status }} report created on {{ $report->created_at->format('Y-m-d H:i') }}.
                        </p>
                    </div>
                    <div class="space-x-2">
                        <a href="{{ route('reports.show', $report->id) }}" class="inline-flex items-center px-3 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition">
                            View Report
                        </a>
                        <a href="{{ route('reports.edit', $report->id) }}" class="inline-flex items-center px-3 py-1 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:border-yellow-900 focus:ring ring-yellow-300 disabled:opacity-25 transition">
                            Edit Report
                        </a>
                        <form action="{{ route('reports.destroy', $report->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition" onclick="return confirm('Are you sure you want to delete this report?')">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- No report exists, show create button -->
        @if($dbStudy)
            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6 bg-gray-50">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Report</h3>
                </div>
                <div class="border-t border-gray-200 p-4">
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-500">No report has been created for this study yet.</p>
                        <a href="{{ route('reports.create', $dbStudy->id) }}" class="inline-flex items-center px-3 py-1 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition">
                            Create Report
                        </a>
                    </div>
                </div>
            </div>
        @endif
    @endif
    
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 bg-gray-50">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Series Information</h3>
        </div>
        <div class="border-t border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Series Number</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Modality</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Images</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($study['Series'] ?? [] as $seriesId)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $seriesDetails[$seriesId]['MainDicomTags']['SeriesNumber'] ?? $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $seriesDetails[$seriesId]['MainDicomTags']['SeriesDescription'] ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-blue-600 text-white">
                                        {{ $seriesDetails[$seriesId]['MainDicomTags']['Modality'] ?? 'Unknown' }}
                                    </span>
                                    @php
                                        $seriesModality = $seriesDetails[$seriesId]['MainDicomTags']['Modality'] ?? '';
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
                                        $modalityFullName = $modalityMap[$seriesModality] ?? $seriesModality;
                                    @endphp
                                    @if($seriesModality && isset($modalityMap[$seriesModality]))
                                        <div class="text-xs text-gray-500 mt-1">{{ $modalityFullName }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ count($seriesDetails[$seriesId]['Instances'] ?? []) }} images
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection 