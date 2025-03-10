@extends('layouts.app')

@section('title', 'افزودن کاربر جدید')

@section('content')
<div class="max-w-7xl mx-auto py-4 md:py-6">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h5 class="text-xl font-semibold text-gray-800">افزودن کاربر جدید</h5>
            <a href="{{ route('users.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                بازگشت
            </a>
        </div>

        <div class="p-6">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-6 mb-6">
                    <!-- First section: Personal Info -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">نام و نام خانوادگی</label>
                            <input type="text" name="name" placeholder="نام و نام خانوادگی خود را وارد کنید"
                                class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200"
                                value="{{ old('name') }}" required>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                    
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">شماره تلفن</label>
                            <div class="relative">
                                <input type="phone" id="phone-register" name="phone" value="{{ old('phone') }}"
                                    placeholder="شماره تلفن خود را وارد کنید"
                                    class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200">
                                <button type="button"
                                    class="verify-phone-btn absolute left-2 top-1/2 -translate-y-1/2 px-4 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 text-sm"
                                    data-phone-id="register">
                                    ارسال کد تایید
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>
                    
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ایمیل</label>
                            <input type="email" name="email" placeholder="ایمیل خود را وارد کنید"
                                class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200"
                                value="{{ old('email') }}" required>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                    </div>                    

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">رمز عبور</label>
                            <input type="password" name="password" placeholder="رمز عبور خود را وارد کنید"
                                class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200"
                                required>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تکرار رمز عبور</label>
                            <input type="password" name="password_confirmation" placeholder="تکرار رمز عبور خود را وارد کنید"
                                class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200"
                                required>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Second section: Role -->
                    <div class="mb-6">
                        <div class="w-full">
                            <label class="block text-sm font-medium text-gray-700 mb-2">نقش کاربر</label>
                            <select name="role"
                                class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->persian_name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>
                    </div>
                </div>                                         

                <div class="flex justify-end">
                    <button type="submit" 
                            class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 inline-flex items-center">
                        <span class="material-icons-round text-xl">save</span>
                        <span class="mr-2">ذخیره کاربر</span>
                    </button>
                </div>                
            </form>
        </div>
    </div>
</div>
@endsection