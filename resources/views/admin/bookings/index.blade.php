@extends('layouts.app')

@section('title', 'رزروها')

@pushOnce('styles')
    <link rel="stylesheet" href="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.css">
@endPushOnce

@section('content')
<div class="max-w-7xl mx-auto py-4 md:py-6">
    <x-errors-success-label />

    <!-- Bookings List -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-100">
            <div class="flex items-center gap-3">
                <h4 class="text-xl font-bold text-gray-800">رزروها</h4>
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

        @if($bookings->isEmpty())
            <div class="px-6 py-12 flex flex-col items-center justify-center">
                <i class="material-icons-round text-gray-400 text-6xl mb-4">event_busy</i>
                <h2 class="text-xl font-bold text-gray-800 mb-4">هیچ رزروی ثبت نشده است.</h2>
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
                            @foreach($bookings as $booking)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-900">{{ $booking->date }}</td>
                                <td class="px-4 py-3 text-gray-900">{{ $booking->time_slot }}</td>
                                <td class="px-4 py-3 text-gray-900">{{ $booking->car->name }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                        ($booking->status === 'paid' ? 'bg-blue-100 text-blue-800' : 
                                        ($booking->status === 'undergraduate' ? 'bg-purple-100 text-purple-800' : 
                                        'bg-yellow-100 text-yellow-800')) }}">
                                        {{ $booking->status === 'completed' ? 'تکمیل شده' : 
                                        ($booking->status === 'paid' ? 'پرداخت شده' :
                                        ($booking->status === 'undergraduate' ? 'درحال کارشناسی' : 
                                        'در انتظار')) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-2">
                                        @if(in_array($booking->status, ['pending', 'paid', 'undergraduate']))
                                            <a href="{{ route('report.create', ['booking' => $booking->id]) }}"
                                            class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded hover:bg-blue-200 transition-colors duration-200">
                                                <i class="material-icons-round text-sm">assignment</i>
                                                <span class="text-xs mr-0.5">ثبت گزارش</span>
                                            </a>
                                        @endif

                                        @if($booking->status === 'completed')
                                            <a href="{{ route('report.show', ['booking' => $booking->id, 'report' => $booking->report->id]) }}"
                                            class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 rounded hover:bg-green-200 transition-colors duration-200">
                                                <i class="material-icons-round text-sm">visibility</i>
                                                <span class="text-xs mr-0.5">مشاهده گزارش</span>
                                            </a>
                                        @endif

                                        @if(in_array($booking->status, ['pending', 'paid', 'undergraduate']))
                                            <button data-modal-target="bookingEditModal-{{ $booking->id }}"
                                                    class="modal-trigger inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded hover:bg-blue-200 transition-colors duration-200">
                                                <i class="material-icons-round text-sm">edit</i>
                                                <span class="text-xs mr-0.5">ویرایش</span>
                                            </button>
                                            <button class="delete-btn inline-flex items-center px-2 py-1 bg-rose-100 text-rose-800 rounded hover:bg-rose-200 transition-colors duration-200" data-route="{{route("bookings.destroy", $booking->id)}}" data-type="booking">
                                                <i class="material-icons-round text-sm">cancel</i>
                                                <span class="text-xs mr-0.5">کنسل کردن</span>
                                            </button>
                                        @endif 
                                    </div>
                                </td>
                            </tr>
                        
                            <!-- Edit Booking Modal -->
                            <x-edit-modal 
                                :id="'bookingEditModal-'.$booking->id"
                                title="ویرایش رزرو"
                                :action="route('bookings.update', $booking->id)"
                                method="POST"
                                maxWidth="4xl">
                                
                                <div class="space-y-4">
                                    <div class="bg-gray-50 p-3 rounded-lg mb-4">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <span class="block text-sm font-medium text-gray-500">نام و نام خانوادگی</span>
                                                <span class="block mt-1 text-gray-900">{{ $booking->customer->name }}</span>
                                            </div>
                                            <div>
                                                <span class="block text-sm font-medium text-gray-500">شماره تماس</span>
                                                <span class="block mt-1 text-gray-900">{{ $booking->customer->phone }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="customer_id" value="{{ $booking->customer_id }}">

                                    <div>
                                        <label for="car_{{ $booking->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                            خودرو
                                        </label>
                                        <select name="car_id"
                                                id="car_{{ $booking->id }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                required>
                                            @foreach($booking->customer->cars as $car)
                                                @php
                                                    $license_plate = explode('-', $car->license_plate);
                                                @endphp
                                                <option value="{{ $car->id }}" {{ $booking->car_id == $car->id ? 'selected' : '' }}>
                                                    {{ $car->name }} - ایران {{ $license_plate[3] }} | {{ $license_plate[2] }} {{ $license_plate[1] }} {{ $license_plate[0] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label for="status_{{ $booking->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                            وضعیت
                                        </label>
                                        <select name="status" 
                                                id="status_{{ $booking->id }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                required>
                                            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>در انتظار پرداخت</option>
                                            <option value="paid" {{ $booking->status == 'paid' ? 'selected' : '' }}>پرداخت شده</option>
                                            <option value="undergraduate" {{ $booking->status == 'undergraduate' ? 'selected' : '' }}>در حال کارشناسی</option>
                                            <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>تکمیل شده</option>
                                            <option value="expired" {{ $booking->status == 'expired' ? 'selected' : '' }}>منقضی شده</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                    </div>

                                    <div class="form-group">
                                        <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">تاریخ مراجعه</label>
                                        <input 
                                            type="text" 
                                            id="appointment_date"
                                            name="date"
                                            placeholder="تاریخ مدنظر خود را انتخاب کنید"
                                            data-jdp
                                            data-jdp-min-date="today"
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer"
                                            value={{ $booking->date }}
                                            readonly>
                                        <x-input-error :messages="$errors->get('date')" class="mt-2" />
                                    </div>


                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            ساعت مراجعه
                                        </label>
                                        
                                        <div data-time-slots-container class="mt-2">
                                            {{-- Time Slots Header --}}
                                            <div class="flex items-center justify-between mb-4">
                                                <h3 class="text-lg font-semibold text-gray-900">
                                                    <span class="flex items-center gap-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        ساعت مراجعه
                                                    </span>
                                                </h3>
                                            </div>

                                            {{-- Time Slots Message (Loading/Error) --}}
                                            <div data-time-slots-message-{{ $booking->id }} class="mb-4 hidden">
                                                <div class="text-sm text-gray-500 bg-gray-50 rounded-lg p-3 border border-gray-200">
                                                    در حال بارگذاری ساعات مراجعه...
                                                </div>
                                            </div>

                                            {{-- Time Slots Grid --}}
                                            <div id="time-slots-grid-{{ $booking->id }}" data-time-slots-grid class="">
                                                {{-- Time slots will be dynamically inserted here --}}
                                            </div>

                                            {{-- Hidden Input for Selected Time --}}
                                            <input type="hidden" 
                                                name="time_slot" 
                                                id="time_slot_{{ $booking->id }}" 
                                                value="{{ $booking->time_slot }}"
                                                data-time-slot-input>
                                            <x-input-error :messages="$errors->get('time_slot')" class="mt-2" />
                                        </div>
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

<x-delete-modal type="booking" />

@pushOnce('scripts')
    <script src="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.js"></script>
    <script>
        window.requiredManagers = window.requiredManagers || [];
        window.requiredManagers.push('datePickerManager');
    </script>
@endPushOnce
@endsection