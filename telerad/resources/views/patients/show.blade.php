<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Patient Details') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('patients.edit', $patient->id) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Edit Patient
                </a>
                <a href="{{ route('patients.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition">
                    Back to Patients
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Session Status Messages -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            
            <!-- Patient Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6 bg-gray-50">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Patient Information</h3>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
                            <div class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500">Name</dt>
                                <dd class="text-sm text-gray-900">{{ $patient->formatted_name }}</dd>
                            </div>
                            <div class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500">Patient ID</dt>
                                <dd class="text-sm text-gray-900">{{ $patient->patient_id ?? 'N/A' }}</dd>
                            </div>
                            <div class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500">MRN</dt>
                                <dd class="text-sm text-gray-900">{{ $patient->mrn ?? 'N/A' }}</dd>
                            </div>
                            <div class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500">Birth Date</dt>
                                <dd class="text-sm text-gray-900">{{ $patient->birth_date ? $patient->birth_date->format('Y-m-d') : 'N/A' }}</dd>
                            </div>
                            <div class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500">Gender</dt>
                                <dd class="text-sm text-gray-900">{{ $patient->sex ?? 'N/A' }}</dd>
                            </div>
                            <div class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                <dd class="text-sm text-gray-900">{{ $patient->phone ?? 'N/A' }}</dd>
                            </div>
                            <div class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="text-sm text-gray-900">{{ $patient->email ?? 'N/A' }}</dd>
                            </div>
                            <div class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500">Address</dt>
                                <dd class="text-sm text-gray-900">{{ $patient->address ?? 'N/A' }}</dd>
                            </div>
                        </div>
                    </dl>
                </div>
            </div>
            
            <!-- Patient Studies -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Patient Studies</h3>
                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $patient->studies->count() }} studies</span>
                </div>
                <div class="border-t border-gray-200 p-4">
                    @if($patient->studies->isEmpty())
                        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4">
                            <p>No studies found for this patient.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-3 px-4 text-left">Date</th>
                                        <th class="py-3 px-4 text-left">Accession #</th>
                                        <th class="py-3 px-4 text-left">Description</th>
                                        <th class="py-3 px-4 text-left">Modality</th>
                                        <th class="py-3 px-4 text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($patient->studies as $study)
                                        <tr class="hover:bg-gray-50">
                                            <td class="py-3 px-4">{{ $study->study_date ? $study->study_date->format('Y-m-d') : 'N/A' }}</td>
                                            <td class="py-3 px-4">{{ $study->accession_number ?? 'N/A' }}</td>
                                            <td class="py-3 px-4">{{ $study->study_description ?? 'N/A' }}</td>
                                            <td class="py-3 px-4">{{ $study->modalities_string }}</td>
                                            <td class="py-3 px-4 space-x-2">
                                                <a href="{{ route('studies.show', $study->orthancId) }}" class="inline-flex items-center px-3 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition">
                                                    Details
                                                </a>
                                                @if($study->orthancId)
                                                    <a href="{{ route('ohif.launch', $study->orthancId) }}" class="inline-flex items-center px-3 py-1 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition" target="_blank">
                                                        VIEW
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 