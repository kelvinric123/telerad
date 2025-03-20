<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Patient') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('patients.show', $patient->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition">
                    Back to Patient Details
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('patients.update', $patient->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Patient ID -->
                            <div>
                                <x-input-label for="patient_id" :value="__('Patient ID (DICOM)')" />
                                <x-text-input id="patient_id" class="block mt-1 w-full bg-gray-100" type="text" name="patient_id" :value="$patient->patient_id" disabled />
                                <p class="text-xs text-gray-500 mt-1">DICOM Patient ID cannot be modified</p>
                            </div>
                            
                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $patient->name)" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            
                            <!-- MRN -->
                            <div>
                                <x-input-label for="mrn" :value="__('Medical Record Number (MRN)')" />
                                <x-text-input id="mrn" class="block mt-1 w-full" type="text" name="mrn" :value="old('mrn', $patient->mrn)" />
                                <x-input-error :messages="$errors->get('mrn')" class="mt-2" />
                            </div>
                            
                            <!-- Birth Date -->
                            <div>
                                <x-input-label for="birth_date" :value="__('Birth Date')" />
                                <x-text-input id="birth_date" class="block mt-1 w-full" type="date" name="birth_date" :value="old('birth_date', $patient->birth_date ? $patient->birth_date->format('Y-m-d') : '')" />
                                <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                            </div>
                            
                            <!-- Gender -->
                            <div>
                                <x-input-label for="sex" :value="__('Gender')" />
                                <select id="sex" name="sex" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                    <option value="">Select Gender</option>
                                    <option value="M" {{ old('sex', $patient->sex) === 'M' ? 'selected' : '' }}>Male</option>
                                    <option value="F" {{ old('sex', $patient->sex) === 'F' ? 'selected' : '' }}>Female</option>
                                    <option value="O" {{ old('sex', $patient->sex) === 'O' ? 'selected' : '' }}>Other</option>
                                </select>
                                <x-input-error :messages="$errors->get('sex')" class="mt-2" />
                            </div>
                            
                            <!-- Phone -->
                            <div>
                                <x-input-label for="phone" :value="__('Phone')" />
                                <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $patient->phone)" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>
                            
                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $patient->email)" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            
                            <!-- Address -->
                            <div class="md:col-span-2">
                                <x-input-label for="address" :value="__('Address')" />
                                <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address', $patient->address)" />
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('patients.show', $patient->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition mr-4">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Update Patient') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 