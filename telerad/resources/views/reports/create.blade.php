@extends('layouts.orthanc')

@section('orthanc-content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Create Report</h1>
        <div>
            <a href="{{ $referrer ?? route('studies.show', $study->orthancId) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition">
                Back
            </a>
        </div>
    </div>
    
    <!-- Study Information Summary -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 bg-gray-50 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Study Information</h3>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4">
                    <div class="space-y-2">
                        <dt class="text-sm font-medium text-gray-500">Patient</dt>
                        <dd class="text-sm text-gray-900">{{ $study->patient->formatted_name }}</dd>
                    </div>
                    <div class="space-y-2">
                        <dt class="text-sm font-medium text-gray-500">Study Date</dt>
                        <dd class="text-sm text-gray-900">{{ $study->study_date ? $study->study_date->format('Y-m-d') : 'N/A' }}</dd>
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
                            
                            $modalities = is_array($study->modalities) ? $study->modalities : json_decode($study->modalities ?? '[]', true);
                            $primaryModality = !empty($modalities) ? $modalities[0] : '';
                            $modalityFullName = $modalityMap[$primaryModality] ?? $primaryModality;
                        @endphp
                        <dd class="text-sm text-gray-900 font-semibold">
                            @if($primaryModality)
                                <span class="font-medium text-sm">{{ $primaryModality }}</span>
                                <span class="text-xs text-gray-500 block">{{ $modalityFullName }}</span>
                                @if($study->study_description)
                                    <span class="text-xs text-gray-700">{{ $study->study_description }}</span>
                                @endif
                            @else
                                <span class="text-sm text-gray-500">Unknown</span>
                            @endif
                        </dd>
                    </div>
                    <div class="space-y-2">
                        <dt class="text-sm font-medium text-gray-500">Accession Number</dt>
                        <dd class="text-sm text-gray-900">{{ $study->accession_number ?? 'N/A' }}</dd>
                    </div>
                    <div class="space-y-2">
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="text-sm text-gray-900">{{ $study->study_description ?? 'N/A' }}</dd>
                    </div>
                    <div class="space-y-2">
                        <dt class="text-sm font-medium text-gray-500">Referring Physician</dt>
                        <dd class="text-sm text-gray-900">{{ $study->referring_physician ?? 'N/A' }}</dd>
                    </div>
                </div>
            </dl>
        </div>
    </div>
    
    <!-- Report Form -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 bg-gray-50">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Report Details</h3>
        </div>
        <div class="border-t border-gray-200 p-4">
            <form action="{{ route('reports.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="study_id" value="{{ $study->id }}">
                
                @if($referrer)
                    <input type="hidden" name="referrer" value="{{ $referrer }}">
                @endif
                
                <div class="mb-4">
                    <label for="findings" class="block text-sm font-medium text-gray-700 mb-1">Findings</label>
                    <div class="flex mb-2">
                        @if($study->modalities && is_array($study->modalities) && count($study->modalities) > 0)
                            @php 
                                $primaryModality = $study->modalities[0];
                            @endphp
                            <button type="button" onclick="insertModalityTemplate('findings', '{{ $primaryModality }}')" class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 py-1 px-2 rounded ml-2">
                                {{ $primaryModality }} Template
                            </button>
                            <select id="findingsTemplateSelect" onchange="insertSavedTemplate('findings')" class="text-xs bg-green-100 hover:bg-green-200 text-green-700 py-1 px-2 rounded ml-2 cursor-pointer">
                                <option value="">Select Saved Template</option>
                            </select>
                        @endif
                    </div>
                    <textarea id="findings" name="findings" rows="8" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                </div>
                
                <div class="mb-4">
                    <label for="impression" class="block text-sm font-medium text-gray-700 mb-1">Impression</label>
                    <textarea id="impression" name="impression" rows="4" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                </div>
                
                <div class="mb-4">
                    <label for="recommendations" class="block text-sm font-medium text-gray-700 mb-1">Recommendations</label>
                    <textarea id="recommendations" name="recommendations" rows="3" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                </div>
                
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" name="status" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="draft">Draft</option>
                        <option value="final">Final</option>
                    </select>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <a href="{{ $referrer ?? route('studies.show', $study->orthancId) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring ring-gray-300 disabled:opacity-25 transition">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition">
                        Save Report
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- JavaScript for Report Templates -->
    <script>
        function insertModalityTemplate(fieldId, modality) {
            let templateText = "";
            
            // Templates for specific modalities
            switch(modality) {
                case 'CT':
                    templateText = "CLINICAL INDICATION:\n\nTECHNIQUE: CT scan of the [body part] performed with [contrast/no contrast].\n\nCOMPARISON: [Prior studies if applicable]\n\nFINDINGS:\n- Brain/Head:\n- Chest:\n- Abdomen:\n- Pelvis:\n- Extremities:\n- Vascular structures:\n";
                    break;
                case 'MR':
                    templateText = "CLINICAL INDICATION:\n\nTECHNIQUE: MRI of the [body part] performed with [sequences performed, contrast/no contrast].\n\nCOMPARISON: [Prior studies if applicable]\n\nFINDINGS:\n- Signal characteristics:\n- Anatomic structures:\n- Abnormalities:\n- Enhancement pattern (if applicable):\n";
                    break;
                case 'US':
                    templateText = "CLINICAL INDICATION:\n\nTECHNIQUE: Ultrasound examination of the [body part/organ].\n\nCOMPARISON: [Prior studies if applicable]\n\nFINDINGS:\n- Organ size/echogenicity:\n- Masses/lesions:\n- Vascular assessment (if performed):\n";
                    break;
                case 'XA':
                    templateText = "CLINICAL INDICATION:\n\nTECHNIQUE: X-ray angiography of the [vascular territory] performed with [contrast agent].\n\nCOMPARISON: [Prior studies if applicable]\n\nFINDINGS:\n- Vessel patency:\n- Stenosis/occlusions:\n- Aneurysms/vascular malformations:\n- Interventions performed (if any):\n";
                    break;
                case 'CR':
                case 'DX':
                    templateText = "CLINICAL INDICATION:\n\nTECHNIQUE: [CR/DX] radiography of the [body part].\n\nCOMPARISON: [Prior studies if applicable]\n\nFINDINGS:\n- Bone alignment and density:\n- Joint spaces:\n- Soft tissues:\n- Any abnormalities identified:\n";
                    break;
                case 'MG':
                    templateText = "CLINICAL INDICATION:\n\nTECHNIQUE: Digital mammography [screening/diagnostic], [views obtained].\n\nCOMPARISON: [Prior mammogram date if available]\n\nFINDINGS:\n- Breast composition (density):\n- Masses:\n- Calcifications:\n- Architectural distortion:\n- Asymmetries:\n- Skin/nipple changes:\n- Lymph nodes:\n\nBI-RADS Assessment: [Category]\n";
                    break;
                case 'NM':
                    templateText = "CLINICAL INDICATION:\n\nTECHNIQUE: Nuclear medicine [scan type] performed with [radiopharmaceutical] administration.\n\nCOMPARISON: [Prior studies if applicable]\n\nFINDINGS:\n- Radiotracer distribution:\n- Normal uptake areas:\n- Abnormal uptake areas:\n- Quantitative measures (if applicable):\n";
                    break;
                case 'PT':
                    templateText = "CLINICAL INDICATION:\n\nTECHNIQUE: PET/CT scan performed with [radiopharmaceutical] administration.\n\nCOMPARISON: [Prior studies if applicable]\n\nFINDINGS:\n- Head/Neck:\n- Chest:\n- Abdomen:\n- Pelvis:\n- Extremities:\n- SUV measurements of significant lesions:\n";
                    break;
                default:
                    templateText = "CLINICAL INDICATION:\n\nTECHNIQUE:\n\nCOMPARISON:\n\nFINDINGS:\n";
                    break;
            }
            
            const field = document.getElementById(fieldId);
            field.value = templateText;
            field.focus();
        }
        
        // When the page loads, fetch templates from the server
        document.addEventListener('DOMContentLoaded', function() {
            try {
                @if($study->modalities && is_array($study->modalities) && count($study->modalities) > 0)
                    @php 
                        $primaryModality = $study->modalities[0];
                    @endphp
                    // Check if findings template select exists
                    const findingsSelect = document.getElementById('findingsTemplateSelect');
                    if (findingsSelect) {
                        fetchTemplates('{{ $primaryModality }}', 'findings', 'findingsTemplateSelect');
                    }
                @else
                    // Check if findings template select exists
                    const findingsSelect = document.getElementById('findingsTemplateSelect');
                    if (findingsSelect) {
                        fetchTemplates('', 'findings', 'findingsTemplateSelect');
                    }
                @endif
            } catch (error) {
                console.error('Error initializing templates:', error);
            }
        });
        
        // Fetch templates from the server
        function fetchTemplates(modality, section, selectId) {
            const select = document.getElementById(selectId);
            
            // Check if select element exists
            if (!select) {
                console.error('Select element not found:', selectId);
                return;
            }
            
            fetch('/report-templates/get-templates?modality=' + modality + '&section=' + section)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    // Clear existing options except the first one
                    while (select.options.length > 1) {
                        select.remove(1);
                    }
                    
                    // Add new options
                    if (data.templates && Array.isArray(data.templates)) {
                        data.templates.forEach(template => {
                            const option = document.createElement('option');
                            option.value = template.id;
                            option.textContent = template.name;
                            option.dataset.content = template.content;
                            select.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching templates:', error);
                    
                    // Add a disabled option to indicate error
                    const errorOption = document.createElement('option');
                    errorOption.disabled = true;
                    errorOption.textContent = 'Error loading templates';
                    select.appendChild(errorOption);
                });
        }
        
        // Insert saved template
        function insertSavedTemplate(fieldId) {
            const selectId = fieldId + 'TemplateSelect';
            const select = document.getElementById(selectId);
            
            // Check if select element exists before proceeding
            if (!select) {
                console.error('Select element not found:', selectId);
                return;
            }
            
            // Check if there is a selected option
            if (select.selectedIndex < 0) {
                return;
            }
            
            const selectedOption = select.options[select.selectedIndex];
            
            if (select.value && selectedOption && selectedOption.dataset.content) {
                const field = document.getElementById(fieldId);
                field.value = selectedOption.dataset.content;
                field.focus();
                
                // Reset select
                select.selectedIndex = 0;
            }
        }
    </script>
@endsection 