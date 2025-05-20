
@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
    <div class="container mx-auto px-4 py-12 md:py-20">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4">بهترین مجموعه‌های خدماتی</h1>
            <p class="text-lg md:text-xl text-blue-100 mb-8">خدمات مورد نیاز خود را با بهترین کیفیت دریافت کنید</p>
            
            <!-- Search Box -->
            <div class="bg-white p-2 rounded-lg shadow-lg max-w-2xl mx-auto">
                <div class="flex flex-col md:flex-row gap-2">
                    <div class="flex-1">
                        <input type="text" 
                               placeholder="نام مجموعه یا نوع خدمات..." 
                               class="w-full px-4 py-3 rounded-md text-gray-800 focus:outline-none"
                               dir="rtl">
                    </div>
                    <div class="flex-none">
                        <button class="w-full md:w-auto px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-150">
                            جستجو
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="container mx-auto px-4 py-8">
    <!-- Filters Section -->
    <div class="mb-8 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        <select class="w-full p-2 rounded-lg border border-gray-300 text-gray-700" dir="rtl">
            <option>منطقه</option>
            <option>شمال</option>
            <option>جنوب</option>
            <option>شرق</option>
            <option>غرب</option>
            <option>مرکز</option>
        </select>

        <select class="w-full p-2 rounded-lg border border-gray-300 text-gray-700" dir="rtl">
            <option>نوع خدمات</option>
            <option>تعمیرگاه</option>
            <option>نمایندگی مجاز</option>
            <option>فروشگاه قطعات</option>
        </select>

        <select class="w-full p-2 rounded-lg border border-gray-300 text-gray-700" dir="rtl">
            <option>امتیاز</option>
            <option>5 ستاره</option>
            <option>4 ستاره و بالاتر</option>
            <option>3 ستاره و بالاتر</option>
        </select>

        <select class="w-full p-2 rounded-lg border border-gray-300 text-gray-700" dir="rtl">
            <option>ساعت کاری</option>
            <option>24 ساعته</option>
            <option>روزانه</option>
            <option>شبانه</option>
        </select>
    </div>

    <!-- Service Centers Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Card 1 -->
        @foreach ($serviceCenters as $serviceCenter)
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="relative">
                    <img src="https://via.placeholder.com/600x300" alt="تصویر مجموعه" class="w-full h-48 object-cover rounded-t-xl">
                    <div class="absolute top-4 left-4">
                        <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm">باز است</span>
                    </div>
                </div>
                                
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">مجموعه فنی تخصصی ایران</h3>
                            <div class="flex items-center mt-1">
                                <div class="flex items-center text-yellow-400">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="mr-1 text-gray-600">4.8</span>
                                </div>
                                <span class="mx-2 text-gray-400">|</span>
                                <span class="text-gray-600">142 نظر</span>
                            </div>
                        </div>
                        <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-full transition duration-150">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-3 text-gray-600 mb-6">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <span class="mr-2">تهران، سعادت‌آباد، میدان کاج</span>
                        </div>
                        
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="mr-2">همه روزه از ۸ صبح تا ۸ شب</span>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">تعمیرگاه مجاز</span>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">خدمات فنی</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <button class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-150">
                            مشاهده و رزرو
                        </button>
                        <button class="mr-4 p-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-150">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Load More Button -->
    <div class="text-center mt-12">
        <button class="px-8 py-3 bg-white text-blue-600 border-2 border-blue-600 rounded-lg hover:bg-blue-50 transition duration-150">
            نمایش موارد بیشتر
        </button>
    </div>
</div>

<!-- Features Section -->
<div class="bg-gray-50 py-16 mt-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">چرا ما را انتخاب کنید؟</h2>
            <p class="text-gray-600">با ما بهترین خدمات را با بالاترین کیفیت دریافت کنید</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">تضمین کیفیت</h3>
                <p class="text-gray-600">تمامی خدمات ارائه شده دارای ضمانت کیفیت هستند</p>
            </div>

            <div class="text-center p-6">
                <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">سرعت در خدمات</h3>
                <p class="text-gray-600">ارائه سریع‌ترین خدمات در کوتاه‌ترین زمان ممکن</p>
            </div>

            <div class="text-center p-6">
                <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">پشتیبانی ۲۴/۷</h3>
                <p class="text-gray-600">پشتیبانی شبانه‌روزی در تمام روزهای هفته</p>
            </div>
        </div>
    </div>
</div>
@endsection