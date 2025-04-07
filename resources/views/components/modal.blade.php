@props(['id', 'title'])

@pushOnce('scripts')
<script>
    window.requiredManagers = window.requiredManagers || [];
    if (!window.requiredManagers.includes('modalManager')) {
        window.requiredManagers.push('modalManager');
    }
</script>
@endPushOnce

<div id="{{ $id }}" class="modal hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <!-- Modal panel -->
        <div class="inline-block align-middle bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-2xl sm:w-full">
            <!-- Header -->
            <div class="bg-gray-100 px-4 py-3 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900" id="modal-title">
                    {{ $title }}
                </h3>
            </div>

            <!-- Content -->
            <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4">
                {{ $slot }}
            </div>

            <!-- Footer -->
            @if (isset($footer))
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>