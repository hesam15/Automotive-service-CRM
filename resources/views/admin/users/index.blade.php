@extends('layouts.app')

@section('title', 'لیست کاربران')


@section('content')
<div class="max-w-7xl mx-auto py-6 px-4">
    <x-errors-success-label />

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-100 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800">لیست کاربران</h2>
            <a type="button"
                class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition duration-200"
                data-modal-target="userCreateModal" href="{{ route('users.create') }}">
                <span class="material-icons-round text-base ml-1">add</span>
                افزودن کاربر جدید
            </a>
        </div>

        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-2 text-right text-sm font-medium text-gray-500">نام کاربر</th>
                            <th class="px-6 py-2 text-right text-sm font-medium text-gray-500">شماره تماس</th>
                            <th class="px-6 py-2 text-right text-sm font-medium text-gray-500">نقش</th>
                            <th class="px-6 py-2 text-right text-sm font-medium text-gray-500">عملیات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-900">{{ $user->name }}</td>
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-900">{{ $user->phone ?? '---' }}</span>
                                    <button type="button"
                                        class="inline-flex items-center px-2 py-1 bg-amber-100 text-amber-800 rounded hover:bg-amber-200 transition-colors duration-200"
                                        data-modal-target="phoneEditModal-{{$user->id}}">
                                        <i class="material-icons-round text-sm">edit</i>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm">
                                    {{ $user->roles->first()->persian_name }}
                                </span>
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex gap-2">
                                    <button type="button" class="modal-trigger inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded hover:bg-blue-200 transition-colors duration-200" data-modal-target="userEditModal-{{$user->id}}">
                                        <i class="material-icons-round text-sm">edit</i>
                                        <span class="text-xs mr-0.5">ویرایش</span>
                                    </button>
                                    <button class="delete-btn inline-flex items-center px-2 py-1 bg-rose-100 text-rose-800 rounded hover:bg-rose-200 transition-colors duration-200" 
                                        data-route="{{route('users.destroy', $user->id)}}" data-type="user">
                                        <i class="material-icons-round text-sm">delete</i>
                                        <span class="text-xs mr-0.5">حذف</span>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <x-edit-modal :id="'phoneEditModal-'.$user->id" title="ویرایش شماره تماس" :action="route('users.update.phone', $user->id)" method="POST">
                            @csrf
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">شماره تلفن</label>
                                <div class="relative">
                                    <input type="phone" id="phone-{{$user->id}}" name="phone" value="{{ old('phone', $user->phone) }}"
                                        placeholder="شماره تلفن خود را وارد کنید"
                                        class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200">
                                    <button type="button"
                                        class="verify-phone-btn absolute left-2 top-1/2 -translate-y-1/2 px-4 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 text-sm" 
                                        data-phone-id="{{$user->id}}">
                                        ارسال کد تایید
                                    </button>                                    
                                </div>
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>
                        </x-edit-modal>

                        <x-edit-modal :id="'userEditModal-'.$user->id" title="ویرایش کاربر" :action="route('users.update', $user->id)" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">نام کاربر</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                        class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200 @error('name') border-red-500 @enderror">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">ایمیل</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                                        class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200 @error('email') border-red-500 @enderror">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">نقش کاربر</label>
                                <select name="role" 
                                    class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200 @error('role') border-red-500 @enderror">
                                    
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ $user->roles->first()->id == $role->id ? 'selected' : '' }}>
                                            {{ $role->persian_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>               
                        </x-edit-modal>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<x-delete-modal />

@endsection
