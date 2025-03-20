<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Profile Sidebar -->
                <div class="md:col-span-1">
                    <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-blue-500 p-4 pb-20 relative">
                            <div class="absolute -bottom-16 left-1/2 transform -translate-x-1/2">
                                <div class="w-32 h-32 rounded-full bg-white border-4 border-white shadow-lg flex items-center justify-center overflow-hidden">
                                    <span class="text-4xl font-bold text-indigo-500">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="pt-20 pb-6 px-4 text-center">
                            <h3 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $user->email }}</p>
                            
                            @if($user->roles->count() > 0)
                                <div class="flex flex-wrap justify-center gap-2 mt-3">
                                    @foreach($user->roles as $role)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                            
                            @if($user->hospital)
                                <div class="mt-3 text-sm text-gray-600">
                                    <span class="font-medium">{{ __('Hospital:') }}</span> 
                                    @if($user->isHospitalAdmin())
                                        <a href="{{ route('hospitals.edit-profile') }}" class="text-indigo-600 hover:text-indigo-800 hover:underline">
                                            {{ $user->hospital->name }}
                                        </a>
                                        <span class="ml-1 text-xs text-gray-500">(click to edit)</span>
                                    @else
                                        {{ $user->hospital->name }}
                                    @endif
                                </div>
                            @endif
                        </div>
                        
                        <div class="border-t border-gray-200">
                            <ul class="divide-y divide-gray-200">
                                <li>
                                    <a href="#personal-info" class="block px-4 py-3 hover:bg-gray-50 transition">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span class="text-sm font-medium text-gray-700">{{ __('Personal Information') }}</span>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#password" class="block px-4 py-3 hover:bg-gray-50 transition">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                            <span class="text-sm font-medium text-gray-700">{{ __('Password') }}</span>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#roles" class="block px-4 py-3 hover:bg-gray-50 transition">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            <span class="text-sm font-medium text-gray-700">{{ __('Roles & Permissions') }}</span>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#danger" class="block px-4 py-3 hover:bg-gray-50 transition">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span class="text-sm font-medium text-red-600">{{ __('Danger Zone') }}</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Profile Content -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Personal Information Section -->
                    <div id="personal-info" class="bg-white shadow sm:rounded-lg overflow-hidden">
                        <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <h3 class="text-lg font-medium leading-6 text-gray-900">{{ __('Personal Information') }}</h3>
                            </div>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ __('Update your account\'s profile information.') }}</p>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                    
                    <!-- Password Section -->
                    <div id="password" class="bg-white shadow sm:rounded-lg overflow-hidden">
                        <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <h3 class="text-lg font-medium leading-6 text-gray-900">{{ __('Password') }}</h3>
                            </div>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ __('Ensure your account is using a secure password.') }}</p>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                    
                    <!-- Roles Section -->
                    <div id="roles" class="bg-white shadow sm:rounded-lg overflow-hidden">
                        <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <h3 class="text-lg font-medium leading-6 text-gray-900">{{ __('Roles & Permissions') }}</h3>
                            </div>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ __('Your assigned roles and system permissions.') }}</p>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.user-roles-information')
                        </div>
                    </div>
                    
                    <!-- Danger Zone Section -->
                    <div id="danger" class="bg-white shadow sm:rounded-lg overflow-hidden">
                        <div class="px-4 py-5 sm:px-6 bg-red-50 border-b border-red-200">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <h3 class="text-lg font-medium leading-6 text-red-800">{{ __('Danger Zone') }}</h3>
                            </div>
                            <p class="mt-1 max-w-2xl text-sm text-red-600">{{ __('Permanently delete your account.') }}</p>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
