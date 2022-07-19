@section('head.title', __('Dashboard'))

<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">{{ __('Dashboard') }}</h1>
            <p class="mt-2 text-sm text-gray-700">{{ __('Summary of your results so far.') }}</p>
        </div>
    </div>
    <div class="mt-5 xl:grid xl:grid-cols-12 xl:gap-x-5">
        <div class="space-y-6 xl:px-0 xl:col-span-10">
            <i class="float-right mb-2 text-gray-800 fas fa-fw fa-exclamation-square" data-tippy-placement="left" data-tippy-content="{{ __('We calculate your performance from the last 30 days until now and compare with the period before.') }}"></i>
            <dl class="grid clear-both grid-cols-1 mt-5 overflow-hidden bg-white divide-y divide-gray-200 rounded-lg shadow md:grid-cols-3 md:divide-y-0 md:divide-x">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-base font-normal text-gray-900">{{ __('Total Amount') }}</dt>
                    <dd class="flex items-baseline justify-between mt-1 md:block lg:flex">
                        <div class="flex items-baseline text-2xl font-semibold text-indigo-600">
                            $571,897
                        </div>
                        <div class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800 md:mt-2 lg:mt-0">
                            <i class="self-center flex-shrink-0 mr-1.5 text-green-500 fas fa-arrow-up"></i>
                            <span class="sr-only">{{ __('Increased by') }}</span>
                            12%
                        </div>
                    </dd>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-base font-normal text-gray-900">{{ __('Profit') }}</dt>
                    <dd class="flex items-baseline justify-between mt-1 md:block lg:flex">
                        <div class="flex items-baseline text-2xl font-semibold text-indigo-600">
                            $57,897
                        </div>
                        <div class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800 md:mt-2 lg:mt-0">
                            <i class="self-center flex-shrink-0 mr-1.5 text-green-500 fas fa-arrow-up"></i>
                            <span class="sr-only">{{ __('Increased by') }}</span>
                            20.02%
                        </div>
                    </dd>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-base font-normal text-gray-900">Result</dt>
                    <dd class="flex items-baseline justify-between mt-1 md:block lg:flex">
                        <div class="flex items-baseline text-2xl font-semibold text-indigo-600">
                            24.57%
                        </div>
                        <div class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800 md:mt-2 lg:mt-0">
                            <i class="self-center flex-shrink-0 mr-1.5 text-red-500 fas fa-arrow-down"></i>
                            <span class="sr-only">{{ __('Decreased by') }}</span>
                            4.05%
                        </div>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
    <div class="mt-16 xl:grid xl:grid-cols-12 xl:gap-x-5">
        <div class="space-y-6 xl:px-0 xl:col-span-10" x-data>
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium leading-6 text-gray-900">
                    {{ __('Results') }}
                    <span class="text-sm text-gray-500">{{ __('last 12 months') }}</span>
                </h3>
                <i class="text-gray-800 fas fa-fw fa-exclamation-square cursor-help" data-tippy-placement="left" data-tippy-content="{!! __('We compare your result from the last 12 months with the previous 12 months.') !!}"></i>
            </div>

            <div class="mt-5 bg-white rounded-lg shadow">
                <div class="px-4 pt-5 sm:p-6 sm:pb-0">
                    <div class="flex gap-16">
                        <div class="flex flex-col">
                            <p class="text-sm text-gray-700">{{ __('Total in the period') }}</p>
                            <p class="text-lg text-indigo-600">{{ money($lastYearAmount) }}</p>
                        </div>
                    </div>
                </div>

                <div x-ref="chart" x-init="new window.chart($refs.chart, {
                        chart: {
                            id: 'orders-last-year',
                            type: 'line',
                            height: 150,
                            foreColor: '#6b7280',
                            toolbar: {
                                show: false,
                            },
                            sparkline: {
                                enabled: false
                            },
                            zoom: {
                                enabled: false,
                            },
                        },
                        colors: [
                            'rgb(79,70,229)',
                            {{-- 'rgb(50,50,50)', --}}
                        ],
                        dataLabels: {
                            enabled: true,
                            formatter: function(val) {
                                return dollarUS.format(val)
                            },
                        },
                        stroke: {
                            width: 2,
                            curve: 'straight',
                        },
                        series: [
                            {
                                data: {{ json_encode($lastYear) }}
                            }
                        ],
                        xaxis: {
                            type: 'category',
                            categories: {{ json_encode($months) }},
                            labels: {
                                show: true
                            },
                            tickPlacement: 'between'
                        },
                        yaxis: {
                            type: 'numeric',
                            labels: {
                                formatter: function (val) {
                                    return dollarUS.format(val)
                                }
                            }
                        },
                        tooltip: {
                            enabled: false
                        },
                        legend: {
                            show: false
                        }
                    }).render();"
                ></div>
            </div>
        </div>
    </div>
</div>
