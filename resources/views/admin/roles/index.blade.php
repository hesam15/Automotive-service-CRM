@extends('layouts.app')

@section('title', 'لیست نقش ها')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        window.app.getManager("modalManager");
    });
</script>
@endpush

@section('content')
<div class="max-w-7xl mx-auto py-4 md:py-6">
    @include('layouts.label')

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-100">
            <h5 class="text-xl font-semibold text-gray-800">لیست نقش‌ها</h5>
            <a href="{{ route('roles.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                <span class="material-icons-round text-xl ml-2">add</span>
                افزودن نقش جدید
            </a>
        </div>

        <div class="p-6">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="px-6 py-2 text-right text-sm font-medium text-gray-500 w-1/12">#</th>
                        <th class="px-6 py-2 text-right text-sm font-medium text-gray-500 w-3/12">نام انگلیسی</th>
                        <th class="px-6 py-2 text-right text-sm font-medium text-gray-500 w-3/12">نام فارسی</th>
                        <th class="px-4 py-2 text-right text-sm font-medium text-gray-500 w-2/12">عملیات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($roles as $role)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-2 text-sm text-gray-900">{{ $loop->iteration }}</td>
                            <td class="px-6 py-2 text-base text-gray-900">{{ $role->name }}</td>
                            <td class="px-6 py-2 text-base text-gray-900">{{ $role->persian_name }}</td>
                            <td class="px-4 py-2">
                                <div class="flex items-center gap-1">
                                    <button type="button"
                                        class="modal-trigger inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded hover:bg-blue-200 transition-colors duration-200"
                                        data-modal-target="roleEditModal-{{$role->id}}">
                                        <i class="material-icons-round text-sm">edit</i>
                                        <span class="text-xs mr-0.5">ویرایش</span>
                                    </button>
                                    <button class="inline-flex items-center px-2 py-1 bg-rose-100 text-rose-800 rounded hover:bg-rose-200 transition-colors duration-200 delete-btn" 
                                        data-route="{{ route('roles.destroy', $role->id) }}" 
                                        data-type="role">
                                        <i class="material-icons-round text-sm">delete</i>
                                        <span class="text-xs mr-0.5">حذف</span>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <x-edit-modal :id="'roleEditModal-'.$role->id" title="ویرایش نقش" :action="route('roles.update', $role->id)" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">نام انگلیسی</label>
                                    <input type="text" name="name" 
                                        class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200 @error('name') border-red-500 @enderror"
                                        value="{{ old('name', $role->name) }}" required>
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                    
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">نام فارسی</label>
                                    <input type="text" name="persian_name" 
                                        class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200 @error('persian_name') border-red-500 @enderror"
                                        value="{{ old('persian_name', $role->persian_name) }}" required>
                                    @error('persian_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                    
                            <div class="mb-6">
                                <div class="flex justify-between items-center mb-4">
                                    <label class="text-sm font-medium text-gray-700">دسترسی‌ها</label>
                                    @if($permissions->count() > 0)
                                        <button type="button" id="selectAll-{{$role->id}}" 
                                                class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200 transition-colors duration-200">
                                            <span id="selectAllText-{{$role->id}}">انتخاب همه</span>
                                        </button>
                                    @endif
                                </div>
                    
                                @if($permissions->count() > 0)
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                        @foreach($permissions as $permission)
                                            <div class="flex items-center">
                                                <input type="checkbox"
                                                    name="permissions[]"
                                                    value="{{ $permission->id }}"
                                                    id="perm_{{ $role->id }}_{{ $permission->id }}"
                                                    class="permission-checkbox-{{$role->id}} w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                                    {{ in_array($permission->id, old('permissions', $role->permissions ? $role->permissions->pluck('id')->toArray() : [])) ? 'checked' : '' }}>
                                                <label class="mr-2 text-sm text-gray-700" for="perm_{{ $role->id }}_{{ $permission->id }}">
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
                        </x-edit-modal>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8">
                                <div class="text-center">
                                    <span class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-800 rounded-lg">
                                        <i class="material-icons-round text-lg ml-2">info</i>
                                        هیچ نقشی یافت نشد
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<x-delete-modal />
@endsection