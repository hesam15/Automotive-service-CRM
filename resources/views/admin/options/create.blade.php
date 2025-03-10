@extends('layouts.app')

@section('title', 'ثبت خدمت')

@section('content')
<div class="max-w-4xl mx-auto py-4 md:py-6">
    
    <x-errors-success-label />
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-100">
            <h5 class="text-xl font-semibold text-gray-800">ثبت خدمت</h5>
        </div>

        <div class="p-6">

            <form action="{{route('options.store')}}" method="post" class="space-y-6">
                @csrf
                <div class="space-y-4">
                    <!-- Option Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">نام خدمت</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
            
                    <!-- Option Values -->
                    <div id="option-values">
                        <label class="block text-sm font-medium text-gray-700 mb-1">مقادیر</label>
                        <div class="option-values-container space-y-6">
                            <div class="option-field grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">نام آپشن</label>
                                    <input type="text" 
                                        name="options[]" 
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">مقادیر</label>
                                    <input type="text" 
                                        name="values[]" 
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="مقادیر را با ویرگول(،) جدا کنید">
                                </div>
                            </div>
                        </div>
            
                        <!-- Action Buttons -->
                        <div class="flex gap-3 mt-4">
                            <button type="button" class="option-add inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200 transition-colors duration-200">
                                <i class="material-icons-round text-lg ml-1">add</i>
                                اضافه کردن آپشن
                            </button>
                            <button type="button" class="option-remove inline-flex items-center px-4 py-2 bg-rose-100 text-rose-800 rounded-lg hover:bg-rose-200 transition-colors duration-200">
                                <i class="material-icons-round text-lg ml-1">remove</i>
                                حذف کردن آپشن
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit"
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    ثبت
                </button>
            </form>
        </div>
    </div>
</div>
@endsection