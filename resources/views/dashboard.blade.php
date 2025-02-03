@extends('layouts.app')

@section('title', 'داشبورد مدیریت')

@section('content')
<div class="max-w-7xl mx-auto py-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Quick Stats Cards -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">تعداد مشتریان</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-2">{{ $customersCount }}</h3>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="material-icons-round text-blue-600 text-2xl">people</i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">نوبت‌های امروز</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-2">{{ $todayBookings }}</h3>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="material-icons-round text-green-600 text-2xl">event</i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">گزارش‌های ثبت شده</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-2">{{ $reportsCount }}</h3>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <i class="material-icons-round text-purple-600 text-2xl">description</i>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="md:col-span-2 bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h4 class="text-lg font-semibold text-gray-800">نوبت‌های اخیر</h4>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-right text-sm font-medium text-gray-500">نام مشتری</th>
                                <th class="px-4 py-2 text-right text-sm font-medium text-gray-500">تاریخ</th>
                                <th class="px-4 py-2 text-right text-sm font-medium text-gray-500">ساعت</th>
                                <th class="px-4 py-2 text-right text-sm font-medium text-gray-500">وضعیت</th>
                                <th class="px-4 py-2 text-right text-sm font-medium text-gray-500">عملیات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($recentBookings as $booking)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-900"><a href="{{route("customers.profile" , $booking->customer->fullname)}}">{{ $booking->customer->fullname }}</a></td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $booking->date }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $booking->time_slot }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $booking->status === 'completed' ? 'تکمیل شده' : 'در انتظار' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="" 
                                       class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                        <span class="material-icons-round text-sm ml-1">assignment</span>
                                        کارشناسی
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>                    
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h4 class="text-lg font-semibold text-gray-800">دسترسی سریع</h4>
            </div>
            <div class="p-6 space-y-4">
                <a href="{{ route('customers.create') }}" class="flex items-center px-4 py-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                    <i class="material-icons-round text-blue-600 text-xl ml-3">person_add</i>
                    <span class="text-gray-700">ثبت مشتری جدید</span>
                </a>
                
                <a href="{{ route('users.create') }}" class="flex items-center px-4 py-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                    <i class="material-icons-round text-orange-600 text-xl ml-3">manage_accounts</i>
                    <span class="text-gray-700">ثبت کاربر جدید</span>
                </a>
        
                <a href="{{ route('roles.create') }}" class="flex items-center px-4 py-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                    <i class="material-icons-round text-purple-600 text-xl ml-3">admin_panel_settings</i>
                    <span class="text-gray-700">ثبت نقش جدید</span>
                </a>
        
                <a href="{{ route('create.option') }}" class="flex items-center px-4 py-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                    <i class="material-icons-round text-green-600 text-xl ml-3">add_circle</i>
                    <span class="text-gray-700">ثبت خدمت جدید</span>
                </a>
            </div>
        </div>               
    </div>
</div>
@endsection