@section('head.title', __('Positions'))

<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">{{ __('Positions') }}</h1>
            <p class="mt-2 text-sm text-gray-700">{{ __('A list with your positions.') }}</p>
        </div>
        @if (count($positions) > 0)
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('positions.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                <i class="mr-2 fas fa-plus"></i>
                {{ __('Add position') }}
            </a>
        </div>
        @endif
    </div>
    @if (count($positions) > 0)
    <div class="flex flex-col mt-8">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">{{ __('Asset') }}</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ __('Amount') }}</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ __('Status') }}</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ __('Strategy') }}</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ __('Started at') }}</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">{{ __('Actions') }}</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($positions as $position)
                            <tr>
                                <td class="py-4 pl-4 pr-3 text-sm whitespace-nowrap sm:pl-6">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-10 h-10">
                                            <img class="w-10 h-10 rounded-full" src="{{ asset('storage/'.$position->asset->image) }}" alt="{{ $position->asset->name }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="font-medium text-gray-900">{{ $position->asset->name }}</div>
                                            <div class="text-gray-500">{{ $position->asset->code }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap">
                                    <div class="text-gray-900">{{ money($position->amount) }}</div>
                                    <div class="text-xs text-gray-500">{{ money($position->initial_amount) }}</div>
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap">
                                    <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">{{ __('Active') }}</span>
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap">{{ ucfirst($position->strategy) }}</td>
                                <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $position->started_at ? date('d/m/Y', strtotime($position->started_at)) : __('Waiting')  }}</td>
                                <td class="relative py-4 pl-3 pr-4 text-sm font-medium text-right whitespace-nowrap sm:pr-6">
                                    <a href="{{ route('positions.display', ['id' => $position->id]) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('View') }}<span class="sr-only">, {{ $position->asset->name }}</span></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @else
        <div class="mt-6 overflow-hidden bg-white rounded-lg shadow">
            <div class="py-8 text-center">
                <i class="text-3xl text-gray-400 fas fa-folder-open"></i>
                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('No position found.') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('Create new positions for a strategy.') }}</p>
                <div class="mt-6">
                    <a href="{{ route('positions.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="mr-2 fas fa-plus"></i>
                        {{ __('Add position') }}
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
