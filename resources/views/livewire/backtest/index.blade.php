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
            <form class="mt-6 sm:flex sm:flex-row sm:items-start sm:min-w-0 sm:flex-1" wire:submit.prevent="run_backtest">
                <div class="basis-1/5">
                    <label for="yahoo_code" class="sr-only">{{ __('Yahoo Finance code') }}</label>
                    <x-admin.form.input type="text" class="" divClass="" name="yahoo_code" id="yahoo_code" wire:model.defer="yahoo_code" placeholder="{{ __('Yahoo Finance code') }}" />
                </div>
                <div class="mt-2 basis-1/5 sm:mt-0 sm:ml-1">
                    <label for="strategy" class="sr-only">{{ __('Strategy') }}</label>
                    <x-admin.form.select id="strategy" name="strategy" wire:model.defer="strategy" class="mt-0">
                        <option value="0">{{ __('Strategy') }}</option>
                        @foreach (config('utils.strategies') as $key => $strategy)
                            <option value="{{ $key }}">{{ $strategy }}</option>
                        @endforeach
                    </x-admin.form.select>
                </div>
                <div class="mt-2 basis-1/5 sm:mt-0 sm:ml-1">
                    <label for="granularity" class="sr-only">{{ __('Granularity') }}</label>
                    <x-admin.form.select id="granularity" name="granularity" wire:model.defer="granularity" class="mt-0">
                        <option value="0">{{ __('Granularity') }}</option>
                        @foreach (config('utils.granularities') as $key => $granularity)
                            <option value="{{ $granularity }}">{{ $granularity }}</option>
                        @endforeach
                    </x-admin.form.select>
                </div>
                <div class="mt-2 basis-1/5 sm:mt-0 sm:ml-1">
                    <label for="start_date" class="sr-only">{{ __('Start date') }}</label>
                    <x-admin.form.input type="text" class="" divClass="" name="start_date" id="start_date" wire:model.defer="start_date" placeholder="{{ __('Start date') }}"  x-data="{}" x-ref="start_date" x-init="new Cleave($refs.start_date, { date: true, delimiter: '/', datePattern: ['d', 'm', 'Y'] })" />
                </div>
                <div class="mt-2 basis-1/5 sm:mt-0 sm:ml-1">
                    <label for="end_date" class="sr-only">{{ __('End date') }}</label>
                    <x-admin.form.input type="text" class="" divClass="" name="end_date" id="end_date" wire:model.defer="end_date" placeholder="{{ __('End date') }}"  x-data="{}" x-ref="end_date" x-init="new Cleave($refs.end_date, { date: true, delimiter: '/', datePattern: ['d', 'm', 'Y'] })" />
                </div>
                <div class="mt-2 basis-1/7 sm:mt-0 sm:ml-1 sm:flex-shrink-0">
                    <x-admin.buttons.primary submit wire:target="run_backtest" class="w-full uppercase">
                        <i class="mr-2 fas fa-play"></i>
                        {{ __('Run') }}
                    </x-admin.buttons.primary >
                </div>
            </form>
        </div>
    </div>
    @if ($backtest_result)
    <div class="flex flex-col mt-8">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr class="divide-x divide-gray-200">
                            @foreach ($backtest_result[0] as $key => $value)
                                @if (strtolower($key) != 'asset')
                                <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">{{ $value['text'] }}</th>
                                @endif
                            @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($backtest_result as $results)
                        <tr class="divide-x divide-gray-200">
                            @foreach ($results as $key => $result)
                                @if (strtolower($key) != 'asset')
                                <td class="p-4 text-sm text-center text-gray-500 whitespace-nowrap">
                                    @if ($result['type'] == 'money')
                                        {{ money($result['value']) }}
                                    @elseif ($result['type'] == 'percentage')
                                        {{ round($result['value'], 2) }}%
                                    @elseif ($result['type'] == 'float')
                                        {{ round($result['value'], 2) }}
                                    @elseif ($result['type'] == 'date')
                                        {{ date('d/m/Y', strtotime($result['value'])) }}
                                    @else
                                        {{ $result['value'] }}
                                    @endif
                                </td>
                                @endif
                            @endforeach
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
