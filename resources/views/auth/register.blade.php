<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ثبت نام</title>
    <script src="https://cdn.tailwindcss.com"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Vazirmatn", sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex flex-col lg:flex-row min-h-screen">
        <!-- Form Section -->
        <div class="w-full lg:w-1/2 lg:fixed lg:top-0 lg:right-0 min-h-screen bg-white">
            <div class="flex items-center justify-center min-h-screen px-4 lg:px-6 py-8 lg:py-0">
                <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-6 lg:p-8">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">ثبت نام</h2>
                        <p class="text-sm lg:text-base text-gray-600">برای ثبت نام اطلاعات خود را وارد کنید</p>
                    </div>

                    <hr class="my-6 border-gray-200">

                    <x-errors-success-label/>

                    <form method="POST" action="{{ route("registerUser") }}" class="space-y-6">
                        <div class="space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">نام</label>
                                <div class="relative">
                                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                                        placeholder="نام خود را وارد کنید"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400">
                                </div>
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">ایمیل</label>
                                <div class="relative">
                                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                                        placeholder="ایمیل خود را وارد کنید"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400">
                                </div>
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
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


                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">رمز عبور</label>
                                <div class="relative">
                                    <input type="password" id="password" name="password"
                                        placeholder="رمز عبور را وارد کنید"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400">
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">تکرار رمز عبور</label>
                                <div class="relative">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        placeholder="رمز عبور را تکرار کنید"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400">
                                </div>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 text-sm font-medium">
                            ثبت نام
                        </button>                        

                        <p class="text-center text-sm text-gray-600">
                            حساب کاربری دارید؟
                            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium">ورود</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>

        <!-- Image Section -->
        <div class="hidden lg:block lg:w-1/2 lg:mr-[50%]">
            <img src="{{asset('img/car-service.jpg')}}"
                alt="تعمیرگاه خودرو"
                class="w-full h-screen object-cover">
        </div>
    </div>    

    <script>
        window.requiredManagers = window.requiredManagers || [];
        window.requiredManagers.push('phoneVerificationManager');
    </script>
</body>
</html>