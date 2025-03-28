@extends('layouts.app')

@section('title', 'فرم رزرواسیون')

@pushOnce('styles')
<link rel="stylesheet" href="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.css">
@endPushOnce

@section('content')
<div class="min-h-screen p-4 md:p-6">
    <div class="max-w-md md:max-w-7xl mx-auto">
        <x-errors-success-label />

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-4 py-3 md:px-6 md:py-4 border-b border-gray-200 bg-gray-100">
                <h5 class="text-base md:text-xl font-semibold text-gray-800">فرم رزرواسیون</h5>
            </div>

            <div class="p-4 md:p-6">
                <form action="{{ route('bookings.store', $customer->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid md:grid-cols-2 gap-4 md:gap-6">
                        {{-- Customer Info --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                نام و نام خانوادگی
                            </label>
                            <div class="text-base text-gray-900">
                                <input type="text" name="customer_id" id="name" value="{{ $customer->id }}" hidden>
                                <span>{{ $customer->fullname }}</span>
                            </div>
                        </div>
                
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                شماره تلفن
                            </label>
                            <div class="text-base text-gray-900">
                                {{ $customer->phone }}
                            </div>
                        </div>
                
                        {{-- Car Selection --}}
                        <div>
                            <label for="car" class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">انتخاب خودرو</label>
                            @if($customer->cars->count() > 0)
                                <select name="car_id" id="car" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">انتخاب کنید</option>
                                    @foreach ($customer->cars as $car)
                                    @php
                                        $license_plate = explode('-', $car->license_plate);
                                    @endphp
                                        <option value="{{ $car->id }}" {{old("car_id") == $car->id ? "selected" : ""}}>
                                            {{ $car->name }} - ایران {{ $license_plate[3] }} | {{ $license_plate[2] }} {{ $license_plate[1] }} {{ $license_plate[0] }}
                                        </option>                                     
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('car_id')" class="mt-2" />
                            @else
                                <div class="w-full flex items-center justify-between px-4 py-2.5 md:py-2 bg-red-600 text-white text-sm rounded-lg">
                                    <span>هنوز خودرویی برای این مشتری ثبت نشده است.</span>
                                    <a href="{{ route('cars.create', $customer->id) }}" class="inline-flex items-center px-3 py-1.5 bg-white text-red-600 rounded hover:bg-red-50 transition-colors duration-200">
                                        <span class="material-icons-round text-base ml-1">add</span>
                                        افزودن خودرو
                                    </a>
                                </div>                                                        
                            @endif
                        </div>                        
                        
                        {{-- Date Picker --}}
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
                                readonly>
                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                        </div>
                    </div>
                    
                    {{-- Time Slots Container --}}
                    <div data-time-slots-container class="mt-6 hidden">
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
                        <div data-time-slots-message class="mb-4 hidden">
                            <div class="text-sm text-gray-500 bg-gray-50 rounded-lg p-3 border border-gray-200">
                                در حال بارگذاری ساعات مراجعه...
                            </div>
                        </div>

                        {{-- Time Slots Grid --}}
                        <div data-time-slots-grid class="grid gap-3">
                            {{-- Time slots will be dynamically inserted here --}}
                        </div>

                        {{-- Hidden Input for Selected Time --}}
                        <input type="hidden" name="time_slot" data-time-slot-input>
                        <x-input-error :messages="$errors->get('time_slot')" class="mt-2" />
                    </div>
                
                    {{-- Submit Button --}}
                    <div class="pt-4">
                        <button type="submit"
                            class="w-full md:w-auto md:px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                            ثبت اطلاعات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@pushOnce('scripts')
    <script src="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.js"></script>
    <script>
        window.requiredManagers = window.requiredManagers || [];
        window.requiredManagers.push('datePickerManager');
    </script>
@endPushOnce
@endsection