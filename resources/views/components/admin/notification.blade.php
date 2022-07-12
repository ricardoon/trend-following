@props([
    'type' => session('flash.type', 'success'),
    'message' => session('flash.message')
])

<div x-data="{{ json_encode([ 'show' => false, 'type' => $type, 'message' => $message ]) }}"
    x-show="show && message" x-cloak
    x-init="setTimeout(() => show = true, 100);"
    class="fixed inset-0 z-20 flex items-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start">
    <div class="flex flex-col items-center w-full space-y-4 sm:items-end"
        x-show="show && message"
        x-transition:enter="transition ease-in-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-12 sm:-translate-y-12"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-12 sm:-translate-y-12"
    >
        <div class="w-full max-w-sm overflow-hidden bg-white rounded-lg shadow-md pointer-events-auto ring-1 ring-black ring-opacity-5">
            <div class="p-4">
                <div class="flex items-start">
                    <div class="shrink-0">
                        <i class="text-green-400 far fa-fw fa-check-circle"></i>
                    </div>

                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-medium text-gray-800">
                            {{ $message }}
                        </p>
                    </div>

                    <div class="flex ml-4 shrink-0">
                        <button class="inline-flex py-1 rounded-md cursor-pointer focus:outline-none"
                            type="button"
                            aria-label="Dismiss"
                            x-on:click="show = false"
                        >
                            <i class="fal fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
