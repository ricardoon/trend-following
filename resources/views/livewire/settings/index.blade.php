@section('head.title', __('Settings'))

<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">{{ __('Settings') }}</h1>
            <p class="mt-2 text-sm text-gray-700">{{ __('All your settings in one place.') }}</p>
        </div>
    </div>
    <div class="mt-8 xl:grid xl:grid-cols-12 xl:gap-x-5">
        <div class="space-y-6 xl:px-0 xl:col-span-10">
            <section aria-labelledby="binance-settings-heading">
                <form wire:submit.prevent="save_binance" autocomplete="off">
                    <input autocomplete="false" name="hidden" type="text" style="display:none;">
                    <div class="shadow sm:rounded-md sm:overflow-hidden">
                        <div class="px-4 py-6 bg-white sm:p-6">
                            <div>
                                <h2 id="binance-settings-heading" class="text-lg font-medium leading-6 text-gray-900">{{ __('Binance') }}</h2>
                                <p class="mt-1 text-sm text-gray-500">{{ __('Update your Binance keys. Please note that updating your keys could affect your positions.') }}</p>
                            </div>
                            <div class="grid grid-cols-4 gap-6 mt-6">
                                <div class="col-span-4 sm:col-span-2">
                                    <label for="binance_api_key" class="block text-sm font-medium text-gray-700">{{ __('API Key') }}</label>
                                    <x-admin.form.input type="text" name="binance_api_key" id="binance_api_key" wire:model.defer="binance_api_key"></x-admin.form.input>
                                </div>
                                <div class="col-span-4 sm:col-span-2">
                                    <label for="binance_api_secret" class="block text-sm font-medium text-gray-700">{{ __('API Secret') }}</label>
                                    <x-admin.form.input type="text" name="binance_api_secret" id="binance_api_secret" wire:model.defer="binance_api_secret"></x-admin.form.input>
                                </div>
                            </div>
                        </div>
                        <div class="relative px-4 py-3 text-right bg-gray-50 sm:px-6">
                            @if (isset($settings->binance['api_key']))
                            <span class="absolute flex items-center mt-2 text-sm font-medium text-gray-500 sm:mr-6">
                                <i class="fas fa-check-circle mr-1.5 text-green-400"></i>
                                {{ __('Binance configured') }}
                            </span>
                            @endif
                            <x-admin.buttons.primary submit>{{ __('Save') }}</x-admin.buttons.primary>
                        </div>
                    </div>
                </form>
            </section>
            <section aria-labelledby="commissions-history-heading">
                <div class="pt-6 bg-white shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 sm:px-6">
                        <h2 id="commissions-history-heading" class="text-lg font-medium leading-6 text-gray-900">{{ __('Commissions history') }}</h2>
                    </div>
                    <div class="flex flex-col mt-6">
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                <div class="overflow-hidden border-t border-gray-200">
                                @if ($commissions)
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Date</th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Description</th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Amount</th>
                                                <th scope="col" class="relative px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                                    <span class="sr-only">View receipt</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr>
                                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                                    <time datetime="2020-01-01">1/1/2020</time>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">Business Plan - Annual Billing</td>
                                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">CA$109.00</td>
                                                <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                                    <a href="#" class="text-orange-600 hover:text-orange-900">View receipt</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @else
                                    <p class="py-6 text-center text-gray-500">{{ __('No commissions yet.') }}</p>
                                @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
