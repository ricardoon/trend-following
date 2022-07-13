@section('head.title', __('Position details'))

<div>
    <div class="max-w-3xl px-4 mx-auto sm:px-6 md:flex md:items-center md:justify-between md:space-x-5 lg:max-w-7xl lg:px-8">
        <div class="flex items-center space-x-5">
            <div class="flex-shrink-0">
                <div class="relative">
                    <img class="w-16 h-16 rounded-full" src="{{ asset('storage/'.$position->asset->image) }}" alt="{{ $position->asset->name }}">
                    <span class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></span>
                </div>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $position->asset->name }}</h1>
                <p class="text-sm font-medium text-gray-500">{{ __('Position for') }} {{ $position->asset->code }}</p>
            </div>
        </div>
        <div class="mt-6 space-y-4 space-y-reverse sm:flex-row-reverse sm:justify-end sm:space-x-reverse sm:space-y-0 sm:space-x-3 md:mt-0 md:flex-row md:space-x-3">
            <x-admin.links.white href="{{ route('positions') }}">
                <i class="mr-2 fas fa-angle-left"></i>
                {{ __('Go back') }}
            </x-admin.links.white>
            @if ($position->ended_at == null)
            <x-admin.buttons.danger onclick="alert('fechei?')">{{ __('Close position') }}</x-admin>
            @endif
        </div>
    </div>

    <div class="grid max-w-3xl grid-cols-1 gap-6 px-4 mx-auto mt-8 sm:px-6 lg:max-w-7xl lg:grid-flow-col-dense lg:grid-cols-3">
        <div class="space-y-6 lg:col-start-1 lg:col-span-2">
            <section aria-labelledby="applicant-information-title">
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h2 id="applicant-information-title" class="text-lg font-medium leading-6 text-gray-900">{{ __('Information') }}</h2>
                        <p class="max-w-2xl mt-1 text-sm text-gray-500">{{ __('Details about your position.') }}</p>
                    </div>
                    <div class="px-4 py-5 border-t border-gray-200 sm:px-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">{{ __('Asset code') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $position->asset->code }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">{{ __('Granularity') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $position->granularity }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">{{ __('Leverage') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $position->leverage }}x</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">{{ __('Max Stop') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $position->max_stop }}%</dd>
                            </div>
                        </dl>
                    </div>
                    <div>
                        <x-admin.links.transparent href="https://www.binance.com/en/futures/{{ $position->asset->code }}" target="_blank">
                            {{ __('See your position at Binance') }}
                            <i class="ml-1 fas fa-external-link"></i>
                        </x-admin>
                    </div>
                </div>
            </section>
        </div>
        <section aria-labelledby="timeline-title" class="lg:col-start-3 lg:col-span-1">
            <div class="px-4 py-5 bg-white shadow sm:rounded-lg sm:px-6">
                <h2 id="timeline-title" class="text-lg font-medium text-gray-900">{{ __('Current position in Binance') }}</small></h2>
                <div class="flow-root mt-6">
                    @if ($binance_position != null && $binance_position['positionAmt'] != 0)
                    <ul role="list" class="-mb-4">
                        <li>
                            <div class="relative pb-8">
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="flex items-center justify-center w-8 h-8 bg-gray-400 rounded-full ring-8 ring-white">
                                            <!-- Heroicon name: solid/user -->
                                            <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Applied to <a href="#" class="font-medium text-gray-900">Front End Developer</a></p>
                                        </div>
                                        <div class="text-sm text-right text-gray-500 whitespace-nowrap">
                                            <time datetime="2020-09-20">Sep 20</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="relative pb-8">
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="flex items-center justify-center w-8 h-8 bg-blue-500 rounded-full ring-8 ring-white">
                                            <!-- Heroicon name: solid/thumb-up -->
                                            <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Advanced to phone screening by <a href="#" class="font-medium text-gray-900">Bethany Blake</a></p>
                                        </div>
                                        <div class="text-sm text-right text-gray-500 whitespace-nowrap">
                                            <time datetime="2020-09-22">Sep 22</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="relative pb-8">
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="flex items-center justify-center w-8 h-8 bg-green-500 rounded-full ring-8 ring-white">
                                            <!-- Heroicon name: solid/check -->
                                            <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Completed phone screening with <a href="#" class="font-medium text-gray-900">Martha Gardner</a></p>
                                        </div>
                                        <div class="text-sm text-right text-gray-500 whitespace-nowrap">
                                            <time datetime="2020-09-28">Sep 28</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    @else
                    <p class="text-gray-500">{{ __('No position yet.') }}</p>
                    @endif
                </div>
            </div>
        </section>
    </div>
</div>

