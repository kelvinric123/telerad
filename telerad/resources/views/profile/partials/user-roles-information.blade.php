<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Role Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Your assigned roles and permissions.") }}
        </p>
    </header>

    <div class="mt-6">
        <div class="bg-gray-50 p-4 rounded-md">
            <h3 class="font-medium text-gray-700 mb-2">{{ __('Your Roles') }}</h3>
            
            <div>
                @if($user->roles->count() > 0)
                    <div class="space-y-6">
                        @if($user->isHospitalAdmin() || $user->isRadiologist() || $user->isConsultant())
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @if($user->isHospitalAdmin())
                                    <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-100">
                                        <div class="flex items-center mb-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            <h4 class="font-medium text-indigo-900">{{ __('Hospital Admin') }}</h4>
                                        </div>
                                        <ul class="space-y-2 pl-7">
                                            <li class="text-sm text-indigo-800 flex items-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-600 mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ __('Manage radiologists and consultants') }}
                                            </li>
                                            <li class="text-sm text-indigo-800 flex items-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-600 mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ __('Assign and remove roles from users') }}
                                            </li>
                                            <li class="text-sm text-indigo-800 flex items-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-600 mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ __('View all reports and studies') }}
                                            </li>
                                        </ul>
                                    </div>
                                @endif

                                @if($user->isRadiologist())
                                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                        <div class="flex items-center mb-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            <h4 class="font-medium text-blue-900">{{ __('Radiologist') }}</h4>
                                        </div>
                                        <ul class="space-y-2 pl-7">
                                            <li class="text-sm text-blue-800 flex items-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600 mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ __('View assigned studies') }}
                                            </li>
                                            <li class="text-sm text-blue-800 flex items-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600 mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ __('Create and edit reports') }}
                                            </li>
                                            <li class="text-sm text-blue-800 flex items-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600 mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ __('Utilize report templates') }}
                                            </li>
                                        </ul>
                                    </div>
                                @endif

                                @if($user->isConsultant())
                                    <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                                        <div class="flex items-center mb-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <h4 class="font-medium text-green-900">{{ __('Consultant') }}</h4>
                                        </div>
                                        <ul class="space-y-2 pl-7">
                                            <li class="text-sm text-green-800 flex items-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600 mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ __('View patient reports') }}
                                            </li>
                                            <li class="text-sm text-green-800 flex items-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600 mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ __('Add comments and recommendations') }}
                                            </li>
                                            <li class="text-sm text-green-800 flex items-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600 mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ __('Collaborate with radiologists') }}
                                            </li>
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        @endif
                        
                        <div class="mt-6">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                {{ __('Go to Dashboard') }}
                            </a>
                        </div>
                    </div>
                @else
                    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-100">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <h4 class="text-sm font-medium text-yellow-800">{{ __('No roles assigned') }}</h4>
                        </div>
                        <p class="mt-2 text-sm text-yellow-700 pl-8">
                            {{ __('You have not been assigned any roles yet. Please contact your administrator.') }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section> 