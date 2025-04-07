@props([
    'id',
    'title',
    'action' => '#',
    'method' => 'POST',
    'maxWidth' => '4xl',
    'showFooter' => true,
    'submitLabel' => 'ذخیره تغییرات',
    'cancelLabel' => 'انصراف'
])

@pushOnce('scripts')
<script>
    window.requiredManagers = window.requiredManagers || [];
    if (!window.requiredManagers.includes('modalManager')) {
        window.requiredManagers.push('modalManager');
    }
</script>
@endPushOnce

<div id="{{ $id }}" 
     class="modal hidden fixed inset-0 z-50 overflow-y-auto" 
     aria-labelledby="modal-title-{{ $id }}" 
     role="dialog" 
     aria-modal="true">
    
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

    {{-- Modal Panel --}}
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="modal-content relative transform overflow-hidden rounded-lg bg-white text-right shadow-xl transition-all sm:my-8 max-w-{{ $maxWidth }} w-full">                {{-- Header --}}
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 sm:px-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900" id="modal-title-{{ $id }}">
                            {{ $title }}
                        </h3>
                        <button type="button" 
                                class="modal-close rounded-md bg-gray-50 text-gray-400 hover:text-gray-500 focus:outline-none"
                                data-modal-target="{{ $id }}">
                            <span class="sr-only">بستن</span>
                            <i class="material-icons-round text-xl">close</i>
                        </button>
                    </div>
                </div>

                {{-- Form --}}
                <form id="editForm-{{ $id }}" 
                      action="{{ $action }}" 
                      method="{{ $method === 'GET' ? 'GET' : 'POST' }}"
                      class="relative">
                    @unless($method === 'GET')
                        @csrf
                        @method($method)
                    @endunless

                    {{-- Body --}}
                    <div class="bg-white px-4 py-5 sm:p-6">
                        {{ $slot }}
                    </div>

                    {{-- Footer --}}
                    @if($showFooter)
                        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6 flex flex-row-reverse gap-2">
                            <button type="submit"
                                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                {{ $submitLabel }}
                            </button>
                            <button type="button"
                                    class="modal-close inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                    data-modal-target="{{ $id }}">
                                {{ $cancelLabel }}
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
