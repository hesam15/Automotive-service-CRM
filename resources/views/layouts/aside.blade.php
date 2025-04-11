<<div id="sidebar" class="fixed inset-y-0 right-0 w-64 bg-white border-l border-gray-200 transform translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0 md:w-52 z-50 flex flex-col">
    <!-- Logo -->
    <div class="flex-none sticky top-0 z-50 bg-gray-100 backdrop-blur-sm border-b border-gray-200 p-4 text-center">
        <span class="text-xl font-bold text-gray-800">{{ auth()->user()->serviceCenter->name }}</span>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-h-0"> <!-- min-h-0 is crucial for nested flex containers -->
        <!-- Scrollable Navigation -->
        <nav class="flex-1 overflow-y-auto p-3">
            <div class="space-y-2">
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
                @can("view_customers")
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
                                @can('create_customers')
                                    <a href="{{route('customers.create')}}" class="flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-100 {{ Route::currentRouteName() == 'customers.create' ? 'bg-gray-100' : '' }}">
                                        <i class="material-icons-round text-gray-500 text-lg ml-2">event</i>
                                        <span class="text-gray-700">ثبت نام مشتری</span>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    @endif
                @endcan

                <!-- Services Menu -->
                @can("create_options")
                <div class="relative">
                    <button id="servicesButton" class="w-full flex items-center justify-between px-3 py-2 text-sm rounded-lg hover:bg-gray-100 {{ in_array(Route::currentRouteName(), ['options.index', 'options.create']) ? 'bg-gray-100' : '' }}">
                        <div class="flex items-center">
                            <i class="material-icons-round text-gray-500 text-lg ml-2">build</i>
                            <span class="text-gray-700">ثبت خدمات</span>
                        </div>
                        <i class="material-icons-round text-gray-400 text-sm transition-transform duration-200" id="servicesIcon">expand_more</i>
                    </button>
                
                    <div id="servicesMenu" class="overflow-hidden transition-all duration-200" style="max-height: {{ in_array(Route::currentRouteName(), ['options.index', 'options.create']) ? '160px' : '0px' }}">
                        <div class="pr-7 mr-2 border-r border-gray-200 mt-1 space-y-1">
                            <a href="{{route('options.index')}}" class="flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-100 {{ Route::currentRouteName() == 'options.index' ? 'bg-gray-100' : '' }}">
                                <i class="material-icons-round text-gray-500 text-lg ml-2">list</i>
                                <span class="text-gray-700">نمایش خدمات</span>
                            </a>
                            <a href="{{route('options.create')}}" class="flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-100 {{ Route::currentRouteName() == 'options.create' ? 'bg-gray-100' : '' }}">
                                <i class="material-icons-round text-gray-500 text-lg ml-2">add</i>
                                <span class="text-gray-700">ایجاد خدمات</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endcan

                <!-- Users Menu -->
                @can("create_users")
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
                @endcan
            </div>
        </nav>

        @if (!auth()->user()->tokens()->exists() && auth()->user()->can("create_api_key"))
            <div class="flex-none p-3">
                <a href="#" 
                    id="apiKeyButton"
                    class="modal-trigger flex items-center justify-center px-3 py-2 text-sm rounded-lg bg-blue-500 hover:bg-blue-600 transition-colors duration-200"
                    data-modal-target="apiKeyModal">
                    <i class="material-icons-round text-white text-lg ml-2">key</i>
                    <span class="text-white font-medium">دریافت API Key</span>
                </a>
            </div>
        @endif

        @can("edit_serviceCenters")
            <!-- Bottom Edit Button - Always stays at bottom -->
            <div class="flex-none p-3 border-t">
                <a href="{{ route('serviceCenter.edit', auth()->user()->serviceCenter->id) }}" class="flex items-center justify-center px-3 py-2 text-sm rounded-lg hover:bg-gray-100">
                    <i class="material-icons-round text-gray-500 text-lg ml-2">edit</i>
                    <span class="text-gray-700">مشخصات مجموعه</span>
                </a>
            </div>
        @endcan
    </div>
</div>

@if($agent->isMobile() || $agent->isTablet())
<!-- Mobile Bottom Navigation -->
<div class="fixed md:hidden bottom-0 left-0 right-0 h-16 bg-white border-t border-gray-200 flex justify-around items-center z-40">
    <a href="{{route('home')}}" class="flex flex-col items-center {{ Route::currentRouteName() == 'home' ? 'text-blue-600' : '' }}">
        <i class="material-icons-round {{ Route::currentRouteName() == 'home' ? 'text-blue-600' : 'text-gray-500' }} text-xl">dashboard</i>
        <span class="text-xs {{ Route::currentRouteName() == 'home' ? 'text-blue-600' : 'text-gray-700' }}">داشبورد</span>
    </a>

    @can("create_customer")
        <a href="{{route('customers.index')}}" class="flex flex-col items-center {{ Route::currentRouteName() == 'customers.index' ? 'text-blue-600' : '' }}">
            <i class="material-icons-round {{ Route::currentRouteName() == 'customers.index' ? 'text-blue-600' : 'text-gray-500' }} text-xl">people</i>
            <span class="text-xs {{ Route::currentRouteName() == 'customers.index' ? 'text-blue-600' : 'text-gray-700' }}">مشتریان</span>
        </a>
        
        <a href="{{route('customer.form')}}" class="flex flex-col items-center {{ Route::currentRouteName() == 'customer.form' ? 'text-blue-600' : '' }}">
            <i class="material-icons-round {{ Route::currentRouteName() == 'customer.form' ? 'text-blue-600' : 'text-gray-500' }} text-xl">event</i>
            <span class="text-xs {{ Route::currentRouteName() == 'customer.form' ? 'text-blue-600' : 'text-gray-700' }}">نوبت‌دهی</span>
        </a>
    @endcan
</div>
@endif

@if (!auth()->user()->tokens()->exists() && auth()->user()->can("create_api_key"))
    <x-modal id="apiKeyModal" title="API Key شما">
        <div>
            <p class="mb-2 text-sm text-gray-600">API Key شما با موفقیت ایجاد شد:</p>
            <div class="bg-gray-100 p-4 rounded-lg relative">
                <div class="flex items-center gap-4">
                    <div id="apiKeyContent" class="font-mono text-sm break-all w-[550px] whitespace-normal"></div>
                    <button class="copy-api-key flex-shrink-0 text-gray-500 hover:text-gray-700 focus:outline-none">
                        <i class="material-icons-round text-lg">content_copy</i>
                    </button>
                </div>
            </div>
            <p class="mt-2 text-xs text-gray-500">لطفا این کلید را در جای امنی ذخیره کنید.</p>
        </div>

        <x-slot name="footer">
            <button type="button" class="modal-close w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                تایید
            </button>
        </x-slot>
    </x-modal>
@endif