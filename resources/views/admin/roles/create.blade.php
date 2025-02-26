@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-4 md:py-6">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-100">
            <h5 class="text-xl font-semibold text-gray-800">ایجاد نقش جدید</h5>
            <a href="{{ route('roles.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                بازگشت
            </a>
        </div>

        <div class="p-6">
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">نام انگلیسی</label>
                        <input type="text" name="name" 
                               class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200 @error('name') border-red-500 @enderror"
                               value="{{ old('name') }}" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">نام فارسی</label>
                        <input type="text" name="persian_name" 
                               class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200 @error('persian_name') border-red-500 @enderror"
                               value="{{ old('persian_name') }}" required>
                        @error('persian_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <label class="text-sm font-medium text-gray-700">دسترسی‌ها</label>
                        <button type="button" id="selectAllPermissions" class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">
                            <span class="select-all-text">انتخاب همه</span>
                        </button>
                    </div>

                    @if($permissions->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($permissions as $permission)
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                        name="permissions[]"
                                        value="{{ $permission->id }}"
                                        id="permission-{{ $permission->id }}"
                                        class="w-4 h-4 text-blue-600 rounded border-gray-300">
                                    <label for="permission-{{ $permission->id }}" class="mr-2 text-sm text-gray-700">
                                        {{ $permission->persian_name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <p class="text-gray-500">هیچ دسترسی در سیستم تعریف نشده است</p>
                        </div>
                    @endif
                </div>

                <div>
                    <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        ذخیره
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection