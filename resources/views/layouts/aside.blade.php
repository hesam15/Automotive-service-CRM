<!-- Sidebar -->
<div id="sidebar" class="fixed inset-y-0 right-0 w-64 bg-white border-l border-gray-200 transform translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0 md:w-52 z-50">
    <!-- Logo -->
    <div class="sticky top-0 z-50 bg-gray-100 backdrop-blur-sm border-b border-gray-200 p-4">
        <span class="text-xl font-bold text-gray-800">کارشناسی خودرو</span>
    </div>

    <!-- Navigation -->
    <nav class="h-[calc(100vh-4rem)] overflow-y-auto">
        <div class="flex flex-col h-full p-3">
            <!-- Main Menu -->
            <div class="flex-1 space-y-2">
                @if($agent->isDesktop())
                <a href="{{route('home')}}" class="flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-100 {{ Route::currentRouteName() == 'home' ? 'bg-gray-100' : '' }}">
                    <i class="material-icons-round text-gray-500 text-lg ml-2 {{ Route::currentRouteName() == 'home' ? 'text-blue-600' : '' }}">dashboard</i>
                    <span class="text-gray-700 {{ Route::currentRouteName() == 'home' ? 'text-blue-600' : '' }}">داشبورد</span>
                </a>
                
                <!-- Bookings Menu -->
                <a href="{{route('bookings.index')}}" class="flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-100 {{ Route::currentRouteName() == 'bookings.index' ? 'bg-gray-100' : '' }}">
                    <i class="material-icons-round text-gray-500 text-lg ml-2">event_note</i>
                    <span class="text-gray-700 {{ Route::currentRouteName() == 'bookings.index' ? 'text-blue-600' : '' }}">لیست رزروها</span>
                </a>

                <!-- Customers Menu -->
                @permision("create_customer")
                    <div class="relative">
                        <button id="customersButton" class="w-full flex items-center justify-between px-3 py-2 text-sm rounded-lg hover:bg-gray-100 {{ in_array(Route::currentRouteName(), ['customers.index', 'customers.create']) ? 'bg-gray-100' : '' }}">
                            <div class="flex items-center">
                                <i class="material-icons-round text-gray-500 text-lg ml-2">people</i>
                                <span class="text-gray-700">مدیریت مشتریان</span>
                            </div>
                            <i class="material-icons-round text-gray-400 text-sm transition-transform duration-200" id="customersIcon">expand_more</i>
                        </button>

                        <div id="customersMenu" class="overflow-hidden transition-all duration-200" style="max-height: {{ in_array(Route::currentRouteName(), ['customers.index', 'customers.create']) ? '160px' : '0px' }}">
                            <div class="pr-7 mr-2 border-r border-gray-200 mt-1 space-y-1">
                                <a href="{{route('customers.index')}}" class="flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-100 {{ Route::currentRouteName() == 'customers.index' ? 'bg-gray-100' : '' }}">
                                    <i class="material-icons-round text-gray-500 text-lg ml-2">list</i>
                                    <span class="text-gray-700">لیست مشتریان</span>
                                </a>
                                <a href="{{route('customers.create')}}" class="flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-100 {{ Route::currentRouteName() == 'customers.create' ? 'bg-gray-100' : '' }}">
                                    <i class="material-icons-round text-gray-500 text-lg ml-2">event</i>
                                    <span class="text-gray-700">ثبت نام مشتری</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                @endpermision

                <!-- Services Menu -->
                @permision("create_option")
                    <div class="relative">
                        <button id="servicesButton" class="w-full flex items-center justify-between px-3 py-2 text-sm rounded-lg hover:bg-gray-100 {{ in_array(Route::currentRouteName(), ['show.options', 'create.option']) ? 'bg-gray-100' : '' }}">
                            <div class="flex items-center">
                                <i class="material-icons-round text-gray-500 text-lg ml-2">build</i>
                                <span class="text-gray-700">ثبت خدمات</span>
                            </div>
                            <i class="material-icons-round text-gray-400 text-sm transition-transform duration-200" id="servicesIcon">expand_more</i>
                        </button>

                        <div id="servicesMenu" class="overflow-hidden transition-all duration-200" style="max-height: {{ in_array(Route::currentRouteName(), ['show.options', 'create.option']) ? '160px' : '0px' }}">
                            <div class="pr-7 mr-2 border-r border-gray-200 mt-1 space-y-1">
                                <a href="{{route('show.options')}}" class="flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-100 {{ Route::currentRouteName() == 'show.options' ? 'bg-gray-100' : '' }}">
                                    <i class="material-icons-round text-gray-500 text-lg ml-2">list</i>
                                    <span class="text-gray-700">نمایش خدمات</span>
                                </a>
                                <a href="{{route('create.option')}}" class="flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-100 {{ Route::currentRouteName() == 'create.option' ? 'bg-gray-100' : '' }}">
                                    <i class="material-icons-round text-gray-500 text-lg ml-2">add</i>
                                    <span class="text-gray-700">ایجاد خدمات</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endpermision

                <!-- Roles Menu -->
                @permision("create_role")
                    <div class="relative">
                        <button id="rolesButton" class="w-full flex items-center justify-between px-3 py-2 text-sm rounded-lg hover:bg-gray-100 {{ in_array(Route::currentRouteName(), ['roles.index', 'roles.create']) ? 'bg-gray-100' : '' }}">
                            <div class="flex items-center">
                                <i class="material-icons-round text-gray-500 text-lg ml-2">admin_panel_settings</i>
                                <span class="text-gray-700">مدیریت نقش‌ها</span>
                            </div>
                            <i class="material-icons-round text-gray-400 text-sm transition-transform duration-200" id="rolesIcon">expand_more</i>
                        </button>

                        <div id="rolesMenu" class="overflow-hidden transition-all duration-200" style="max-height: {{ in_array(Route::currentRouteName(), ['roles.index', 'roles.create']) ? '160px' : '0px' }}">
                            <div class="pr-7 mr-2 border-r border-gray-200 mt-1 space-y-1">
                                <a href="{{route('roles.index')}}" class="flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-100 {{ Route::currentRouteName() == 'roles.index' ? 'bg-gray-100' : '' }}">
                                    <i class="material-icons-round text-gray-500 text-lg ml-2">list</i>
                                    <span class="text-gray-700">نمایش نقش‌ها</span>
                                </a>
                                <a href="{{route('roles.create')}}" class="flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-100 {{ Route::currentRouteName() == 'roles.create' ? 'bg-gray-100' : '' }}">
                                    <i class="material-icons-round text-gray-500 text-lg ml-2">add</i>
                                    <span class="text-gray-700">ایجاد نقش</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endpermision

                <!-- Users Menu -->
                @permision("create_user")
                    <div class="relative">
                        <button id="usersButton" class="w-full flex items-center justify-between px-3 py-2 text-sm rounded-lg hover:bg-gray-100 {{ in_array(Route::currentRouteName(), ['users.index', 'users.create']) ? 'bg-gray-100' : '' }}">
                            <div class="flex items-center">
                                <i class="material-icons-round text-gray-500 text-lg ml-2">manage_accounts</i>
                                <span class="text-gray-700">مدیریت کاربران</span>
                            </div>
                            <i class="material-icons-round text-gray-400 text-sm transition-transform duration-200" id="usersIcon">expand_more</i>
                        </button>

                        <div id="usersMenu" class="overflow-hidden transition-all duration-200" style="max-height: {{ in_array(Route::currentRouteName(), ['users.index', 'users.create']) ? '160px' : '0px' }}">
                            <div class="pr-7 mr-2 border-r border-gray-200 mt-1 space-y-1">
                                <a href="{{route('users.index')}}" class="flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-100 {{ Route::currentRouteName() == 'users.index' ? 'bg-gray-100' : '' }}">
                                    <i class="material-icons-round text-gray-500 text-lg ml-2">list</i>
                                    <span class="text-gray-700">نمایش کاربران</span>
                                </a>
                                <a href="{{route('users.create')}}" class="flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-100 {{ Route::currentRouteName() == 'users.create' ? 'bg-gray-100' : '' }}">
                                    <i class="material-icons-round text-gray-500 text-lg ml-2">add</i>
                                    <span class="text-gray-700">ایجاد کاربر</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endpermision
            </div>
        </div>
    </nav>
</div>

@if($agent->isMobile() || $agent->isTablet())
<!-- Mobile Bottom Navigation -->
<div class="fixed md:hidden bottom-0 left-0 right-0 h-16 bg-white border-t border-gray-200 flex justify-around items-center z-40">
    <a href="{{route('home')}}" class="flex flex-col items-center {{ Route::currentRouteName() == 'home' ? 'text-blue-600' : '' }}">
        <i class="material-icons-round {{ Route::currentRouteName() == 'home' ? 'text-blue-600' : 'text-gray-500' }} text-xl">dashboard</i>
        <span class="text-xs {{ Route::currentRouteName() == 'home' ? 'text-blue-600' : 'text-gray-700' }}">داشبورد</span>
    </a>

    @permision("create_customer")
        <a href="{{route('customers.index')}}" class="flex flex-col items-center {{ Route::currentRouteName() == 'customers.index' ? 'text-blue-600' : '' }}">
            <i class="material-icons-round {{ Route::currentRouteName() == 'customers.index' ? 'text-blue-600' : 'text-gray-500' }} text-xl">people</i>
            <span class="text-xs {{ Route::currentRouteName() == 'customers.index' ? 'text-blue-600' : 'text-gray-700' }}">مشتریان</span>
        </a>
        
        <a href="{{route('customer.form')}}" class="flex flex-col items-center {{ Route::currentRouteName() == 'customer.form' ? 'text-blue-600' : '' }}">
            <i class="material-icons-round {{ Route::currentRouteName() == 'customer.form' ? 'text-blue-600' : 'text-gray-500' }} text-xl">event</i>
            <span class="text-xs {{ Route::currentRouteName() == 'customer.form' ? 'text-blue-600' : 'text-gray-700' }}">نوبت‌دهی</span>
        </a>
    @endpermision
</div>
@endif