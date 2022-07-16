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
            <i class="float-right mb-2 text-gray-800 fas fa-fw fa-exclamation-square" data-tippy-placement="left" data-tippy-content="{{ __('We calculate your performance from the last 30 days until now.') }}"></i>
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
                    <dt class="text-base font-normal text-gray-900">Avg. Click Rate</dt>
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
</div>
