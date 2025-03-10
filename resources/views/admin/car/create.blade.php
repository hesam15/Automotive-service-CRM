<!-- resources/views/admin/cars/create.blade.php -->
@extends('layouts.app')

@section('title', 'افزودن خودرو')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4">
    <x-errors-success-label />

    <div class="bg-white rounded-xl shadow-lg">
        <!-- Header -->
        <div class="px-4 py-3 md:px-6 md:py-4 border-b border-gray-200 bg-gray-100">
            <h5 class="text-base md:text-xl font-semibold text-gray-800">فرم ثبت خودرو مشتری</h5>
        </div>

        <!-- Form Content -->
        <div class="p-6">
            <form action="{{ route('cars.store', $customer->id) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Owner Info -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">مالک خودرو</label>
                        <div class="px-3 py-2 bg-gray-50 rounded-lg text-gray-900">{{ $customer->fullname }}</div>
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                    </div>

                    <!-- Car Details -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">نام خودرو</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">رنگ</label>
                        <input type="text" name="color" value="{{ old('color') }}"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <x-input-error :messages="$errors->get('color')" class="mt-2" />
                    </div>

                    <!-- License Plate -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">پلاک خودرو</label>
                        <div class="grid grid-cols-4 gap-4">
                            <div>
                                <input type="number" min="0" maxlength="2" name="plate_iran" placeholder="ایران" value="{{ old('plate_iran') }}"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <x-input-error :messages="$errors->get('plate_iran')" class="mt-2" />
                            </div>
                            <div>
                                <input type="number" min="0" maxlength="3" name="plate_three" maxlength="3" placeholder="سه رقم"  value="{{ old('plate_three') }}"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <x-input-error :messages="$errors->get('plate_three')" class="mt-2" />
                            </div>
                            <div>
                                <input type="text" name="plate_letter" maxlength="1" placeholder="حرف" value="{{ old('plate_letter') }}"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blu500">
                                <x-input-error :messages="$errors->get('plate_letter')" class="mt-2" />
                            </div>
                            <div>
                                <input type="number" min="0" maxlength="2" name="plate_two" maxlength="2" placeholder="دو رقم" value="{{ old('plate_two') }}"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <x-input-error :messages="$errors->get('plate_two')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end mt-6">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition duration-200">
                        <span class="material-icons-round text-base ml-1">save</span>
                        ثبت خودرو
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.oninput = function() {
            if (this.value.length >= this.maxLength) {
                this.value = this.value.slice(0, this.maxLength);
            }
        }
    });
</script>
@endsection