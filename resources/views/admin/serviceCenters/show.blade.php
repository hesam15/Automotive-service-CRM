@extends('layouts.app')

@section('title', 'مشخصات مجموعه ' . $serviceCenter->name)

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">{{ $serviceCenter->name }}</h1>
                <p class="mt-1 text-sm text-gray-500">مشخصات و جزئیات مجموعه</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('serviceCenter.edit', $serviceCenter->id) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-blue-700">
                    <i class="material-icons-round text-lg ml-1">edit</i>
                    ویرایش مشخصات
                </a>
            </div>
        </div>

        <!-- Service Center Details -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b">
                <h3 class="text-lg leading-6 font-medium text-gray-900">اطلاعات اصلی</h3>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">نام مجموعه</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $serviceCenter->name }}</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">نام مدیر</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $serviceCenter->manager_name }}</dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">شماره تماس</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $serviceCenter->phone }}</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">آدرس</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $serviceCenter->address }}</dd>
                    </div>
                    {{-- <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">وضعیت</dt>
                        <dd class="mt-1 sm:mt-0 sm:col-span-2">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $serviceCenter->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $serviceCenter->is_active ? 'فعال' : 'غیرفعال' }}
                            </span>
                        </dd>
                    </div> --}}
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">تاریخ ثبت‌نام</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ verta($serviceCenter->created_at)->format('Y/m/d H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b">
                <h3 class="text-lg leading-6 font-medium text-gray-900">آمار و اطلاعات تکمیلی</h3>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">تعداد کارشناسی‌های انجام شده</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"></dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">تعداد کارشناسان</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"></dd>
                    </div>
                    @if($serviceCenter->api_key)
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">API Key</dt>
                            <dd class="mt-1 text-sm font-mono bg-gray-100 p-2 rounded sm:mt-0 sm:col-span-2">
                                {{ $serviceCenter->api_key }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
@endsection