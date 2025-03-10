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

            <form action="{{route('store.option')}}" method="post" class="space-y-6">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        نام خدمت اصلی(برای مثال خدمات کارشناسی بدنه)
                    </label>
                    <input type="text" name="name" id="name" placeholder="نام خدمت"
                           class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200">
                </div>

                <div id="options_container" data-mode="single" class="space-y-4">
                    <div class="option-field grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                خدمات(برای مثال درب موتور)
                            </label>
                            <input type="text" name="sub_options[]" placeholder="نام آپشن"
                                   class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                مقادیر
                            </label>
                            <input type="text" name="sub_values[]" placeholder="مقادیر رو با ، جدا کنید"
                                   class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200">
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" id="option_add"
                            class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200 transition-colors duration-200">
                        <i class="material-icons-round text-lg ml-1">add</i>
                        اضافه کردن آپشن
                    </button>
                    <button type="button" id="option_remove"
                            class="inline-flex items-center px-4 py-2 bg-rose-100 text-rose-800 rounded-lg hover:bg-rose-200 transition-colors duration-200">
                        <i class="material-icons-round text-lg ml-1">remove</i>
                        حذف کردن آپشن
                    </button>
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