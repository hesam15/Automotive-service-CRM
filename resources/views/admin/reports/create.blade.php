@extends('layouts.app')

@section('title', 'ثبت گزارش')

@section('content')
<div class="max-w-6xl mx-auto py-4 md:py-6 space-y-6">
    <x-errors-success-label />

    <!-- Customer & Car Info Accordion -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <button type="button" 
                id="infoAccordion"
                class="w-full px-6 py-4 bg-gray-100 flex items-center justify-between hover:bg-gray-50 transition-colors duration-200">
            <h5 class="text-xl font-semibold text-gray-800">اطلاعات مشتری و خودرو</h5>
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="h-5 w-5 transform transition-transform duration-200" 
                 id="accordionIcon"
                 fill="none" 
                 viewBox="0 0 24 24" 
                 stroke="currentColor">
                <path stroke-linecap="round" 
                      stroke-linejoin="round" 
                      stroke-width="2" 
                      d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div id="infoContent" 
             class="hidden border-t border-gray-200">
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
                                <a href="tel:{{ $booking->customer->phone }}" 
                                   class="text-blue-600 hover:text-blue-800 font-medium">
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
                                <span class="text-gray-900 font-medium">{{ $booking->date }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-gray-600 ml-2">ساعت:</span>
                                <span class="text-gray-900 font-medium">{{ $booking->time_slot }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Form -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-100">
            <h5 class="text-xl font-semibold text-gray-800">ثبت گزارش جدید</h5>
        </div>

        <div class="p-6">
            <form action="{{ route('report.store', ['booking' => $booking->id]) }}" method="POST">
                @csrf
                
                <div class="space-y-8">
                    @foreach ($options as $mainService)
                        <div class="pb-3 border-b border-gray-200 last:border-0">
                            <label class="block text-lg font-semibold text-gray-800 mb-4">
                                {{ $mainService->name }}
                            </label>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                @foreach ($mainService->values as $key => $subService)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-2">{{ $key }}</label>
                                        <select name="options[{{ $mainService->name }}][{{ $key }}]" 
                                                class="w-full px-4 py-2.5 text-sm text-gray-900 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200">
                                            <option value="">انتخاب کنید</option>
                                            @foreach($subService as $value)
                                                <option value="{{ $value }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" 
                                    class="explanation-toggle inline-flex items-center text-sm text-blue-600 hover:text-blue-800"
                                    data-service="{{ str_replace(' ', '_', $mainService->name) }}">
                                <svg xmlns="http://www.w3.org/2000/svg" 
                                     class="h-4 w-4 ml-1.5 transition-transform duration-200" 
                                     fill="none" 
                                     viewBox="0 0 24 24" 
                                     stroke="currentColor">
                                    <path stroke-linecap="round" 
                                          stroke-linejoin="round" 
                                          stroke-width="2" 
                                          d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                نیاز به توضیح دارید؟
                            </button>
                            
                            <div id="explanation_{{ str_replace(' ', '_', $mainService->name) }}"
                                 class="mt-1"></div>
                        </div>
                    @endforeach

                    <div>
                        <label for="description" 
                               class="block text-lg font-semibold text-gray-800 mb-4">توضیحات کلی</label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="5" 
                                  placeholder="توضیحات تکمیلی خود را اینجا وارد کنید..."
                                  class="w-full px-4 py-3 text-sm text-gray-900 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200"></textarea>
                    </div>
                </div>

                <input type="hidden" name="car_id" value="{{ $booking->car->id }}">

                <div class="flex justify-end mt-8">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        ثبت گزارش
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection