<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Patients') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('patients.sync') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Sync with Orthanc
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Session Status Messages -->
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif
                    
                    <!-- Filter Form -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <form method="GET" action="{{ route('patients.index') }}" class="flex flex-wrap gap-4 items-end">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                                <input type="text" id="search" name="search" 
                                    value="{{ $filters['search'] ?? '' }}"
                                    placeholder="Name, ID or MRN"
                                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Birth Date</label>
                                <input type="date" id="birth_date" name="birth_date" 
                                    value="{{ $filters['birth_date'] ?? '' }}"
                                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label for="sex" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                                <select id="sex" name="sex" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">All</option>
                                    <option value="M" {{ isset($filters['sex']) && $filters['sex'] === 'M' ? 'selected' : '' }}>Male</option>
                                    <option value="F" {{ isset($filters['sex']) && $filters['sex'] === 'F' ? 'selected' : '' }}>Female</option>
                                    <option value="O" {{ isset($filters['sex']) && $filters['sex'] === 'O' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition">
                                    Filter
                                </button>
                                @if(!empty($filters))
                                    <a href="{{ route('patients.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring ring-gray-300 disabled:opacity-25 transition ml-2">
                                        Clear
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                    
                    <!-- Patients Table -->
                    @if($patients->isEmpty())
                        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4">
                            <p>No patients found. You can sync with Orthanc to import patient data.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-3 px-4 text-left">Name</th>
                                        <th class="py-3 px-4 text-left">ID</th>
                                        <th class="py-3 px-4 text-left">MRN</th>
                                        <th class="py-3 px-4 text-left">Birth Date</th>
                                        <th class="py-3 px-4 text-left">Gender</th>
                                        <th class="py-3 px-4 text-left">Studies</th>
                                        <th class="py-3 px-4 text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($patients as $patient)
                                        <tr class="hover:bg-gray-50">
                                            <td class="py-3 px-4">{{ $patient->formatted_name }}</td>
                                            <td class="py-3 px-4">{{ $patient->patient_id }}</td>
                                            <td class="py-3 px-4">{{ $patient->mrn }}</td>
                                            <td class="py-3 px-4">{{ $patient->birth_date ? $patient->birth_date->format('Y-m-d') : 'N/A' }}</td>
                                            <td class="py-3 px-4">{{ $patient->sex }}</td>
                                            <td class="py-3 px-4">{{ $patient->studies_count }}</td>
                                            <td class="py-3 px-4 space-x-2">
                                                <a href="{{ route('patients.show', $patient->id) }}" class="inline-flex items-center px-3 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition">
                                                    Details
                                                </a>
                                                <a href="{{ route('patients.edit', $patient->id) }}" class="inline-flex items-center px-3 py-1 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition">
                                                    Edit
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $patients->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 