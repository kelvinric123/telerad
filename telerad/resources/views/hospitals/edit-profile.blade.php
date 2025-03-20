<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Hospital Information') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('hospitals.update-profile') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Information Section -->
                            <div class="space-y-4 md:col-span-2">
                                <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>
                                
                                <!-- Hospital Name -->
                                <div>
                                    <x-input-label for="name" :value="__('Hospital Name')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $hospital->name)" required />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <!-- Hospital Description -->
                                <div>
                                    <x-input-label for="description" :value="__('Description')" />
                                    <textarea id="description" name="description" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description', $hospital->description) }}</textarea>
                                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                </div>

                                <!-- Hospital Logo -->
                                <div class="space-y-2">
                                    <x-input-label for="logo" :value="__('Hospital Logo')" />
                                    
                                    @if($hospital->logo_path)
                                        <div class="mt-2 mb-4">
                                            <p class="text-sm text-gray-500 mb-2">Current Logo:</p>
                                            <img src="{{ Storage::url($hospital->logo_path) }}" alt="{{ $hospital->name }} Logo" class="h-20 w-auto object-contain border border-gray-200 rounded p-2">
                                        </div>
                                    @endif
                                    
                                    <input type="file" id="logo" name="logo" accept="image/*" class="block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-indigo-50 file:text-indigo-700
                                        hover:file:bg-indigo-100">
                                    <p class="mt-1 text-sm text-gray-500">Upload a new logo (optional). Recommended size: 200x200px.</p>
                                    <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                                </div>
                            </div>

                            <!-- Contact Information Section -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900">Contact Information</h3>
                                
                                <!-- Email -->
                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $hospital->email)" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>
                                
                                <!-- Phone -->
                                <div>
                                    <x-input-label for="phone" :value="__('Phone')" />
                                    <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $hospital->phone)" />
                                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                </div>
                                
                                <!-- Fax -->
                                <div>
                                    <x-input-label for="fax" :value="__('Fax')" />
                                    <x-text-input id="fax" class="block mt-1 w-full" type="text" name="fax" :value="old('fax', $hospital->fax)" />
                                    <x-input-error :messages="$errors->get('fax')" class="mt-2" />
                                </div>
                                
                                <!-- Website -->
                                <div>
                                    <x-input-label for="website" :value="__('Website')" />
                                    <x-text-input id="website" class="block mt-1 w-full" type="url" name="website" :value="old('website', $hospital->website)" />
                                    <x-input-error :messages="$errors->get('website')" class="mt-2" />
                                </div>
                            </div>

                            <!-- Address Section -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900">Address</h3>
                                
                                <!-- Street Address -->
                                <div>
                                    <x-input-label for="address" :value="__('Street Address')" />
                                    <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address', $hospital->address)" />
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>
                                
                                <!-- City -->
                                <div>
                                    <x-input-label for="city" :value="__('City')" />
                                    <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city', $hospital->city)" />
                                    <x-input-error :messages="$errors->get('city')" class="mt-2" />
                                </div>
                                
                                <!-- State/Province -->
                                <div>
                                    <x-input-label for="state" :value="__('State/Province')" />
                                    <x-text-input id="state" class="block mt-1 w-full" type="text" name="state" :value="old('state', $hospital->state)" />
                                    <x-input-error :messages="$errors->get('state')" class="mt-2" />
                                </div>
                                
                                <!-- Postal Code -->
                                <div>
                                    <x-input-label for="postal_code" :value="__('Postal/ZIP Code')" />
                                    <x-text-input id="postal_code" class="block mt-1 w-full" type="text" name="postal_code" :value="old('postal_code', $hospital->postal_code)" />
                                    <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
                                </div>
                                
                                <!-- Country -->
                                <div>
                                    <x-input-label for="country" :value="__('Country')" />
                                    <x-text-input id="country" class="block mt-1 w-full" type="text" name="country" :value="old('country', $hospital->country)" />
                                    <x-input-error :messages="$errors->get('country')" class="mt-2" />
                                </div>
                            </div>

                            <!-- Registration Information Section -->
                            <div class="space-y-4 md:col-span-2">
                                <h3 class="text-lg font-medium text-gray-900">Registration Information</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Tax ID -->
                                    <div>
                                        <x-input-label for="tax_id" :value="__('Tax ID/VAT Number')" />
                                        <x-text-input id="tax_id" class="block mt-1 w-full" type="text" name="tax_id" :value="old('tax_id', $hospital->tax_id)" />
                                        <x-input-error :messages="$errors->get('tax_id')" class="mt-2" />
                                    </div>
                                    
                                    <!-- Registration Number -->
                                    <div>
                                        <x-input-label for="registration_number" :value="__('Registration Number')" />
                                        <x-text-input id="registration_number" class="block mt-1 w-full" type="text" name="registration_number" :value="old('registration_number', $hospital->registration_number)" />
                                        <x-input-error :messages="$errors->get('registration_number')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Save Changes') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 