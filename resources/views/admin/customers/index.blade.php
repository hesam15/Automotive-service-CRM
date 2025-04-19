@extends('layouts.app')

@section('title', 'داشبورد')

@pushOnce('scripts')
<script>
    window.requiredManagers = window.requiredManagers || [];
    window.requiredManagers.push('phoneVerificationManager');
</script>
@endPushOnce

@section('content')
<div class="max-w-7xl mx-auto py-4 md:py-6">
    <x-errors-success-label />

    <!-- Customers List -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-100">
            <div class="flex items-center gap-3">
                <h4 class="text-xl font-bold text-gray-800">لیست مشتریان</h4>
                <button type="button" class="modal-trigger inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition duration-200" data-modal-target="createCustomerModal">
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
                <button type="button" class="modal-trigger inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200" data-modal-target="createCustomerModal">
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
                                    <td class="px-4 py-2 text-base text-gray-900 w-1/5">{{ $customer->name }}</td>
                                    <td class="px-4 py-2 text-base text-gray-900 w-1/5">{{ $customer->phone }}</td>
                                    <td class="px-4 py-2 text-base text-gray-900 w-1/5">
                                        <div class="flex items-center gap-2">
                                            @can('view_bookings')                                                
                                                <a href="{{ route('customers.bookings', $customer->id) }}" 
                                                    class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded hover:bg-blue-200 transition-colors duration-200">
                                                    <i class="material-icons-round text-sm">list_alt</i>
                                                    <span class="text-xs mr-0.5">تمام نوبت ها</span>
                                                </a>
                                            @endcan
                                            
                                            @can('create_bookings')                                                
                                                <a href="{{ route('bookings.create', $customer->id) }}" 
                                                    class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded hover:bg-blue-200 transition-colors duration-200">
                                                    <i class="material-icons-round text-sm">event_available</i>
                                                    <span class="text-xs mr-0.5">نوبت جدید</span>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 w-2/5">
                                        <div class="flex items-center gap-1">
                                            @can('view_customers')
                                                <a href="{{ route('customers.profile', ['customer' => $customer->id]) }}" 
                                                    class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 rounded hover:bg-green-200 transition-colors duration-200">
                                                    <i class="material-icons-round text-sm">person</i>
                                                    <span class="text-xs mr-0.5">پروفایل</span>
                                                </a>
                                            @endcan

                                            @can('edit_customers')                                                
                                                <button type="button" class="modal-trigger inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded hover:bg-blue-200 transition-colors duration-200" data-modal-target="customerEditModal-{{$customer->id}}">
                                                    <i class="material-icons-round text-sm">edit</i>
                                                    <span class="text-xs mr-0.5">ویرایش</span>
                                                </button>
                                                <button class="delete-btn inline-flex items-center px-2 py-1 bg-rose-100 text-rose-800 rounded hover:bg-rose-200 transition-colors duration-200" 
                                                    data-route="{{route('customers.destroy', $customer->id)}}" data-type="customer">
                                                    <i class="material-icons-round text-sm">delete</i>
                                                    <span class="text-xs mr-0.5">حذف</span>
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>

                                <!-- Edit Customer Modal -->
                                <x-edit-modal 
                                    :id="'customerEditModal-'.$customer->id" 
                                    title="ویرایش مشتری" 
                                    :action="route('customers.update', $customer->id)"
                                    maxWidth="md"
                                    method="POST">
                                    @csrf
                                    
                                    <div class="space-y-4">
                                        {{-- نام و نام خانوادگی --}}
                                        <div>
                                            <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-1">
                                                نام و نام خانوادگی
                                            </label>
                                            <input type="text" 
                                                name="name" 
                                                id="edit_name" 
                                                value="{{ $customer->name }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                placeholder="نام و نام خانوادگی را وارد کنید"
                                                required>
                                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                        </div>

                                        {{-- شماره تماس --}}
                                        <div>
                                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">شماره تلفن</label>
                                            <div class="relative">
                                                <input type="phone" id="phone-{{$customer->id}}" name="phone" value="{{ old('phone', $customer->phone) }}"
                                                    placeholder="شماره تلفن خود را وارد کنید"
                                                    class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200">
                                                <button type="button"
                                                    class="verify-phone-btn absolute left-2 top-1/2 -translate-y-1/2 px-4 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 text-sm" 
                                                    data-phone-id="{{$customer->id}}">
                                                    ارسال کد تایید
                                                </button>                                    
                                            </div>
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

    <x-create-modal 
        id="createCustomerModal"
        title="ایجاد مشتری جدید"
        action="{{ route('customers.store') }}"
        maxWidth="md"
        submitLabel="ایجاد مشتری">
        
        <div class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                    نام و نام خانوادگی
                </label>
                <input type="text" 
                    name="name" 
                    id="name"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    placeholder="نام و نام خانوادگی را وارد کنید"
                    required>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">شماره تلفن</label>
                <div class="phone-verification-form" id="phone-verification-register">
                    <div class="relative">
                        <input type="tel" name="phone" value="{{ old('phone') }}"
                            placeholder="شماره تلفن خود را وارد کنید"
                            class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200"
                            dir="rtl">
                        <button type="button"
                            class="send-code-btn absolute left-2 top-1/2 -translate-y-1/2 px-4 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 text-sm">
                            ارسال کد تایید
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>
            </div>
        </div>
    </x-create-modal>
</div>

<!-- Create Customer Modal -->


<x-delete-modal />
@endsection