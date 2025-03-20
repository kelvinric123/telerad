<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:flex sm:items-center space-x-4 sm:ms-6">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('patients.index')" :active="request()->routeIs('patients.*')">
                        {{ __('Patients') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('studies.index')" :active="request()->routeIs('studies.*')">
                        {{ __('Studies') }}
                    </x-nav-link>

                    @if(Auth::user()->isRadiologist())
                        <x-nav-link :href="route('report-templates.index')" :active="request()->routeIs('report-templates.*')">
                            {{ __('Report Templates') }}
                        </x-nav-link>
                    @endif
                    
                    @if(Auth::user()->isHospitalAdmin())
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ __('Admin') }}</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('hospitals.manage-users')">
                                    {{ __('Manage Users') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('roles.index')">
                                    {{ __('Role Management') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('hospitals.create-user')">
                                    {{ __('Add New User') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    @endif
                </div>
            </div>

            <!-- User Profile Dropdown -->
            <div class="hidden sm:flex sm:items-center">
                <x-dropdown align="right" width="80">
                    <x-slot name="trigger">
                        <button class="flex items-center transition focus:outline-none">
                            <div class="relative">
                                <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                
                                @if(Auth::user()->roles->count() > 0)
                                    <div class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full bg-green-500 border-2 border-white"></div>
                                @endif
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="p-3 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="h-12 w-12 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold mr-3">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                                    <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
                                </div>
                            </div>
                            
                            @if(Auth::user()->roles->count() > 0)
                                <div class="mt-3">
                                    <div class="text-xs text-gray-500 uppercase tracking-wider mb-1">Roles</div>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach(Auth::user()->roles as $role)
                                            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium 
                                                @if($role->name == 'Hospital Admin') bg-indigo-100 text-indigo-800
                                                @elseif($role->name == 'Radiologist') bg-blue-100 text-blue-800
                                                @elseif($role->name == 'Consultant') bg-green-100 text-green-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            @if(Auth::user()->hospital)
                                <div class="mt-2">
                                    <div class="text-xs text-gray-500 uppercase tracking-wider mb-1">Hospital</div>
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <div class="text-sm text-gray-700">{{ Auth::user()->hospital->name }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="py-1">
                            <x-dropdown-link :href="route('profile.edit')" class="flex items-center px-4 py-2 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ __('My Profile') }}
                            </x-dropdown-link>

                            @if(Auth::user()->isHospitalAdmin())
                                <x-dropdown-link :href="route('hospitals.manage-users')" class="flex items-center px-4 py-2 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    {{ __('Manage Users') }}
                                </x-dropdown-link>
                            @endif

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem" tabindex="-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('patients.index')" :active="request()->routeIs('patients.*')">
                {{ __('Patients') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('studies.index')" :active="request()->routeIs('studies.*')">
                {{ __('Studies') }}
            </x-responsive-nav-link>
            
            @if(Auth::user()->isRadiologist())
                <x-responsive-nav-link :href="route('report-templates.index')" :active="request()->routeIs('report-templates.*')">
                    {{ __('Report Templates') }}
                </x-responsive-nav-link>
            @endif
            
            @if(Auth::user()->isHospitalAdmin())
                <x-responsive-nav-link :href="route('hospitals.manage-users')" :active="request()->routeIs('hospitals.manage-users')">
                    {{ __('Manage Users') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.index')">
                    {{ __('Role Management') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('hospitals.create-user')" :active="request()->routeIs('hospitals.create-user')">
                    {{ __('Add New User') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                
                @if(Auth::user()->roles->count() > 0)
                    <div class="flex flex-wrap gap-1 mt-2">
                        @foreach(Auth::user()->roles as $role)
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $role->name }}
                            </span>
                        @endforeach
                    </div>
                @endif
                
                @if(Auth::user()->hospital)
                    <div class="mt-1 text-sm text-gray-500">
                        {{ Auth::user()->hospital->name }}
                    </div>
                @endif
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
