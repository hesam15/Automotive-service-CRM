@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-4 md:py-6">

    <x-errors-success-label />
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-100">
            <h5 class="text-xl font-semibold text-gray-800">مدیریت خدمات</h5>
            <a href="{{ route('create.option') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                <i class="material-icons-round text-xl ml-2">add</i>
                <span>افزودن خدمت جدید</span>
            </a>
        </div>

        <!-- Table Content -->
        @if($options->isEmpty())
            <div class="px-6 py-12 flex flex-col items-center justify-center">
                <i class="material-icons-round text-gray-400 text-6xl mb-4">info</i>
                <h2 class="text-xl font-bold text-gray-800 mb-4">هیچ خدمتی یافت نشد.</h2>
                <a href="{{ route('create.option') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    <i class="material-icons-round text-xl ml-2">add</i>
                    افزودن خدمت جدید
                </a>
            </div>
        @else
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-right text-sm font-medium text-gray-700 w-2/5">نام خدمت</th>
                                <th class="px-4 py-3 text-right text-sm font-medium text-gray-700 w-2/5">آپشن‌ها</th>
                                <th class="px-4 py-3 text-right text-sm font-medium text-gray-700 w-1/5">عملیات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($options as $option)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4">
                                        <h5 class="text-base font-medium text-gray-900">{{ $option->name }}</h5>
                                    </td>
                                    <td class="px-4 py-4">
                                        <button onclick="openModal('viewModal{{ $option->id }}')" class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded hover:bg-blue-200 transition-colors duration-200">
                                            <i class="material-icons-round text-sm">visibility</i>
                                            <span class="text-xs mr-0.5">مشاهده آپشن ها</span>
                                        </button>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-2">
                                            <button onclick="openModal('editModal-{{$option->id}}')" class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded hover:bg-blue-200 transition-colors duration-200">
                                                <i class="material-icons-round text-sm">edit</i>
                                                <span class="text-xs mr-0.5">ویرایش</span>
                                            </button>
                                            <button class="delete-btn inline-flex items-center px-2 py-1 bg-rose-100 text-rose-800 rounded hover:bg-rose-200 transition-colors duration-200"
                                                data-route="{{ route('delete.option', $option->id) }}"
                                                data-type="option">
                                                <i class="material-icons-round text-sm">delete</i>
                                                <span class="text-xs mr-0.5">حذف</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- View Modal -->
                                <x-edit-modal id="viewModal{{ $option->id }}" 
                                    title="آپشن‌های {{ $option->name }}"
                                    :show-footer="false">
                                    <ul class="space-y-3">
                                        @foreach(json_decode($option->values) as $key => $values)
                                            <li class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded-lg">
                                                <span class="text-gray-800">{{$key}}</span>
                                                <span class="text-gray-600">
                                                    @foreach($values as $index => $value)
                                                        {{ $value }}{{ !$loop->last ? '، ' : '' }}
                                                    @endforeach
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </x-edit-modal>

                                <!-- Edit Modal -->
                                <x-edit-modal id="editModal-{{$option->id}}" 
                                    title="ویرایش خدمت"
                                    action="{{ route('update.option', $option->id) }}"
                                    method="POST">
                                    <div class="space-y-8" id="modal-{{$option->id}}">
                                        <!-- Main Service Name -->
                                        <div>
                                            <label for="name_{{$option->id}}" class="block text-sm font-medium text-gray-700 mb-3">
                                                نام خدمت اصلی
                                            </label>
                                            <input type="text" name="name" id="name_{{$option->id}}"
                                                value="{{ $option->name }}" placeholder="نام خدمت"
                                                class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200">
                                        </div>
                                    
                                        <!-- Options Container -->
                                        <div id="options_container_{{$option->id}}">
                                            @foreach(json_decode($option->values) as $key => $values)
                                                <div class="option-field grid grid-cols-2 gap-8 mt-3">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-3">خدمات</label>
                                                        <input type="text" name="sub_options[{{$loop->index}}]"
                                                            value="{{ $key }}" placeholder="نام آپشن"
                                                            class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200">
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-3">مقادیر</label>
                                                        <input type="text" name="sub_values[{{$loop->index}}]"
                                                            value="{{ implode(', ', $values) }}"
                                                            class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    
                                        <!-- Action Buttons -->
                                        <div class="flex gap-4 pt-4">
                                            <button type="button" onclick="addOptionField('{{$option->id}}')" 
                                                class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200">
                                                <i class="material-icons-round text-lg ml-1">add</i>
                                                اضافه کردن آپشن
                                            </button>
                                            <button type="button" onclick="removeOptionField('{{$option->id}}')"
                                                class="inline-flex items-center px-4 py-2 bg-rose-100 text-rose-800 rounded-lg hover:bg-rose-200">
                                                <i class="material-icons-round text-lg ml-1">remove</i>
                                                حذف کردن آپشن
                                            </button>
                                        </div>
                                    </div>
                                    
                                </x-edit-modal>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>

<x-delete-modal />
@endsection