
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
     class="modal fixed inset-0 z-50 hidden" 
     aria-labelledby="modal-title-{{ $id }}" 
     role="dialog" 
     aria-modal="true">
    
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

    {{-- Modal Panel --}}
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="modal-content relative transform bg-white text-right shadow-xl rounded-xl transition-all w-full flex flex-col max-h-[90vh]
                        {{ $maxWidth === 'sm' ? 'sm:max-w-sm' : '' }}
                        {{ $maxWidth === 'md' ? 'sm:max-w-md' : '' }}
                        {{ $maxWidth === 'lg' ? 'sm:max-w-lg' : '' }}
                        {{ $maxWidth === 'xl' ? 'sm:max-w-xl' : '' }}
                        {{ $maxWidth === '2xl' ? 'sm:max-w-2xl' : '' }}
                        {{ $maxWidth === '3xl' ? 'sm:max-w-3xl' : '' }}
                        {{ $maxWidth === '4xl' ? 'sm:max-w-4xl' : '' }}
                        {{ $maxWidth === '5xl' ? 'sm:max-w-5xl' : '' }}
                        {{ $maxWidth === '6xl' ? 'sm:max-w-6xl' : '' }}
                        {{ $maxWidth === '7xl' ? 'sm:max-w-7xl' : '' }}">
                
                {{-- Header - Fixed --}}
                <div class="bg-gray-50 px-6 py-4 rounded-t-xl border-b border-gray-200 flex-none">
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
                      class="flex flex-col flex-1 min-h-0">
                    @unless($method === 'GET')
                        @csrf
                        @method($method)
                    @endunless

                    {{-- Body - Scrollable --}}
                    <div class="flex-1 overflow-y-auto bg-white">
                        <div class="px-6 py-6">
                            <div class="space-y-4">
                                {{ $slot }}
                            </div>
                        </div>
                    </div>

                    {{-- Footer - Fixed --}}
                    @if($showFooter)
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 rounded-b-xl flex flex-row-reverse gap-2 flex-none">
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