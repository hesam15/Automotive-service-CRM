@inject('agent', 'Jenssegers\Agent\Agent')

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield("title")</title>

    <!-- Fonts and Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <!-- Load jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Then load Persian Date dependencies -->
    <link rel="stylesheet" href="https://unpkg.com/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
    <script src="https://unpkg.com/persian-date@1.1.0/dist/persian-date.min.js"></script>
    <script src="https://unpkg.com/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
    
    <!-- Your custom scripts last -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('js/style.js') }}"></script>
</head>

<body class="font-sans antialiased">
    <div class="flex min-h-screen bg-gray">
        <!-- Sidebar -->
        <div class="fixed md:w-52 w-full bottom-0 md:top-0 md:right-0 h-16 md:h-screen bg-white border-t md:border-l border-gray-200 shadow-sm z-50">
            @include('layouts.aside')
        </div>

        <!-- Main Content -->
        <div class="flex-1 md:mr-52 mb-16 md:mb-0">
            @if($agent->isMobile())
                <nav class="sticky top-0 z-40 bg-white border-b border-gray-200">
                    @include('layouts.navigation')
                </nav>
            @endif

            <div class="breadcrumb-container fixed top-[3.25rem] md:top-1 left-0 md:left-0 right-0 md:right-52 z-30 bg-white border-b border-gray-200 transform transition-all duration-300 ease-in-out">
                {{ Breadcrumbs::render(Request::route()->getName(), isset($value) ? $value : null) }}
            </div>

            <main class="p-4 mt-9">
                @yield('content')
            </main>
        </div>
    </div>

    @if(in_array(Route::current()->getName(), ['bookings.create', 'customers.bookings', 'customers.profile', 'bookings.index']))
        <script src="{{ asset('js/datepicker.js') }}"></script>
    @endif

    @if(Route::current()->getName() == 'users.index')
        @vite('resources/js/verify-code.js')
    @endif
</body>
</html>