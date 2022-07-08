@section('head.title', __('Create position'))

<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">{{ __('Positions') }}</h1>
            <p class="mt-2 text-sm text-gray-700">{{ __('Create new position for an asset within a strategy.') }}</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('positions') }}" class="inline-flex items-center justify-center py-2 text-sm font-medium sm:w-auto">
                <i class="mr-2 fas fa-angle-left"></i>
                {{ __('Go back') }}
            </a>
        </div>
    </div>
    <div class="mt-10 shadow sm:rounded-md sm:overflow-hidden">
        <form wire:submit.prevent="save" class="space-y-8 divide-y divide-gray-200">
            <div class="overflow-hidden shadow sm:rounded-md">
                <div class="px-4 py-5 bg-white sm:p-6">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="asset" class="block text-sm font-medium text-gray-700">{{ __('Asset') }}</label>
                            <select id="asset" name="asset" autocomplete="asset-name" class="block w-full px-3 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="0">{{ __('Choose') }}</option>
                                @foreach ($assets as $asset)
                                    <option value="{{ $asset->id }}">{{ $asset->name . ' - ' . $asset->code }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-6 sm:col-span-6 lg:col-span-2">
                            <label for="strategy" class="block text-sm font-medium text-gray-700">{{ __('Strategy') }}</label>
                            <select id="strategy" name="strategy" autocomplete="strategy-name" class="block w-full px-3 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="0">{{ __('Choose') }}</option>
                                @foreach (config('utils.strategies') as $key => $strategy)
                                    <option value="{{ $key }}">{{ $strategy }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                            <label for="granularity" class="block text-sm font-medium text-gray-700">{{ __('Granularity') }}</label>
                            <select id="granularity" name="granularity" autocomplete="granularity-name" class="block w-full px-3 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="0">{{ __('Choose') }}</option>
                                @foreach (config('utils.granularities') as $key => $granularity)
                                    <option value="{{ $key }}">{{ $granularity }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                            <label for="amount" class="block text-sm font-medium text-gray-700">{{ __('Amount') }}</label>
                            <input type="text" name="amount" id="amount" autocomplete="amount" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                            <label for="leverage" class="block text-sm font-medium text-gray-700">{{ __('Leverage') }}</label>
                            <input type="text" name="leverage" id="leverage" autocomplete="leverage" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 text-right bg-gray-50 sm:px-6">
                    <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Save</button>
                </div>
                </div>
        </form>
    </div>
</div>
