@section('head.title', __('Create position'))

<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">{{ __('Positions') }}</h1>
            <p class="mt-2 text-sm text-gray-700">{{ __('Create new position for an asset within a strategy.') }}</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <x-admin.links.white href="{{ route('positions') }}">
                <i class="mr-2 fas fa-angle-left"></i>
                {{ __('Go back') }}
            </x-admin.links.white>
        </div>
    </div>
    <div class="mt-10 shadow sm:rounded-md sm:overflow-hidden">
        <form wire:submit.prevent="save" class="space-y-8 divide-y divide-gray-200">
            <div class="overflow-hidden shadow sm:rounded-md">
                <div class="px-4 py-5 bg-white sm:p-6">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <x-admin.form.label for="asset" tippy="{{ __('Escolha o ativo que deseja que o robô opere.') }}">
                                {{ __('Asset') }}
                            </x-admin.form.label>
                            <x-admin.form.select id="asset" name="asset" wire:model.defer="asset">
                                <option value="0">{{ __('Choose') }}</option>
                                @foreach ($assets as $asset)
                                    <option value="{{ $asset->id }}">{{ $asset->name . ' - ' . $asset->code }}</option>
                                @endforeach
                            </x-admin.form.select>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <x-admin.form.label for="strategy" tippy="{{ __('Para qual estratégia sua posição deve ser operada.') }}">
                                {{ __('Strategy') }}
                            </x-admin.form.label>
                            <x-admin.form.select id="strategy" name="strategy" wire:model.defer="strategy">
                                <option value="0">{{ __('Choose') }}</option>
                                @foreach (config('utils.strategies') as $key => $strategy)
                                    <option value="{{ $key }}">{{ $strategy }}</option>
                                @endforeach
                            </x-admin.form.select>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <x-admin.form.label for="granularity" tippy="{{ __('Qual a granularidade dos preços que deve ser feito o estudo e as operações. Hoje só é possível operar no gráfico diário (1d).') }}">
                                {{ __('Granularity') }}
                            </x-admin.form.label>
                            <x-admin.form.select id="granularity" name="granularity" wire:model.defer="granularity">
                                <option value="0">{{ __('Choose') }}</option>
                                @foreach (config('utils.granularities') as $key => $granularity)
                                    <option value="{{ $granularity }}">{{ $granularity }}</option>
                                @endforeach
                            </x-admin.form.select>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <x-admin.form.label for="amount" max="Min: 100" tippy="{{ __('Valor inicial da posição. Você precisa ter esse valor disponível na carteira de Futuros da Binance.') }}">
                                {{ __('Amount') }}
                            </x-admin.form.label>
                            <x-admin.form.input type="text" name="amount" id="amount" wire:model.defer="amount" class="pl-10" x-data="{}" x-ref="amount" x-init="new Cleave($refs.amount, { numeral: true, numeralThousandsGroupStyle: 'thousand', delimiter: '.', numeralDecimalMark: ',', numeralPositiveOnly: true })">
                                <x-slot name="left">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">
                                            {{ __('USD $') }}
                                        </span>
                                    </div>
                                </x-slot>
                            </x-admin.form.input>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <x-admin.form.label for="leverage" max="Min: 1 | Max: 20" tippy="{{ __('O quanto deseja entrar alavancado. Não recomendamos alavancar mais do que 3x, para diminuir o risco de ser liquidado pela Binance.') }}">
                                {{ __('Leverage') }}
                            </x-admin.form.label>
                            <x-admin.form.input type="text" name="leverage" id="leverage" wire:model.defer="leverage" class="pr-3" x-data="{}" x-ref="leverage" x-init="new Cleave($refs.leverage, { blocks: [2], numericOnly: true })">
                                <x-slot name="right">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">
                                            {{ __('x') }}
                                        </span>
                                    </div>
                                </x-slot>
                            </x-admin.form.input>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <x-admin.form.label for="max_stop" tippy="{{ __('O max stop é utilizado para entrar no meio de uma estratégia já rolando. Ex: A estratégia Hilo está no meio da tendência. O robô entrará caso o candle fechar no preço próximo tantos porcentos ao valor do Hilo.') }}">
                                {{ __('Max Stop') }}
                            </x-admin.form.label>
                            <x-admin.form.input type="text" name="max_stop" id="max_stop" wire:model.defer="max_stop" class="pr-3" x-data="{}" x-ref="max_stop" x-init="new Cleave($refs.max_stop, { blocks: [2], numericOnly: true })">
                                <x-slot name="right">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">
                                            {{ __('%') }}
                                        </span>
                                    </div>
                                </x-slot>
                            </x-admin.form.input>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 text-right bg-gray-50 sm:px-6">
                    <x-admin.buttons.primary submit>
                        {{ __('Create') }}
                    </x-admin.buttons.primary>
                </div>
            </div>
        </form>
    </div>
</div>
