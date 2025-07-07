{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ثبت نام</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Image Section -->
        <div class="hidden lg:block lg:w-1/2 lg:mr-[50%]">
            <img src="{{asset('img/car-service.jpg')}}"
                alt="تعمیرگاه خودرو"
                class="w-full h-screen object-cover">
        </div>

        <!-- Form Section -->
        <div class="w-full lg:w-1/2 lg:fixed lg:top-0 lg:right-0 min-h-screen bg-white">
            <div class="flex items-center justify-center min-h-screen px-4 lg:px-6 py-8 lg:py-0">
                <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-6 lg:p-8">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">بازیابی رمز عبور</h2>
                        <p class="text-sm lg:text-base text-gray-600">ایمیل خود را وارد کنید تا لینک بازیابی رمز عبور برای شما ارسال شود</p>
                    </div>

                    <hr class="my-6 border-gray-200">

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">ایمیل</label>
                                <div class="relative">
                                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                                        placeholder="ایمیل خود را وارد کنید"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400"
                                        required autofocus>
                                </div>
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 text-sm font-medium">
                            ارسال لینک بازیابی
                        </button>

                        <p class="text-center text-sm text-gray-600">
                            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium">بازگشت به صفحه ورود</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html> --}}