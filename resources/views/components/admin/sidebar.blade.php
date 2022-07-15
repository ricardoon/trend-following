<div x-show="openSidebar" class="relative z-40 md:hidden" x-description="Off-canvas menu for mobile, show/hide based on off-canvas menu state." x-ref="dialog" aria-modal="true" style="display: none;">
    <div x-show="openSidebar" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-description="Off-canvas menu backdrop, show/hide based on off-canvas menu state." class="fixed inset-0 bg-gray-600 bg-opacity-75" style="display: none;"></div>

    <div class="fixed inset-0 z-40 flex">
        <div x-show="openSidebar" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" x-description="Off-canvas menu, show/hide based on off-canvas menu state." class="relative flex flex-col flex-1 w-full max-w-xs bg-white" @click.away="openSidebar = false" style="display: none;">
            <div x-show="openSidebar" x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-description="Close button, show/hide based on off-canvas menu state." class="absolute top-0 right-0 pt-2 -mr-12" style="display: none;">
                <button type="button" @click="openSidebar = false" class="flex items-center justify-center w-10 h-10 ml-1 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                    <span class="sr-only">Close sidebar</span>
                    <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                <div class="flex items-center flex-shrink-0 px-4">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block w-auto h-10 text-gray-600 fill-current" />
                    </a>
                </div>
                <nav class="px-2 mt-5 space-y-1">
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} text-center group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="flex-shrink-0 w-6 mr-3 text-lg text-gray-500 far fa-home-lg-alt"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('positions') }}" class="{{ request()->routeIs('positions') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} text-center group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="flex-shrink-0 w-6 mr-3 text-lg text-gray-400 far fa-money-check-alt group-hover:text-gray-500"></i>
                        Positions
                    </a>
                    <a href="{{ route('settings') }}" class="{{ request()->routeIs('settings') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} text-center group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="flex-shrink-0 w-6 mr-3 text-lg text-gray-400 far fa-cog group-hover:text-gray-500"></i>
                        Settings
                    </a>
                </nav>
            </div>
            <div class="flex flex-shrink-0 p-4 border-t border-gray-200">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" class="flex-shrink-0 block group" onclick="event.preventDefault(); this.closest('form').submit();">
                        <div class="flex items-center">
                            <div>
                                <img class="inline-block w-10 h-10 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                            </div>
                            <div class="ml-3">
                                <p class="text-base font-medium text-gray-700 group-hover:text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-sm font-medium text-gray-500 group-hover:text-gray-700">{{ __('Log Out') }}</p>
                            </div>
                        </div>
                    </a>
                </form>
            </div>
        </div>

        <div class="flex-shrink-0 w-14">
        <!-- Force sidebar to shrink to fit close icon -->
        </div>
    </div>
</div>

<!-- Static sidebar for desktop -->
<div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0">
    <div class="flex flex-col flex-1 min-h-0 bg-white border-r border-gray-200">
        <div class="flex flex-col flex-1 pt-5 pb-4 overflow-y-auto">
            <div class="flex items-center flex-shrink-0 px-4">
                <a href="{{ route('dashboard') }}">
                    <x-application-logo class="block w-auto h-10 text-gray-600 fill-current" />
                </a>
            </div>
            <nav class="flex-1 px-2 mt-5 space-y-1 bg-white">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} text-center group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <i class="flex-shrink-0 w-6 mr-3 text-lg text-gray-500 far fa-home-lg-alt"></i>
                    Dashboard
                </a>
                <a href="{{ route('positions') }}" class="{{ request()->routeIs('positions') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} text-center group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <i class="flex-shrink-0 w-6 mr-3 text-lg text-gray-400 far fa-money-check-alt group-hover:text-gray-500"></i>
                    Positions
                </a>
                <a href="{{ route('settings') }}" class="{{ request()->routeIs('settings') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} text-center group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <i class="flex-shrink-0 w-6 mr-3 text-lg text-gray-400 far fa-cog group-hover:text-gray-500"></i>
                    Settings
                </a>
            </nav>
        </div>
        <div class="flex flex-shrink-0 p-4 border-t border-gray-200">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}" class="flex-shrink-0 block w-full group" onclick="event.preventDefault(); this.closest('form').submit();">
                    <div class="flex items-center">
                        <div>
                            <svg class="inline-block text-gray-300 rounded-full h-9 w-9" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-700 group-hover:text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs font-medium text-gray-500 group-hover:text-gray-700">{{ __('Log Out') }}</p>
                        </div>
                    </div>
                </a>
            </form>
        </div>
    </div>
</div>
