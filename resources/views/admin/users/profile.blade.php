@extends('layouts.app')

@section('title', 'پروفایل کاربری')

@section('content')
<div class="max-w-7xl mx-auto py-4 md:py-6">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-100">
            <h5 class="text-xl font-semibold text-gray-800">پروفایل کاربری</h5>
        </div>

        <div class="p-6">
            <div class="flex flex-col items-center mb-8">
                <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                    <span class="material-icons-round text-blue-600 text-5xl">account_circle</span>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl mx-auto">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center mb-2">
                        <span class="material-icons-round text-gray-500 ml-2">phone</span>
                        <span class="text-sm text-gray-600">شماره موبایل</span>
                    </div>
                    <p class="text-gray-800">{{ $user->phone }}</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center mb-2">
                        <span class="material-icons-round text-gray-500 ml-2">calendar_today</span>
                        <span class="text-sm text-gray-600">تاریخ عضویت</span>
                    </div>
                    <p class="text-gray-800">{{ verta($user->created_at)->format('Y/m/d') }}</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center mb-2">
                        <span class="material-icons-round text-gray-500 ml-2">verified_user</span>
                        <span class="text-sm text-gray-600">وضعیت حساب</span>
                    </div>
                    <p class="text-gray-800">فعال</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center mb-2">
                        <span class="material-icons-round text-gray-500 ml-2">badge</span>
                        <span class="text-sm text-gray-600">نقش کاربری</span>
                    </div>
                    <p class="text-gray-800">{{ $user->role->persian_name }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection