@extends('layouts.app')

@section('title', 'ویرایش مرکز سرویس')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4">
    <x-errors-success-label />

    <div class="bg-white rounded-xl shadow-lg">
        <!-- Header -->
        <div class="px-4 py-3 md:px-6 md:py-4 border-b border-gray-200 bg-gray-100">
            <h5 class="text-base md:text-xl font-semibold text-gray-800">فرم ویرایش مرکز سرویس</h5>
        </div>

        <!-- Form Content -->
        <div class="p-6">
            <form action="{{ route('serviceCenter.update', $serviceCenter->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Service Center Details -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">نام مرکز سرویس</label>
                        <input type="text" name="name" value="{{ old('name', $serviceCenter->name) }}"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">نام مدیر</label>
                        <input type="text" name="manager" value="{{ old('manager', $serviceCenter->manager) }}"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <x-input-error :messages="$errors->get('manager')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">شماره تماس</label>
                        <input type="string" name="phone" value="{{ old('phone', $serviceCenter->phone) }}"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" dir="rtl">
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">آدرس</label>
                        <input type="text" name="address" value="{{ old('address', $serviceCenter->address) }}"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>

                    <!-- Working Hours -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ساعت شروع کار</label>
                        <div class="flex items-center gap-2">
                            <select name="working_hours[start_hour][hour]" class="w-24 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @for ($i = 6; $i <= 22; $i++)
                                    <option value="{{ sprintf('%02d', $i) }}" 
                                        {{ old('working_hours.start_hour.hour', $serviceCenter->working_hours['start_hour']['hour']) == sprintf('%02d', $i) ? 'selected' : '' }}>
                                        {{ sprintf('%02d', $i) }}
                                    </option>
                                @endfor
                            </select>
                            <span class="text-gray-600">:</span>
                            <select name="working_hours[start_hour][minute]" class="w-24 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @foreach(['00', '15', '30', '45'] as $minute)
                                    <option value="{{ $minute }}"
                                        {{ old('working_hours.start_hour.minute', $serviceCenter->working_hours['start_hour']['minute']) == $minute ? 'selected' : '' }}>
                                        {{ $minute }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <x-input-error :messages="$errors->get('working_hours[start_hour][hour]')" class="mt-2" />
                        <x-input-error :messages="$errors->get('working_hours[start_hour][minute]')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ساعت پایان کار</label>
                        <div class="flex items-center gap-2">
                            <select name="working_hours[end_hour][hour]" class="w-24 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @for ($i = 6; $i <= 22; $i++)
                                    <option value="{{ sprintf('%02d', $i) }}"
                                        {{ old('working_hours.end_hour.hour', $serviceCenter->working_hours['end_hour']['hour']) == sprintf('%02d', $i) ? 'selected' : '' }}>
                                        {{ sprintf('%02d', $i) }}
                                    </option>
                                @endfor
                            </select>
                            <span class="text-gray-600">:</span>
                            <select name="working_hours[end_hour][minute]" class="w-24 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @foreach(['00', '15', '30', '45'] as $minute)
                                    <option value="{{ $minute }}"
                                        {{ old('working_hours.end_hour.minute', $serviceCenter->working_hours['end_hour']['minute']) == $minute ? 'selected' : '' }}>
                                        {{ $minute }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <x-input-error :messages="$errors->get('working_hours[end_hour][hour]')" class="mt-2" />
                        <x-input-error :messages="$errors->get('working_hours[end_hour][minute]')" class="mt-2" />
                    </div>

                    <!-- Friday Closure -->
                    <div class="md:col-span-2">
                        <div class="flex items-center space-x-4 space-x-reverse">
                            <label class="text-sm font-medium text-gray-700">وضعیت روز جمعه:</label>
                            <div class="flex items-center space-x-4 space-x-reverse">
                                <div class="flex items-center">
                                    <input type="radio" name="fridays_off" value="1" id="friday_closed_yes"
                                        {{ old('fridays_off', $serviceCenter->fridays_off) == true ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <label for="friday_closed_yes" class="mr-2 text-sm text-gray-700">تعطیل</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="fridays_off" value="0" id="friday_closed_no"
                                        {{ old('fridays_off', $serviceCenter->fridays_off) == false ? "checked" : '' }}
                                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <label for="friday_closed_no" class="mr-2 text-sm text-gray-700">باز</label>
                                </div>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('fridays_off')" class="mt-2" />
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end mt-6">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition duration-200">
                        <span class="material-icons-round text-base ml-1">save</span>
                        ذخیره تغییرات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection