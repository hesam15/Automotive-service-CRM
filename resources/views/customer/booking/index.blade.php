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
                {{-- ... existing header code ... --}}
            </div>

            {{-- Service Center Info --}}
            <div class="p-6 border-b border-gray-100">
                <div class="max-w-3xl mx-auto space-y-4">
                    <div class="flex items-center gap-4 text-sm text-gray-600">
                        <span class="flex items-center">
                            <span class="material-icons-round text-base ml-1">schedule</span>
                            ساعات کاری: {{ $serviceCenter->working_hours }}
                        </span>
                        @if($serviceCenter->fridays_off)
                            <span class="flex items-center text-red-600">
                                <span class="material-icons-round text-base ml-1">warning</span>
                                تعطیل در روزهای جمعه
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Booking Section --}}
            <div class="p-6">
                <div class="max-w-3xl mx-auto">
                    {{-- Preview Section (Visible to All) --}}
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold mb-6">مشاهده زمان‌های خالی</h2>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">تاریخ مورد نظر</label>
                                <input type="text" 
                                       data-jdp data-jdp-min-date="today"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                       readonly>
                            </div>
                            
                            <div data-time-slots-container class="hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-1">ساعت‌های خالی</label>
                                <div data-time-slots-grid class="grid grid-cols-3 gap-2">
                                    {{-- Time slots will be inserted here dynamically --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Login Required Message --}}
                    @guest
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center gap-3">
                                <span class="material-icons-round text-blue-600">info</span>
                                <p class="text-blue-800">
                                    برای رزرو نوبت، لطفا ابتدا 
                                    <a href="{{ route('login') }}" class="font-medium underline hover:text-blue-600">
                                        وارد حساب کاربری
                                    </a> 
                                    خود شوید یا 
                                    <a href="{{ route('register') }}" class="font-medium underline hover:text-blue-600">
                                        ثبت‌نام
                                    </a> 
                                    کنید.
                                </p>
                            </div>
                        </div>
                    @endguest

                    {{-- Booking Form (Only for Logged in Users) --}}
                    @auth
                        <div class="border-t border-gray-200 pt-8">
                            <h2 class="text-xl font-semibold mb-6">رزرو نوبت</h2>
                            <form action="{{ route('bookings.store') }}" method="POST" class="space-y-6">
                                @csrf
                                <input type="hidden" name="service_center_id" value="{{ $serviceCenter->id }}">
                                
                                {{-- Personal Info --}}
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">نام و نام خانوادگی</label>
                                        <input type="text" name="name" required value="{{ auth()->user()->name }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">شماره تماس</label>
                                        <input type="tel" name="phone" required pattern="[0-9]{11}" value="{{ auth()->user()->phone }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    </div>
                                </div>

                                {{-- Car Info --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">مدل خودرو</label>
                                    <input type="text" name="car_model" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </div>

                                {{-- Hidden Date & Time Inputs --}}
                                <input type="hidden" name="date" required>
                                <input type="hidden" name="time_slot" required data-time-slot-input>

                                {{-- Submit Button --}}
                                <div>
                                    <button type="submit"
                                            class="w-full md:w-auto px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                        ثبت رزرو
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endauth
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