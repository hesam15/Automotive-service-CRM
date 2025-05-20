@extends('layouts.app')

@section('title', $serviceCenter->name)

@pushOnce('styles')
<link rel="stylesheet" href="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.css">
@endPushOnce

@section('content')
<div class="min-h-screen bg-gray-50 p-4 md:p-6">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            {{-- Header Section --}}
            <div class="relative h-64 md:h-96">
                <img src="{{ $serviceCenter->thumbnail_path ?? asset('images/default-service-center.jpg') }}" 
                     alt="{{ $serviceCenter->name }}"
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div class="absolute bottom-0 w-full p-6 text-white">
                    <h1 class="text-2xl md:text-3xl font-bold mb-2">{{ $serviceCenter->name }}</h1>
                    <div class="flex items-center gap-4">
                        <span class="flex items-center">
                            <span class="material-icons-round text-base ml-1">location_on</span>
                            {{ $serviceCenter->address }}
                        </span>
                        <span class="px-2 py-1 text-sm {{ $serviceCenter->is_open ? 'bg-green-500' : 'bg-red-500' }} rounded">
                            {{ $serviceCenter->is_open ? 'باز' : 'بسته' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Booking Form --}}
            <div class="p-6">
                <div class="max-w-3xl mx-auto">
                    <h2 class="text-xl font-semibold mb-6">رزرو نوبت</h2>
                    
                    <form action="{{ route('bookings.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="service_center_id" value="{{ $serviceCenter->id }}">
                        
                        {{-- Personal Info --}}
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">نام و نام خانوادگی</label>
                                <input type="text" name="name" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">شماره تماس</label>
                                <input type="tel" name="phone" required pattern="[0-9]{11}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        {{-- Car Info --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">مدل خودرو</label>
                            <input type="text" name="car_model" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        {{-- Date & Time Selection --}}
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">تاریخ مراجعه</label>
                                <input type="text" name="date" required
                                       data-jdp data-jdp-min-date="today"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                       readonly>
                            </div>
                            
                            <div data-time-slots-container class="hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-1">ساعت مراجعه</label>
                                <div data-time-slots-grid class="grid grid-cols-3 gap-2">
                                    {{-- Time slots will be inserted here dynamically --}}
                                </div>
                                <input type="hidden" name="time_slot" data-time-slot-input required>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div>
                            <button type="submit"
                                    class="w-full md:w-auto px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                ثبت رزرو
                            </button>
                        </div>
                    </form>
                </div>
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