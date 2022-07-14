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
                <p class="text-sm font-medium text-gray-500">
                    @if ($position->started_at)
                        {{ __('Started at') }} {{ date('d/m/Y', strtotime($position->started_at)) }}
                    @else
                        {{ __('Position not started yet') }}
                    @endif
                </p>
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
            <section aria-labelledby="position-information">
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h2 id="position-information" class="text-lg font-medium leading-6 text-gray-900">{{ __('Information') }}</h2>
                        <p class="max-w-2xl mt-1 text-sm text-gray-500">{{ __('Details about your position.') }}</p>
                    </div>
                    <div class="px-4 py-6 border-t border-gray-200 sm:px-6">
                        <dl class="grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-3">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">{{ __('Asset code') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $position->asset->code }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">{{ __('Initial amount') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ money($position->initial_amount) }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">{{ __('Strategy') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ config('utils.strategies')[$position->strategy] }}</dd>
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
                                <dd class="mt-1 text-sm text-gray-900">{{ $position->max_stop ?? '0' }}%</dd>
                            </div>
                        </dl>
                    </div>
                    <div>
                        <x-admin.links.transparent href="https://finance.yahoo.com/quote/{{ $position->asset->yahoo_code }}" target="_blank">
                            {{ $position->asset->name . ' ' . __('in Yahoo Finance') }}
                            <i class="ml-1 fas fa-external-link"></i>
                        </x-admin>
                    </div>
                </div>
            </section>
            <section aria-labelledby="trades-history">
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h2 id="position-information" class="text-lg font-medium leading-6 text-gray-900">{{ __('Order History') }}</h2>
                        <p class="max-w-2xl mt-1 text-sm text-gray-500">{{ __('Last 10 orders executed for the asset.') }}</p>
                    </div>
                    <div class="border-t border-gray-200">
                        <div class="max-w-6xl">
                            <div class="flex flex-col">
                                <div class="min-w-full overflow-hidden overflow-x-auto align-middle shadow sm:rounded-b-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase bg-gray-50">{{ __('Order ID') }}</th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase bg-gray-50">{{ __('Side') }}</th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase bg-gray-50">{{ __('Avg. Price') }}</th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase bg-gray-50">{{ __('Quantity') }}</th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase bg-gray-50">{{ __('Status') }}</th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase bg-gray-50">{{ __('Type') }}</th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase bg-gray-50">{{ __('Date') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse ($binance_orders as $order)
                                            <tr class="bg-white">
                                                <td class="py-2 pl-4 pr-3 text-xs text-gray-900 whitespace-nowrap sm:pl-6">{{ $order['orderId'] }}</td>
                                                <td class="px-2 py-2 text-xs font-medium text-center whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium {{ $order['side'] == 'SELL' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }} lowercase">
                                                        {{ __($order['side']) }}
                                                    </span>
                                                </td>
                                                <td class="px-2 py-2 text-xs text-center text-gray-900 whitespace-nowrap">
                                                    {{ money($order['avgPrice']) }}
                                                </td>
                                                <td class="px-2 py-2 text-xs text-center text-gray-900 whitespace-nowrap">{{ $order['executedQty'] }}</td>
                                                <td class="px-2 py-2 text-xs text-center text-gray-900 whitespace-nowrap">{{ $order['status'] }}</td>
                                                <td class="px-2 py-2 text-xs text-center text-gray-900 whitespace-nowrap">{{ $order['type'] }}</td>
                                                <td class="px-2 py-2 text-xs text-center text-gray-900 whitespace-nowrap">{{ date('d/m/Y', $order['updateTime']/1000) }}</td>
                                            </tr>
                                            @empty
                                            <tr class="text-center bg-white">
                                                <td class="py-4 text-sm text-gray-500 whitespace-nowrap sm:pl-6" colspan="7">{{ __('No orders yet.') }}</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <section aria-labelledby="current-binance-position" class="lg:col-start-3 lg:col-span-1">
            <div class="px-4 py-5 bg-white shadow sm:rounded-lg sm:px-6">
                <h2 id="current-binance-position" class="text-lg font-medium text-gray-900">{{ __('Current position in Binance') }}</small></h2>
                <div class="flow-root mt-6">
                    @if ($binance_position != null && $binance_position['positionAmt'] != 0)
                    <div class="-ml-4 -mr-4 sm:-ml-6 sm:-mr-6">
                        <dl>
                            <div class="grid grid-cols-3 px-4 py-3 bg-white sm:gap-4 sm:px-6">
                                <dt class="text-xs font-medium text-gray-500">{{ __('Side') }}</dt>
                                <dd class="col-span-2 mt-1 ml-2 text-xs text-gray-900 sm:mt-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium {{ $binance_position['side'] == 'short' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }} lowercase">
                                        {{ $binance_position['side'] }}
                                    </span>
                                </dd>
                            </div>
                            <div class="grid grid-cols-3 px-4 py-3 bg-gray-50 sm:gap-4 sm:px-6">
                                <dt class="text-xs font-medium text-gray-500">{{ __('Margin') }}</dt>
                                <dd class="col-span-2 mt-1 ml-2 text-xs text-gray-900 sm:mt-0">{{ money($binance_position['isolatedWallet']) }}</dd>
                            </div>
                            <div class="grid grid-cols-3 px-4 py-3 bg-white sm:gap-4 sm:px-6">
                                <dt class="text-xs font-medium text-gray-500">{{ __('Quantity') }}</dt>
                                <dd class="col-span-2 mt-1 ml-2 text-xs text-gray-900 sm:mt-0">{{ $binance_position['positionAmt'] }}</dd>
                            </div>
                            <div class="grid grid-cols-3 px-4 py-3 bg-gray-50 sm:gap-4 sm:px-6">
                                <dt class="text-xs font-medium text-gray-500">{{ __('Entry price') }}</dt>
                                <dd class="col-span-2 mt-1 ml-2 text-xs text-gray-900 sm:mt-0">{{ money($binance_position['entryPrice']) }}</dd>
                            </div>
                            <div class="grid grid-cols-3 px-4 py-3 bg-white sm:gap-4 sm:px-6">
                                <dt class="text-xs font-medium text-gray-500">{{ __('Liquidation Price') }}</dt>
                                <dd class="col-span-2 mt-1 ml-2 text-xs text-gray-900 sm:mt-0">{{ money($binance_position['liquidationPrice']) }}</dd>
                            </div>
                            <div class="grid grid-cols-3 px-4 py-3 bg-gray-50 sm:gap-4 sm:px-6">
                                <dt class="text-xs font-medium text-gray-500">{{ __('Result') }}</dt>
                                <dd class="col-span-2 mt-1 ml-2 text-xs text-gray-900 sm:mt-0">
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium {{ $binance_position['result'] < 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        <i class="fas {{ $binance_position['result'] < 0 ? 'fa-arrow-down' : 'fa-arrow-up' }} mr-1 text-xs"></i>
                                        {{ abs($binance_position['result']) }}%
                                    </span>
                                </dd>
                            </div>
                            <div class="grid grid-cols-3 px-4 py-3 bg-white sm:gap-4 sm:px-6">
                                <dt class="text-xs font-medium text-gray-500">{{ __('Profit') }}</dt>
                                <dd class="col-span-2 mt-1 ml-2 text-xs text-gray-900 sm:mt-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium {{ $binance_position['unRealizedProfit'] < 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ money(abs($binance_position['unRealizedProfit'])) }}
                                        <i class="fas {{ $binance_position['unRealizedProfit'] < 0 ? 'fa-arrow-down' : 'fa-arrow-up' }} ml-1 text-xs"></i>
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                    <div class="flex flex-col mt-6 justify-stretch">
                        <x-admin.links.primary href="https://www.binance.com/en/futures/{{ $position->asset->code }}" target="_blank">
                            {{ __('See at Binance') }}
                        </x-admin.links.primary>
                    </div>
                    @else
                    <p class="text-gray-500">{{ __('No position yet.') }}</p>
                    @endif
                </div>
            </div>
        </section>
    </div>
</div>

