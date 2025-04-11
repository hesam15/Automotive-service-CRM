@extends('layouts.app')

@section('title', 'فرم ثبت خودرو')

@section('content')
<div class="min-h-screen p-4 md:p-6">
    <x-errors-success-label />

    <div class="max-w-md md:max-w-7xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-4 py-3 md:px-6 md:py-4 border-b border-gray-200 bg-gray-100">
                <h5 class="text-base md:text-xl font-semibold text-gray-800">فرم ثبت نام مشتری</h5>
            </div>

            <div class="p-4 md:p-6">
                <form action="{{route('customers.store')}}" method="POST">
                    @csrf
                    <div class="grid grid-cols-12 gap-4 md:gap-6 items-end">
                        <div class="col-span-5">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">نام و نام خانوادگی</label>
                            <input type="text" name="name" id="name" class="w-full px-3 md:px-4 py-2.5 md:py-2 text-sm border border-gray-300 rounded-lg md:rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div class="col-span-5">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">شماره تماس</label>
                            <input type="tel" name="phone" id="phone" class="w-full px-3 md:px-4 py-2.5 md:py-2 text-sm border border-gray-300 rounded-lg md:rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <button type="submit" class="col-span-2 px-4 py-2.5 md:py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                            ثبت اطلاعات
                        </button>
                    </div>
                </form>                           
            </div>
        </div>
    </div>
</div>
@endsection