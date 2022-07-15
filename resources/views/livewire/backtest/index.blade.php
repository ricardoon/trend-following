@section('head.title', __('Backtest'))

<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">{{ __('Backtest') }}</h1>
            <p class="mt-2 text-sm text-gray-700">{{ __('Predefined backtests for an asset.') }}</p>
        </div>
    </div>
    <div class="mt-8 shadow sm:rounded-md sm:overflow-hidden">
        <div class="px-4 py-6 bg-white sm:p-6">
            <div class="text-center">
                <i class="mx-auto text-5xl text-gray-400 fad fa-atom-alt"></i>
                <h2 class="mt-2 text-lg font-medium text-gray-900">{{ __('Run your backtest') }}</h2>
                <p class="mt-1 text-sm text-gray-500">{{ __('You can run a backtest for any asset from Yahoo Finance.') }}</p>
            </div>
            <form class="mt-6 sm:flex sm:items-start" wire:submit.prevent="run_backtest">
                <label for="yahoo_code" class="sr-only">{{ __('Yahoo Finance code') }}</label>
                <div class="flex sm:flex-row sm:items-start sm:min-w-0 sm:flex-1">
                    <div class="basis-3/5">
                        <x-admin.form.input type="text" class="rounded-none rounded-l-md" divClass="" name="yahoo_code" id="yahoo_code" wire:model.defer="yahoo_code" placeholder="{{ __('Yahoo Finance code') }}" />
                    </div>
                    <div class="basis-1/5">
                        <label for="strategy" class="sr-only">{{ __('Strategy') }}</label>
                        <x-admin.form.select id="strategy" name="strategy" wire:model.defer="strategy" class="mt-0 text-gray-500 border-l-0 rounded-none">
                            <option value="0">{{ __('Strategy') }}</option>
                            @foreach (config('utils.strategies') as $key => $strategy)
                                <option value="{{ $key }}">{{ $strategy }}</option>
                            @endforeach
                        </x-admin.form.select>
                    </div>
                    <div class="basis-1/5">
                        <label for="granularity" class="sr-only">{{ __('Granularity') }}</label>
                        <x-admin.form.select id="granularity" name="granularity" wire:model.defer="granularity" class="mt-0 text-gray-500 border-l-0 rounded-none rounded-r-md">
                            <option value="0">{{ __('Granularity') }}</option>
                            @foreach (config('utils.granularities') as $key => $granularity)
                                <option value="{{ $granularity }}">{{ $granularity }}</option>
                            @endforeach
                        </x-admin.form.select>
                    </div>
                </div>
                <div class="mt-3 sm:mt-0 sm:ml-4 sm:flex-shrink-0">
                    <x-admin.buttons.primary submit class="w-full uppercase">
                        <i class="mr-2 fas fa-play"></i>
                        {{ __('Run') }}
                    </x-admin.buttons.primary >
                </div>
            </form>
        </div>
    </div>
</div>
