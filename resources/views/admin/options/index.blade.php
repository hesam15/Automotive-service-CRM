@extends('layouts.app')

@section('title', 'لیست آپشن‌ها')

@pushOnce('scripts')
<script>
    window.requiredManagers = window.requiredManagers || [];
    window.requiredManagers.push('optionsManager');
</script>
@endPushOnce

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4">
    <x-errors-success-label />

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-100 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800">لیست آپشن‌ها</h2>
            <button type="button"
                class="modal-trigger inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition duration-200"
                data-modal-target="optionCreateModal">
                <span class="material-icons-round text-base ml-1">add</span>
                افزودن آپشن جدید
            </button>
        </div>

        <div class="p-6">
            @if($options->isEmpty())
                <div class="px-6 py-12 flex flex-col items-center justify-center">
                    <i class="material-icons-round text-gray-400 text-6xl mb-4">build</i>
                    <h2 class="text-xl font-bold text-gray-800 mb-4">هیچ آپشنی ثبت نشده است.</h2>
                    <a href="{{ route('options.create') }}" 
                    class="modal-trigger inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200" 
                    data-modal-target="optionCreateModal">
                        <i class="material-icons-round text-xl ml-2">add</i>
                        افزودن آپشن جدید
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-right text-sm font-medium text-gray-500 w-1/3">نام آپشن</th>
                                <th class="px-4 py-2 text-right text-sm font-medium text-gray-500 w-1/3">مقادیر</th>
                                <th class="px-4 py-2 text-right text-sm font-medium text-gray-500 w-1/3">عملیات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        @foreach($options as $option)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-900 w-1/3">{{ $option->name }}</td>
                            <td class="px-4 py-3 w-1/3">
                                <button type="button"
                                    class="modal-trigger inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded hover:bg-blue-200 transition-colors duration-200"
                                    data-modal-target="servicesModal-{{$option->id}}">
                                    <i class="material-icons-round text-sm">visibility</i>
                                    <span class="text-xs mr-0.5">مشاهده خدمات</span>
                                </button>
                            </td>
                            <td class="px-4 py-3 w-1/3">
                                <div class="flex gap-2">
                                    <button type="button"
                                        class="modal-trigger inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded hover:bg-blue-200 transition-colors duration-200"
                                        data-modal-target="optionEditModal-{{$option->id}}">
                                        <i class="material-icons-round text-sm">edit</i>
                                        <span class="text-xs mr-0.5">ویرایش</span>
                                    </button>
                                    <button class="delete-btn inline-flex items-center px-2 py-1 bg-rose-100 text-rose-800 rounded hover:bg-rose-200 transition-colors duration-200"
                                        data-route="{{ route('options.destroy', $option->id) }}"
                                        data-type="option">
                                        <i class="material-icons-round text-sm">delete</i>
                                        <span class="text-xs mr-0.5">حذف</span>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Edit Option Modal -->
                        <x-edit-modal :id="'optionEditModal-'.$option->id" title="ویرایش آپشن" :action="route('options.update', $option->id)" method="POST">
                            @csrf
                            <div class="space-y-4">
                                <!-- Option Name -->
                                <div>
                                    <label for="name-{{$option->id}}" class="block text-sm font-medium text-gray-700 mb-1">نام آپشن</label>
                                    <input type="text" id="name-{{$option->id}}" name="name" value="{{ $option->name }}"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <!-- Option Values -->
                                <div id="option-values-{{$option->id}}">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">مقادیر</label>
                                    <div class="space-y-4 option-values-container" data-option-id="{{$option->id}}">
                                        @foreach($option->values as $service => $values)
                                            <div class="option-field grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">نام خدمت</label>
                                                    <input type="text" 
                                                        name="options[]" 
                                                        value="{{ $service }}"
                                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">مقادیر</label>
                                                    <input type="text" 
                                                        name="values[]" 
                                                        value="{{ implode(', ', $values) }}"
                                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                        placeholder="مقادیر را با کاما جدا کنید">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="flex gap-3 mt-4">
                                        <button type="button" 
                                            class="option-add-btn inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200 transition-colors duration-200"
                                            data-container="option-values-{{$option->id}}">
                                            <i class="material-icons-round text-lg ml-1">add</i>
                                            اضافه کردن آپشن
                                        </button>
                                        <button type="button" 
                                            class="option-remove-btn inline-flex items-center px-4 py-2 bg-rose-100 text-rose-800 rounded-lg hover:bg-rose-200 transition-colors duration-200"
                                            data-container="option-values-{{$option->id}}">
                                            <i class="material-icons-round text-lg ml-1">remove</i>
                                            حذف کردن آپشن
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </x-edit-modal>

                            <x-view-modal :id="'servicesModal-'.$option->id" :title="'خدمات ' . $option->name">
                                <div class="space-y-4">
                                    @foreach($option->values as $service => $values)
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $service }}</h3>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($values as $value)
                                                    <span class="px-3 py-1 bg-white text-gray-800 rounded-full text-sm border border-gray-200">
                                                        {{ $value }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </x-view-modal>
                            
                        @endforeach
                    </tbody>
                </table>
                </div>
            @endif
        </div>
    </div>
</div>

<x-create-modal 
    id="optionCreateModal" 
    title="افزودن آپشن جدید" 
    :action="route('options.store')" 
    method="POST"
    maxWidth="2xl"
    submitLabel="ایجاد آپشن">
    
    @csrf
    <div class="space-y-4">
        <!-- Option Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">نام آپشن</label>
            <input type="text" 
                   id="name" 
                   name="name" 
                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   placeholder="نام آپشن را وارد کنید"
                   required>
        </div>

        <!-- Option Values -->
        <div id="option-values">
            <label class="block text-sm font-medium text-gray-700 mb-1">مقادیر</label>
            <div class="space-y-4 option-values-container">
                <div class="option-field grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">نام خدمت</label>
                        <input type="text" 
                               name="options[]" 
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="نام خدمت را وارد کنید"
                               required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">مقادیر</label>
                        <input type="text" 
                               name="values[]" 
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="مقادیر را با ویرگول(،) جدا کنید"
                               required>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex gap-3 mt-4">
                <button type="button" 
                        class="option-add-btn inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200 transition-colors duration-200"
                        data-container="option-values">
                    <i class="material-icons-round text-lg ml-1">add</i>
                    اضافه کردن آپشن
                </button>
                <button type="button" 
                        class="option-remove-btn inline-flex items-center px-4 py-2 bg-rose-100 text-rose-800 rounded-lg hover:bg-rose-200 transition-colors duration-200"
                        data-container="option-values">
                    <i class="material-icons-round text-lg ml-1">remove</i>
                    حذف کردن آپشن
                </button>
            </div>
        </div>
    </div>
</x-create-modal>

<x-delete-modal />

@endsection
