@extends('layouts.app')

@section('title', 'مشاهده گزارش')

@push('scripts')
    @vite(['resources/js/managers/AccordionManager.js'])
@endpush

@section('content')
<div class="max-w-6xl mx-auto py-4 md:py-6 space-y-6">
    <!-- Customer & Car Info Box -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-100">
            <h5 class="text-xl font-semibold text-gray-800">اطلاعات مشتری و خودرو</h5>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer Info -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h6 class="text-sm font-semibold text-gray-600 mb-4">اطلاعات مشتری</h6>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <span class="text-gray-600 ml-2">نام:</span>
                            <span class="text-gray-900 font-medium">{{ $booking->customer->fullname }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-gray-600 ml-2">شماره تماس:</span>
                            <a href="tel:{{ $booking->customer->phone }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                {{ $booking->customer->phone }}
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Car Info -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h6 class="text-sm font-semibold text-gray-600 mb-4">اطلاعات خودرو</h6>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <span class="text-gray-600 ml-2">مدل خودرو:</span>
                            <span class="text-gray-900 font-medium">{{ $booking->car->name }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-gray-600 ml-2">تاریخ کارشناسی:</span>
                            <span class="text-gray-900 font-medium">{{ $report->date }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Details -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-100 flex justify-between items-center">
            <h5 class="text-xl font-semibold text-gray-800">جزئیات گزارش</h5>
            <a href="{{ route('report.print', $report->id) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                چاپ گزارش
            </a>
        </div>

        <div class="p-6">
            <div class="space-y-8">
                @php
                    $reportOptions = json_decode($report->reports, true) ?? [];
                    $reportDescriptions = json_decode($report->description, true) ?? [];
                @endphp
            
                @forelse($reportOptions as $serviceName => $serviceDetails)
                    <div class="border border-gray-200 rounded-lg overflow-hidden mb-4 last:mb-0">
                        {{-- Header/Toggle button --}}
                        <button type="button" 
                                onclick="toggleService('{{ $serviceKey = str_replace([' ', '-'], '_', $serviceName) }}')"
                                class="w-full px-6 py-4 bg-gray-50 flex items-center justify-between hover:bg-gray-100 transition-colors duration-200">
                            <h3 class="text-lg font-semibold text-gray-800">{{ str_replace('_', ' ', $serviceName) }}</h3>
                            <svg xmlns="http://www.w3.org/2000/svg" 
                                class="h-5 w-5 transform transition-transform duration-200 ease-in-out" 
                                id="icon_{{ $serviceKey }}"
                                fill="none" 
                                viewBox="0 0 24 24" 
                                stroke="currentColor">
                                <path stroke-linecap="round" 
                                    stroke-linejoin="round" 
                                    stroke-width="2" 
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        {{-- Content --}}
                        <div id="content_{{ $serviceKey }}" 
                            class="hidden transition-all duration-300 ease-in-out">
                            <div class="p-6 space-y-4">
                                {{-- Service Details Grid --}}
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    @foreach($serviceDetails as $key => $value)
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <div class="text-sm font-medium text-gray-600 mb-2">{{ $key }}</div>
                                            <div class="text-gray-900">{{ $value }}</div>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Service Description --}}
                                @if(isset($reportDescriptions[$serviceKey]))
                                    <div class="bg-blue-50 p-4 rounded-lg">
                                        <div class="text-sm font-medium text-blue-800 mb-2">توضیحات تکمیلی</div>
                                        <div class="text-blue-900">
                                            {{ $reportDescriptions[$serviceKey] }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <span class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-800 rounded-lg">
                            <i class="material-icons-round text-lg ml-2">info</i>
                            هیچ گزارشی ثبت نشده است
                        </span>
                    </div>
                @endforelse
            
            @if(isset($reportDescriptions['description']))
                <div class="bg-gray-50 p-6 rounded-lg mt-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">توضیحات کلی</h3>
                    <div class="text-gray-700">
                        {{ $reportDescriptions['description'] }}
                    </div>
                </div>
            @endif
            </div>
        </div>
    </div>
</div>
@endsection