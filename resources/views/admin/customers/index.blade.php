@extends('layouts.app')

@section('title', 'داشبورد')

@section('content')
<div class="max-w-7xl mx-auto py-4 md:py-6">
    <x-errors-success-label />

    <!-- Customers List -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-100">
            <div class="flex items-center gap-3">
                <h4 class="text-xl font-bold text-gray-800">لیست مشتریان</h4>
                <button onclick="openModal('createCustomerModal')" 
                    class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition duration-200">
                    <span class="material-icons-round text-base ml-1">add</span>
                    افزودن مشتری
                </button>
            </div>
            
            <!-- Search Form -->
            <div class="w-64">
                <form action="{{ route('customers.index') }}" method="GET" class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="جستجو..."
                        class="w-full pl-10 pr-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" class="absolute left-0 top-0 h-full px-3 text-gray-500 hover:text-blue-600">
                        <i class="material-icons-round text-lg">search</i>
                    </button>
                </form>
            </div>
        </div>

        @if($customers->isEmpty())
            <div class="px-6 py-12 flex flex-col items-center justify-center">
                <i class="material-icons-round text-gray-400 text-6xl mb-4">person_off</i>
                <h2 class="text-xl font-bold text-gray-800 mb-4">هیچ کاربری ثبت نشده است.</h2>
                <button onclick="openModal('createCustomerModal')" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    <i class="material-icons-round text-xl ml-2">add</i>
                    افزودن کاربر جدید
                </button>
            </div>
        @else
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-right text-sm font-medium text-gray-500 w-1/4">نام مشتری</th>
                                <th class="px-4 py-2 text-right text-sm font-medium text-gray-500 w-1/4">شماره تلفن</th>
                                <th class="px-4 py-2 text-right text-sm font-medium text-gray-500 w-1/4">نوبت ها</th>
                                <th class="px-4 py-2 text-right text-sm font-medium text-gray-500 w-1/4">عملیات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($customers as $customer)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-base text-gray-900 w-1/5">{{ $customer->fullname }}</td>
                                    <td class="px-4 py-2 text-base text-gray-900 w-1/5">{{ $customer->phone }}</td>
                                    <td class="px-4 py-2 text-base text-gray-900 w-1/5">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('customers.bookings', $customer->id) }}" 
                                                class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded hover:bg-blue-200 transition-colors duration-200">
                                                <i class="material-icons-round text-sm">list_alt</i>
                                                <span class="text-xs mr-0.5">تمام نوبت ها</span>
                                            </a>
                                            <a href="{{ route('bookings.create', $customer->id) }}" 
                                                class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded hover:bg-blue-200 transition-colors duration-200">
                                                <i class="material-icons-round text-sm">event_available</i>
                                                <span class="text-xs mr-0.5">نوبت جدید</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 w-2/5">
                                        <div class="flex items-center gap-1">
                                            <a href="{{ route('customers.profile', ['id' => $customer->id]) }}" 
                                                class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 rounded hover:bg-green-200 transition-colors duration-200">
                                                <i class="material-icons-round text-sm">person</i>
                                                <span class="text-xs mr-0.5">پروفایل</span>
                                            </a>
                                            <button onclick="openModal('customerEditModal-{{$customer->id}}')"
                                                class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded hover:bg-blue-200 transition-colors duration-200">
                                                <i class="material-icons-round text-sm">edit</i>
                                                <span class="text-xs mr-0.5">ویرایش</span>
                                            </button>
                                            <button class="delete-btn inline-flex items-center px-2 py-1 bg-rose-100 text-rose-800 rounded hover:bg-rose-200 transition-colors duration-200" 
                                                data-route="{{route('customers.destroy', $customer->id)}}" data-type="customer">
                                                <i class="material-icons-round text-sm">delete</i>
                                                <span class="text-xs mr-0.5">حذف</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Edit Customer Modal -->
                                <x-edit-modal :id="'customerEditModal-'.$customer->id" title="ویرایش مشتری" :action="route('customers.update', $customer->id)" method="POST">
                                    @csrf
                                    <div class="grid grid-cols-12 gap-4 md:gap-6 items-end">
                                        <div class="col-span-6">
                                            <label for="edit_fullname" class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">نام و نام خانوادگی</label>
                                            <input type="text" name="fullname" id="edit_fullname" value="{{ $customer->fullname }}" 
                                                class="w-full px-3 md:px-4 py-2.5 md:py-2 text-sm border border-gray-300 rounded-lg md:rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                            
                                                <x-input-error :messages="$errors->get('fullname')" class="mt-2" />
                                                </div>
                                        <div class="col-span-6">
                                            <label for="edit_phone" class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">شماره تماس</label>
                                            <input type="tel" name="phone" id="edit_phone" value="{{ $customer->phone }}" 
                                                class="w-full px-3 md:px-4 py-2.5 md:py-2 text-sm border border-gray-300 rounded-lg md:rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                            
                                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
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

<!-- Create Customer Modal -->
<x-edit-modal id="createCustomerModal" title="ثبت مشتری جدید" :action="route('customers.store')" method="POST">
    @csrf
    <div class="grid grid-cols-12 gap-4 md:gap-6 items-end">
        <div class="col-span-6">
            <label for="fullname" class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">نام و نام خانوادگی</label>
            <input type="text" name="fullname" id="fullname" 
                class="w-full px-3 md:px-4 py-2.5 md:py-2 text-sm border border-gray-300 rounded-lg md:rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
        </div>
        <div class="col-span-6">
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">شماره تماس</label>
            <input type="tel" name="phone" id="phone" 
                class="w-full px-3 md:px-4 py-2.5 md:py-2 text-sm border border-gray-300 rounded-lg md:rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
        </div>
    </div>
</x-edit-modal>

<x-delete-modal />
@endsection