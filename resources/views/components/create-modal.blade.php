@props([
    'id',
    'title',
    'action' => '#',
    'method' => 'POST',
    'maxWidth' => 'lg',
    'showFooter' => true,
    'submitLabel' => 'ایجاد',
    'cancelLabel' => 'انصراف'
])

<div id="{{ $id }}" 
     class="modal fixed inset-0 z-50 hidden overflow-y-auto" 
     aria-labelledby="modal-title-{{ $id }}" 
     role="dialog" 
     aria-modal="true">
    
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

    {{-- Modal Panel --}}
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="modal-content relative transform overflow-hidden rounded-lg bg-white text-right shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-{{ $maxWidth }}">
                {{-- Header --}}
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
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
                <form id="createForm-{{ $id }}" 
                      action="{{ $action }}" 
                      method="{{ $method === 'GET' ? 'GET' : 'POST' }}"
                      class="relative">
                    @unless($method === 'GET')
                        @csrf
                        @method($method)
                    @endunless

                    {{-- Body --}}
                    <div class="bg-white px-6 py-6">
                        <div class="space-y-4">


                            {{-- اضافه کردن محتوای اضافی --}}
                            {{ $slot }}
                        </div>
                    </div>

                    {{-- Footer --}}
                    @if($showFooter)
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex flex-row-reverse gap-2">
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