@props([ 'current' ])
<div class="max-w-3xl px-4 mx-auto mt-6 sm:px-6 md:px-8">
    <div class="sm:hidden">
        <select id="tabs" aria-label="{{ __('Aba selecionada') }}" class="block w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
            <option value="general">{{ __('Perfil') }}</option>
            <option value="location">{{ __('Localidade') }}</option>      
            <option value="features">{{ __('Funcionalidades') }}</option>      
            <option value="social">{{ __('Redes Sociais') }}</option>
        </select>
    </div>

    <div class="hidden sm:block">
        <nav class="flex space-x-4">
            <a href="{{ route('settings.general') }}" class="px-3 py-2 text-sm font-medium rounded-md text-gray-500 hover:text-gray-700 @if($current == 'general') bg-gray-200 @endif">
                <span>{{ __('Perfil') }}</span>
            </a>
  
            <a href="{{ route('settings.location') }}" class="px-3 py-2 text-sm font-medium rounded-md text-gray-500 hover:text-gray-700 @if($current == 'location') bg-gray-200 @endif">
                <span>{{ __('Localidade') }}</span>
            </a>
  
            <a href="{{ route('settings.features') }}" class="px-3 py-2 text-sm font-medium rounded-md text-gray-500 hover:text-gray-700 @if($current == 'features') bg-gray-200 @endif">
                <span>{{ __('Funcionalidades') }}</span>
            </a>
  
            <a href="{{ route('settings.social') }}" class="px-3 py-2 text-sm font-medium rounded-md text-gray-500 hover:text-gray-700 @if($current == 'social') bg-gray-200 @endif">
                <span>{{ __('Redes Sociais') }}</span>
            </a>
        </nav>
    </div>
</div>