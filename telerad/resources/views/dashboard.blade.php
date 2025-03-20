<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-medium text-gray-900 mb-2">
                        {{ __('Welcome, ') }} {{ Auth::user()->name }}!
                    </h2>
                    
                    @if(Auth::user()->roles->count() > 0)
                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach(Auth::user()->roles as $role)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                    
                    @if(Auth::user()->hospital)
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">{{ __('Hospital:') }}</span> {{ Auth::user()->hospital->name }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Quick Actions Card - Common for all users -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            {{ __('Quick Actions') }}
                        </h3>
                        <div class="flex flex-col space-y-3">
                            <a href="{{ route('studies.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                {{ __('View Studies') }}
                            </a>
                            <a href="{{ route('patients.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ __('Patients List') }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Role-Specific Actions -->
                @if(Auth::user()->isHospitalAdmin())
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                {{ __('Hospital Admin Tools') }}
                            </h3>
                            <div class="flex flex-col space-y-3">
                                <a href="{{ route('hospitals.manage-users') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    {{ __('Manage Users') }}
                                </a>
                                <a href="{{ route('roles.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    {{ __('Role Management') }}
                                </a>
                                <a href="{{ route('hospitals.create-user') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                    {{ __('Add New User') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                @if(Auth::user()->isRadiologist())
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                {{ __('Radiologist Tools') }}
                            </h3>
                            <div class="flex flex-col space-y-3">
                                <a href="{{ route('report-templates.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    {{ __('Report Templates') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                @if(Auth::user()->isConsultant())
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                {{ __('Consultant Tools') }}
                            </h3>
                            <div class="flex flex-col space-y-3">
                                <!-- Add consultant-specific tools here -->
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Recent Activity Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            {{ __('Recent Activity') }}
                        </h3>
                        <p class="text-sm text-gray-600">
                            {{ __('No recent activity to display.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
