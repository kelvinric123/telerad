@extends('layouts.orthanc')

@section('orthanc-content')
    <h1 class="text-2xl font-semibold mb-6">DICOM Studies</h1>
    
    <!-- Enhanced Filters -->
    <div class="mb-6 bg-white p-4 rounded-lg shadow-sm">
        <form action="{{ route('studies.index') }}" method="GET" class="space-y-4">
            <!-- Date Filter Row -->
            <div class="flex flex-wrap gap-4 items-end">
                <div>
                    <label for="startDate" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" id="startDate" name="startDate" 
                        value="{{ isset($startDate) ? date('Y-m-d', strtotime($startDate)) : '' }}"
                        class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="endDate" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" id="endDate" name="endDate" 
                        value="{{ isset($endDate) ? date('Y-m-d', strtotime($endDate)) : '' }}"
                        class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quick Date</label>
                    <div class="flex space-x-2">
                        <button type="button" onclick="setDateRange('today')" class="px-3 py-1 bg-gray-200 rounded-md text-xs hover:bg-gray-300">Today</button>
                        <button type="button" onclick="setDateRange('yesterday')" class="px-3 py-1 bg-gray-200 rounded-md text-xs hover:bg-gray-300">Yesterday</button>
                        <button type="button" onclick="setDateRange('this-week')" class="px-3 py-1 bg-gray-200 rounded-md text-xs hover:bg-gray-300">This Week</button>
                        <button type="button" onclick="setDateRange('last-week')" class="px-3 py-1 bg-gray-200 rounded-md text-xs hover:bg-gray-300">Last Week</button>
                        <button type="button" onclick="setDateRange('this-month')" class="px-3 py-1 bg-gray-200 rounded-md text-xs hover:bg-gray-300">This Month</button>
                    </div>
                </div>
            </div>
            
            <!-- Patient and Modality Filter Row -->
            <div class="flex flex-wrap gap-4 items-end">
                <div>
                    <label for="patientName" class="block text-sm font-medium text-gray-700 mb-1">Patient Name</label>
                    <input type="text" id="patientName" name="patientName" 
                        value="{{ $patientName ?? '' }}"
                        placeholder="Search patient name"
                        class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="modality" class="block text-sm font-medium text-gray-700 mb-1">Modality</label>
                    <select id="modality" name="modality" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">All Modalities</option>
                        <option value="CT" {{ isset($modality) && $modality === 'CT' ? 'selected' : '' }} title="Computed Tomography">CT</option>
                        <option value="MR" {{ isset($modality) && $modality === 'MR' ? 'selected' : '' }} title="Magnetic Resonance">MR</option>
                        <option value="XA" {{ isset($modality) && $modality === 'XA' ? 'selected' : '' }} title="X-Ray Angiography">XA</option>
                        <option value="CR" {{ isset($modality) && $modality === 'CR' ? 'selected' : '' }} title="Computed Radiography">CR</option>
                        <option value="US" {{ isset($modality) && $modality === 'US' ? 'selected' : '' }} title="Ultrasound">US</option>
                        <option value="DX" {{ isset($modality) && $modality === 'DX' ? 'selected' : '' }} title="Digital Radiography">DX</option>
                        <option value="MG" {{ isset($modality) && $modality === 'MG' ? 'selected' : '' }} title="Mammography">MG</option>
                        <option value="PT" {{ isset($modality) && $modality === 'PT' ? 'selected' : '' }} title="Positron Emission Tomography">PT</option>
                        <option value="NM" {{ isset($modality) && $modality === 'NM' ? 'selected' : '' }} title="Nuclear Medicine">NM</option>
                        <option value="RF" {{ isset($modality) && $modality === 'RF' ? 'selected' : '' }} title="Radio Fluoroscopy">RF</option>
                        <option value="OT" {{ isset($modality) && $modality === 'OT' ? 'selected' : '' }} title="Other">OT</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition">
                        Apply Filters
                    </button>
                    @if(isset($startDate) || isset($endDate) || isset($patientName) || isset($modality))
                        <a href="{{ route('studies.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring ring-gray-300 disabled:opacity-25 transition ml-2">
                            Clear All
                        </a>
                    @endif
                </div>
            </div>
        </form>
        
        <!-- Active Filters Display -->
        @if(isset($startDate) || isset($endDate) || isset($patientName) || isset($modality))
            <div class="mt-4 text-sm text-indigo-600 flex flex-wrap gap-2 items-center">
                <span class="font-semibold">Active Filters:</span>
                @if(isset($startDate) || isset($endDate))
                    <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full text-xs">
                        Date: 
                        @if(isset($startDate))
                            From {{ date('Y-m-d', strtotime($startDate)) }}
                        @endif
                        @if(isset($endDate))
                            To {{ date('Y-m-d', strtotime($endDate)) }}
                        @endif
                    </span>
                @endif
                
                @if(isset($patientName))
                    <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full text-xs">
                        Patient: {{ $patientName }}
                    </span>
                @endif
                
                @if(isset($modality))
                    <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full text-xs">
                        Modality: {{ $modality }}
                    </span>
                @endif
            </div>
        @endif
    </div>
    
    @if(empty($studies))
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
            No studies found in Orthanc.
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-3 px-4 text-left">Patient Name</th>
                        <th class="py-3 px-4 text-left">Patient ID</th>
                        <th class="py-3 px-4 text-left">Study Date</th>
                        <th class="py-3 px-4 text-left">Modality</th>
                        <th class="py-3 px-4 text-left">Description</th>
                        <th class="py-3 px-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($studies as $study)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $study['PatientMainDicomTags']['PatientName'] ?? 'N/A' }}</td>
                            <td class="py-3 px-4">{{ $study['PatientMainDicomTags']['PatientID'] ?? 'N/A' }}</td>
                            <td class="py-3 px-4">
                                @if(isset($study['MainDicomTags']['StudyDate']))
                                    @php
                                        try {
                                            echo \Carbon\Carbon::createFromFormat('Ymd', $study['MainDicomTags']['StudyDate'])->format('Y-m-d');
                                        } catch (\Exception $e) {
                                            echo $study['MainDicomTags']['StudyDate'] ?? 'N/A';
                                        }
                                    @endphp
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @php
                                    // Get modality string and array
                                    $modalitiesArray = [];
                                    if(isset($study['MainDicomTags']['ModalitiesInStudy'])) {
                                        $modalitiesArray = explode('\\', $study['MainDicomTags']['ModalitiesInStudy']);
                                    } elseif(isset($study['ModalitiesInStudy'])) {
                                        $modalitiesArray = $study['ModalitiesInStudy'];
                                    } else {
                                        $modalitiesArray = $study['Modalities'] ?? [];
                                    }
                                    
                                    // Get primary modality (first in list)
                                    $primaryModality = !empty($modalitiesArray) ? $modalitiesArray[0] : '';
                                    
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
                                    
                                    $modalityFullName = $modalityMap[$primaryModality] ?? $primaryModality;
                                    
                                    // Extract body part from study description if available
                                    $bodyPart = '';
                                    if(isset($study['MainDicomTags']['StudyDescription'])) {
                                        $description = $study['MainDicomTags']['StudyDescription'];
                                        // Common body parts to look for in description
                                        $bodyParts = ['Abdomen', 'Chest', 'Head', 'Neck', 'Spine', 'Pelvis', 'Knee', 'Hip', 'Shoulder', 'Wrist', 'Ankle', 'Brain', 'Liver', 'Heart', 'Skull', 'Arm', 'Leg'];
                                        foreach($bodyParts as $part) {
                                            if(stripos($description, $part) !== false) {
                                                $bodyPart = $part;
                                                break;
                                            }
                                        }
                                    }
                                @endphp
                                <div>
                                    @if($primaryModality)
                                        <span class="font-medium text-sm" title="{{ $modalityFullName }}">{{ $primaryModality }}</span>
                                        <span class="text-xs text-gray-500 block">{{ $modalityFullName }}</span>
                                        @if($bodyPart)
                                            <span class="text-xs text-gray-700">{{ $bodyPart }}</span>
                                        @endif
                                    @elseif($bodyPart)
                                        <span class="text-sm text-gray-700">{{ $bodyPart }}</span>
                                    @else
                                        <span class="text-sm text-gray-500">Unknown</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-4">{{ $study['MainDicomTags']['StudyDescription'] ?? 'N/A' }}</td>
                            <td class="py-3 px-4 space-x-2">
                                <a href="{{ route('studies.show', $study['ID']) }}" class="inline-flex items-center px-3 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition">
                                    Details
                                </a>
                                <a href="{{ route('ohif.launch', $study['ID']) }}" class="inline-flex items-center px-3 py-1 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition" target="_blank">
                                    VIEW
                                </a>
                                @php
                                    // Try to find the study in our database to link reporting
                                    $dbStudy = \App\Models\Study::where('orthancId', $study['ID'])->first();
                                @endphp
                                @if($dbStudy)
                                    @php
                                        // Check if a report already exists for this study
                                        $existingReport = $dbStudy->reports()->first();
                                    @endphp
                                    @if($existingReport)
                                        <a href="{{ route('reports.show', $existingReport->id) }}" class="inline-flex items-center px-3 py-1 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition">
                                            VIEW REPORT
                                        </a>
                                    @else
                                        <a href="{{ route('reports.create', $dbStudy->id) }}" class="inline-flex items-center px-3 py-1 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition">
                                            CREATE REPORT
                                        </a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- JavaScript for Date Range Presets -->
    <script>
        function setDateRange(range) {
            const today = new Date();
            let startDate = null;
            let endDate = new Date();
            
            switch(range) {
                case 'today':
                    startDate = new Date();
                    break;
                    
                case 'yesterday':
                    startDate = new Date();
                    startDate.setDate(startDate.getDate() - 1);
                    endDate = new Date(startDate);
                    break;
                    
                case 'this-week':
                    // Setting to the beginning of the current week (Sunday)
                    startDate = new Date();
                    startDate.setDate(startDate.getDate() - startDate.getDay());
                    break;
                    
                case 'last-week':
                    // Setting to the beginning of the last week
                    startDate = new Date();
                    startDate.setDate(startDate.getDate() - startDate.getDay() - 7);
                    endDate = new Date(startDate);
                    endDate.setDate(endDate.getDate() + 6);
                    break;
                    
                case 'this-month':
                    startDate = new Date();
                    startDate.setDate(1);
                    break;
            }
            
            // Format as YYYY-MM-DD
            document.getElementById('startDate').value = startDate ? formatDate(startDate) : '';
            document.getElementById('endDate').value = endDate ? formatDate(endDate) : '';
        }
        
        function formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }
    </script>
@endsection 