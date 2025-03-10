<!-- resources/views/components/delete-modal.blade.php -->
<div id="deleteModal" class="modal fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50">
    <div class="fixed inset-0 flex items-center justify-center">
        <div class="modal-content bg-white rounded-xl shadow-lg overflow-hidden max-w-md w-full mx-4">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800"></h3>
                <button type="button" class="modal-close text-gray-500 hover:text-gray-700">
                    <span class="material-icons-round">close</span>
                </button>
            </div>
            
            <div class="p-6">
                <p class="text-gray-600 mb-6"></p>
                <form id="deleteForm" method="POST">
                    @csrf
                    <div class="flex justify-end gap-3">
                        <button type="button" class="modal-close px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                            انصراف
                        </button>
                        <button type="submit"
                                class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700">
                            حذف
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>