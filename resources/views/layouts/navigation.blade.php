<div x-show="open" class="relative z-40 md:hidden" x-description="Off-canvas menu for mobile, show/hide based on off-canvas menu state." x-ref="dialog" aria-modal="true" style="display: none;">
    <div x-show="open" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-description="Off-canvas menu backdrop, show/hide based on off-canvas menu state." class="fixed inset-0 bg-gray-600 bg-opacity-75" style="display: none;"></div>

    <div class="fixed inset-0 flex z-40">
        <div x-show="open" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" x-description="Off-canvas menu, show/hide based on off-canvas menu state." class="relative flex-1 flex flex-col max-w-xs w-full bg-white" @click.away="open = false" style="display: none;">
            <div x-show="open" x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-description="Close button, show/hide based on off-canvas menu state." class="absolute top-0 right-0 -mr-12 pt-2" style="display: none;">
                <button type="button" @click="open = false" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                    <span class="sr-only">Close sidebar</span>
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                <div class="flex-shrink-0 flex items-center px-4">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-10 w-auto fill-current text-gray-600" />
                    </a>
                </div>
                <nav class="mt-5 px-2 space-y-1">
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} text-center group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="far fa-home-lg-alt text-lg text-gray-500 mr-3 flex-shrink-0 w-6"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('positions') }}" class="{{ request()->routeIs('positions') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} text-center group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="far fa-money-check-alt text-lg text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 w-6"></i>
                        Positions
                    </a>
                    <a href="{{ route('settings') }}" class="{{ request()->routeIs('settings') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} text-center group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="far fa-cog text-lg text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 w-6"></i>
                        Settings
                    </a>
                </nav>
            </div>
            <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" class="flex-shrink-0 group block" onclick="event.preventDefault(); this.closest('form').submit();">
                        <div class="flex items-center">
                            <div>
                                <img class="inline-block h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
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
    <!-- Sidebar component, swap this element with another sidebar if you like -->
    <div class="flex-1 flex flex-col min-h-0 border-r border-gray-200 bg-white">
        <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
            <div class="flex items-center flex-shrink-0 px-4">
                <a href="{{ route('dashboard') }}">
                    <x-application-logo class="block h-10 w-auto fill-current text-gray-600" />
                </a>
            </div>
            <nav class="mt-5 flex-1 px-2 bg-white space-y-1">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} text-center group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <i class="far fa-home-lg-alt text-lg text-gray-500 mr-3 flex-shrink-0 w-6"></i>
                    Dashboard
                </a>
                <a href="{{ route('positions') }}" class="{{ request()->routeIs('positions') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} text-center group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <i class="far fa-money-check-alt text-lg text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 w-6"></i>
                    Positions
                </a>
                <a href="{{ route('settings') }}" class="{{ request()->routeIs('settings') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} text-center group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <i class="far fa-cog text-lg text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 w-6"></i>
                    Settings
                </a>
            </nav>
        </div>
        <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}" class="flex-shrink-0 w-full group block" onclick="event.preventDefault(); this.closest('form').submit();">
                    <div class="flex items-center">
                        <div>
                            <img class="inline-block h-9 w-9 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
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
