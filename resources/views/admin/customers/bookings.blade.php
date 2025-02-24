@extends('layouts.app')

@section('title', 'رزروها')

@section('content')
<div class="max-w-7xl mx-auto py-4 md:py-6">
    <x-errors-success-label />

    <!-- Bookings List -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-100">
            <div class="flex items-center gap-3">
                <h4 class="text-xl font-bold text-gray-800">رزروهای {{ $customer->fullname }}</h4>
            </div>
            <!-- Search Form -->
            <div class="w-64">
                <form action="" method="GET" class="relative">
                    <input type="text" name="search" placeholder="جستجو..."
                           class="w-full pl-10 pr-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" class="absolute left-0 top-0 h-full px-3 text-gray-500 hover:text-blue-600">
                        <i class="material-icons-round text-lg">search</i>
                    </button>
                </form>
            </div>
        </div>

        @if($customer->bookings->isEmpty())
            <div class="px-6 py-12 flex flex-col items-center justify-center">
                <i class="material-icons-round text-gray-400 text-6xl mb-4">event_busy</i>
                <h2 class="text-xl font-bold text-gray-800 mb-4">هیچ رزروی ثبت نشده است.</h2>
                <a href="{{ route('bookings.create', $customer->id) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    <i class="material-icons-round text-xl ml-2">add</i>
                    افزودن رزرو جدید
                </a>
            </div>
        @else
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-right text-sm font-medium text-gray-500 w-1/6">تاریخ</th>
                                <th class="px-4 py-2 text-right text-sm font-medium text-gray-500 w-1/6">ساعت</th>
                                <th class="px-4 py-2 text-right text-sm font-medium text-gray-500 w-1/6">نوع خودرو</th>
                                <th class="px-4 py-2 text-right text-sm font-medium text-gray-500 w-1/6">وضعیت</th>
                                <th class="px-4 py-2 text-right text-sm font-medium text-gray-500 w-2/6">عملیات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($customer->bookings as $booking)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-900">{{ $booking->date }}</td>
                                <td class="px-4 py-3 text-gray-900">{{ $booking->time_slot }}</td>
                                <td class="px-4 py-3 text-gray-900">{{ $booking->car->name }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $booking->status === 'completed' ? 'تکمیل شده' : 'در انتظار' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-2">
                                        @if($booking->status === 'pending')
                                            <a href="{{ route('report.create', ['booking_id' => $booking->id]) }}"
                                            class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded hover:bg-blue-200 transition-colors duration-200">
                                                <i class="material-icons-round text-sm">assignment</i>
                                                <span class="text-xs mr-0.5">ثبت گزارش</span>
                                            </a>
                                        @endif
                                        <a href="{{ route('report.index', ['booking_id' => $booking->id, 'car_id' => $booking->car->id]) }}"
                                           class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 rounded hover:bg-green-200 transition-colors duration-200">
                                            <i class="material-icons-round text-sm">visibility</i>
                                            <span class="text-xs mr-0.5">مشاهده گزارش</span>
                                        </a>
                                        @if($booking->status === 'pending')
                                            <button onclick="openModal('bookingEditModal-{{$booking->id}}')" 
                                                    class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded hover:bg-blue-200 transition-colors duration-200">
                                                <i class="material-icons-round text-sm">edit</i>
                                                <span class="text-xs mr-0.5">ویرایش</span>
                                            </button>
                                        @else
                                            {{-- <button onclick="openModal('bookingEditModal-{{$booking->id}}')" 
                                                class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded hover:bg-blue-200 transition-colors duration-200">
                                                <i class="material-icons-round text-sm">edit</i>
                                                <span class="text-xs mr-0.5">ویرایش</span>
                                            </button> --}}
                                        @endif
                                        <button class="delete-btn inline-flex items-center px-2 py-1 bg-rose-100 text-rose-800 rounded hover:bg-rose-200 transition-colors duration-200" data-route="{{route("bookings.destroy", $booking->id)}}" data-type="booking">
                                            <i class="material-icons-round text-sm">cancel</i>
                                            <span class="text-xs mr-0.5">کنسل کردن</span>
                                        </button>  
                                    </div>
                                </td>
                            </tr>
                        
                            <!-- Edit Booking Modal -->
                            <x-edit-modal :id="'bookingEditModal-'.$booking->id" title="ویرایش رزرو" :action="route('bookings.update', $booking->id)" method='POST'>
                                @csrf
                                <div class="grid md:grid-cols-2 gap-4 md:gap-6">
                                    <!-- نام و نام خانوادگی -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            نام و نام خانوادگی
                                        </label>
                                        <div class="text-base text-gray-900">
                                            {{ $booking->customer->fullname }}
                                        </div>
                                    </div>
                            
                                    <!-- شماره تماس -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            شماره تلفن
                                        </label>
                                        <div class="text-base text-gray-900">
                                            {{ $booking->customer->phone }}
                                        </div>
                                    </div>
                            
                                    <!-- نوع خودرو -->
                                    <div>
                                        <label for="car" class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">انتخاب خودرو</label>
                                        <select name="car" id="car" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            @foreach ($booking->customer->cars as $car)
                                                @php
                                                    $license_plate = explode('-', $car->license_plate);
                                                @endphp
                                                <option value="{{ $car->id }}" {{ $booking->car_id == $car->id ? "selected" : "" }}>
                                                    {{ $car->name }} - ایران {{ $license_plate[3] }} | {{ $license_plate[2] }} {{ $license_plate[1] }} {{ $license_plate[0] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('car')" class="mt-2" />
                                    </div>
                            
                                    <!-- تاریخ -->
                                    <div>
                                        <label for="datepicker" class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">تاریخ مراجعه</label>
                                        <input type="text" id="datepicker" name="date" value="{{ $booking->date }}"
                                            class="w-full px-3 md:px-4 py-2.5 md:py-2 text-sm border border-gray-300 rounded-lg md:rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer" readonly>
                                        <x-input-error :messages="$errors->get('date')" class="mt-2" /> 
                                    </div>
                                </div>
                            
                                <!-- وضعیت -->
                                <div class="mt-4">
                                    <label for="booking_status" class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">وضعیت</label>
                                    <select id="booking_status" name="status" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>در انتظار</option>
                                        <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>تکمیل شده</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                </div>
                            
                                <!-- ساعت مراجعه -->
                                <div id="time-slots-container-{{$booking->id}}" class="mt-4">
                                    <label for="time_slot_{{$booking->id}}" class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">ساعت مراجعه</label>
                                    <div class="grid grid-cols-6 gap-2" id="time-slots-grid-{{$booking->id}}">
                                        <!-- Time slot buttons will be dynamically added here -->
                                    </div>
                                    <input type="hidden" name="time_slot" id="time_slot_{{$booking->id}}" value="{{ $booking->time_slot }}" required>
                                    <x-input-error :messages="$errors->get('time_slot')" class="mt-2" />
                                </div>
                            </x-edit-modal>
                                                         
                        @endforeach
                    </tbody>  
                </div>
            </div>
        @endif
    </div>
</div>

<x-delete-modal type="booking" />
@endsection