@props([
    'id',
    'title',
    'action' => "#",
    'method' => "POST",
    'maxWidth' => '4xl',
    'showFooter' => true
])

<div id="{{ $id }}" data-modal-id="{{ $id }}" class="fixed inset-0 z-50 hidden" aria-hidden="true">
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-{{ $maxWidth }} max-h-[90vh] flex flex-col">
            <!-- Fixed Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h5 class="text-lg font-semibold text-gray-800">{{ $title }}</h5>
                <button type="button" onclick="closeModal('{{ $id }}')" class="text-gray-500 hover:text-gray-700 transition-colors">
                    <i class="material-icons-round text-xl">close</i>
                </button>
            </div>

            <!-- Modal Form -->
            <form id="editForm-{{$id}}" action="{{ $action }}" method="{{ $method }}" class="flex flex-col flex-1 min-h-0">
                @csrf
                <!-- Scrollable Content -->
                <div class="flex-1 overflow-y-auto px-6 py-4" id="modal-content-{{ $id }}">
                    {{ $slot }}
                </div>

                <!-- Fixed Footer -->
                @if($showFooter)
                <div class="flex justify-end gap-3 px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <button type="button" onclick="closeModal('{{ $id }}')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        انصراف
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                        ذخیره تغییرات
                    </button>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>
