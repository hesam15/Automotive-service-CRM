@extends('layouts.app')

@section('title', 'جزئیات مشتری')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modalManager = window.app.initializeModalManager();
    });
</script>
@endpush

@section('content') 
<div class="max-w-7xl mx-auto py-6 px-4">  
    <x-errors-success-label />

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Customer Info Card -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">اطلاعات مشتری</h2>
                <div class="flex gap-2">
                    <button type="button"
                        data-modal-target="customerEditModal-{{ $customer->id }}"
                        class="modal-trigger inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded hover:bg-blue-200 transition-colors duration-200">
                        <i class="material-icons-round text-sm">edit</i>
                        <span class="text-xs mr-0.5">ویرایش</span>
                    </button>
                    <button class="delete-btn inline-flex items-center px-2 py-1 bg-rose-100 text-rose-800 rounded hover:bg-rose-200 transition-colors duration-200" data-route="{{route("customers.destroy", $customer->id)}}" data-type="customer">
                        <i class="material-icons-round text-sm">delete</i>
                        <span class="text-xs mr-0.5">حذف</span>
                    </button>
                </div>
            </div>
            
            <!-- Customer Info Content -->
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">نام و نام خانوادگی:</span>
                    <span class="font-medium">{{ $customer->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">شماره تماس:</span>
                    <span class="font-medium">{{ $customer->phone }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">تاریخ ثبت نام:</span>
                    <span class="font-medium">{{ $registrationTime }}</span>
                </div>
            </div>

            <!-- Edit Customer Modal -->
            <x-edit-modal :id="'customerEditModal-'.$customer->id" title="ویرایش اطلاعات مشتری" :action="route('customers.update', $customer->id)" method='POST'>
                @csrf
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">نام و نام خانوادگی</label>
                        <input type="text" id="name" name="name" value="{{ $customer->name }}"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="col-span-6">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">شماره تماس</label>
                        <input type="tel" id="phone" name="phone" value="{{ $customer->phone }}"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </x-edit-modal>
        </div>        

        <!-- Cars List -->
        <div class="md:col-span-2 bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-100 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">خودروها</h2>
                <a href="{{ route('cars.create', $customer->id) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition duration-200">
                    <span class="material-icons-round text-base ml-1">add</span>
                    افزودن خودرو
                </a>
            </div>
            @if($customer->cars->isEmpty())
                <div class="px-6 py-8 flex flex-col items-center justify-center">
                    <i class="material-icons-round text-gray-400 text-6xl mb-4">directions_car</i>
                    <h2 class="text-xl font-bold text-gray-800 mb-4">هیچ خودرویی ثبت نشده است.</h2>
                    <a href="{{ route('cars.create', $customer->id) }}" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <i class="material-icons-round text-xl ml-2">add</i>
                        افزودن خودرو جدید
                    </a>
                </div>
            @else
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-right text-sm font-medium text-gray-500 w-1/4">نوع خودرو</th>
                                    <th class="px-4 py-2 text-right text-sm font-medium text-gray-500 w-1/4">پلاک</th>
                                    <th class="px-4 py-2 text-right text-sm font-medium text-gray-500 w-1/4">رنگ</th>
                                    <th class="px-4 py-2 text-right text-sm font-medium text-gray-500 w-1/4">عملیات</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($customer->cars as $car)
                                    <tr class="{{ session('car') && session('car') == $car->id ? 'bg-blue-50 hover:bg-blue-100' : 'hover:bg-gray-50' }}">
                                        <td class="px-4 py-3 text-gray-900">{{ $car->name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            <div class="border-2 border-black rounded-lg p-2 inline-block bg-white">
                                                <div class="flex items-center gap-1">
                                                    <div class="text-center">
                                                        <div class="text-[10px] border-b border-black">ایران</div>
                                                        <div>{{$car->license_plate[0]}}</div>
                                                    </div>
                                                    <div class="h-8 w-px bg-black"></div>
                                                    <div>{{$car->license_plate[2]}}</div>
                                                    <div class="h-8 w-px bg-black"></div>
                                                    <div class="flex items-middle">{{$car->license_plate[1]}}</div>
                                                    <div class="h-8 w-px bg-black"></div>
                                                    <div>{{$car->license_plate[3]}}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-gray-900">{{ $car->color }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex gap-2">
                                                <button type="button"
                                                    class="modal-trigger inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded hover:bg-blue-200 transition-colors duration-200"
                                                    data-modal-target="carEditModal-{{$car->id}}">
                                                    <i class="material-icons-round text-sm">edit</i>
                                                    <span class="text-xs mr-0.5">ویرایش</span>
                                                </button>
                                                <button class="delete-btn inline-flex items-center px-2 py-1 bg-rose-100 text-rose-800 rounded hover:bg-rose-200 transition-colors duration-200" data-route="{{route("cars.destroy", $car->id)}}" data-type="car">
                                                    <i class="material-icons-round text-sm">delete</i>
                                                    <span class="text-xs mr-0.5">حذف</span>
                                                </button>  
                                            </div>
                                        </td>
                                    </tr>

                                    <x-edit-modal :id="'carEditModal-'.$car->id" title="ویرایش خودرو" :action="route('cars.update', $car->id)" method='POST'>
                                        @csrf
                                        <div class="grid grid-cols-12 gap-4">
                                            <div class="col-span-6">
                                                <label for="car_name-{{$car->id}}" class="block text-sm font-medium text-gray-700 mb-1">نوع خودرو</label>
                                                <input type="text" 
                                                    id="car_name-{{$car->id}}" 
                                                    name="car_name" 
                                                    value="{{ $car->name }}"
                                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            </div>
                                            <div class="col-span-6">
                                                <label for="car_color-{{$car->id}}" class="block text-sm font-medium text-gray-700 mb-1">رنگ</label>
                                                <input type="text" 
                                                    id="car_color-{{$car->id}}" 
                                                    name="car_color" 
                                                    value="{{ $car->color }}"
                                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            </div>

                                            <div class="col-span-12">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">پلاک خودرو</label>
                                                <div class="grid grid-cols-4 gap-2">
                                                    <div>
                                                        <input type="number" 
                                                            name="plate_iran" 
                                                            value="{{ $car->license_plate[3] }}"
                                                            min="0"
                                                            maxlength="2"
                                                            class="w-full px-3 py-2 text-sm text-center border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                            placeholder="ایران">
                                                        <x-input-error :messages="$errors->get('plate_iran')" class="mt-2" />
                                                    </div>
                                                    <div>
                                                        <input type="number" 
                                                            name="plate_three" 
                                                            value="{{ $car->license_plate[2] }}"
                                                            min="0"
                                                            maxlength="3"
                                                            class="w-full px-3 py-2 text-sm text-center border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                            placeholder="سه رقم">
                                                        <x-input-error :messages="$errors->get('plate_three')" class="mt-2" />
                                                    </div>
                                                    <div>
                                                        <input type="text" 
                                                            name="plate_letter" 
                                                            value="{{ $car->license_plate[1] }}"
                                                            maxlength="1"
                                                            class="w-full px-3 py-2 text-sm text-center border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                            placeholder="حرف">
                                                        <x-input-error :messages="$errors->get('plate_letter')" class="mt-2" />
                                                    </div>
                                                    <div>
                                                        <input type="number" 
                                                            name="plate_two" 
                                                            value="{{ $car->license_plate[0] }}"
                                                            min="0"
                                                            maxlength="2"
                                                            class="w-full px-3 py-2 text-sm text-center border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                            placeholder="دو رقم">
                                                        <x-input-error :messages="$errors->get('plate_two')" class="mt-2" />
                                                    </div>
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

        <!-- Bookings List -->
        <div class="md:col-span-3 bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-100 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">رزروی ها</h2>
                <a href="{{ route('bookings.create', $customer->id) }}"
                    class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition duration-200">
                    <span class="material-icons-round text-base ml-1">calendar_today</span>
                    رزرو وقت
                </a>                 
            </div>
            @if($customer->bookings->isEmpty())
                <div class="px-6 py-8 flex flex-col items-center justify-center">
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
                                            <a href="{{ route('report.create', ['booking' => $booking->id]) }}"
                                            class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded hover:bg-blue-200 transition-colors duration-200">
                                                <i class="material-icons-round text-sm">assignment</i>
                                                <span class="text-xs mr-0.5">ثبت گزارش</span>
                                            </a>
                                        @elseif($booking->status === 'completed')
                                            <a href="{{ route('report.show', ['booking' => $booking->id, 'report' => $booking->report->id]) }}"
                                            class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 rounded hover:bg-green-200 transition-colors duration-200">
                                                <i class="material-icons-round text-sm">visibility</i>
                                                <span class="text-xs mr-0.5">مشاهده گزارش</span>
                                            </a>
                                        @endif

                                        @if($booking->status === 'pending')
                                            <button type="button"
                                                class="modal-trigger inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded hover:bg-blue-200 transition-colors duration-200"
                                                data-modal-target="bookingEditModal-{{$booking->id}}">
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
                                            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>در انتظار</option>
                                            <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>تکمیل شده</option>
                                            <option value="completed" {{ $booking->status == 'expired' ? 'selected' : '' }}>منقضی شده</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="date_{{ $booking->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                            تاریخ مراجعه
                                        </label>
                                        <input type="text" 
                                            name="date" 
                                            id="date_{{ $booking->id }}"
                                            value="{{ $booking->date }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            readonly>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            ساعت مراجعه
                                        </label>
                                        <input type="hidden" 
                                            name="time_slot" 
                                            id="time_slot_{{ $booking->id }}" 
                                            value="{{ $booking->time_slot }}">
                                        
                                        <div id="time-slots-container-{{ $booking->id }}">
                                            <div id="time-slots-grid-{{ $booking->id }}" class="grid grid-cols-4 gap-2">
                                                {{-- Time slots will be loaded here via JavaScript --}}
                                            </div>
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
</div>

<x-delete-modal />
@endsection