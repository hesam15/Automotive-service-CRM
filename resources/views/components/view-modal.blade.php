@props(['id', 'title'])

<div id="{{ $id }}" class="modal hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4">
        <!-- Background backdrop -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <!-- Modal panel -->
        <div class="relative bg-white rounded-lg max-w-2xl w-full shadow-xl flex flex-col max-h-[90vh]">
            <!-- Modal header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900" id="modal-title">
                    {{ $title }}
                </h3>
                <button type="button" class="modal-close text-gray-400 hover:text-gray-500">
                    <span class="material-icons-round">close</span>
                </button>
            </div>

            <!-- Modal content -->
            <div class="px-6 py-4 overflow-y-auto flex-1">
                {{ $slot }}
            </div>

            <!-- Modal footer -->
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
                <button type="button" class="modal-close px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                    بستن
                </button>
            </div>
        </div>
    </div>
</div>
