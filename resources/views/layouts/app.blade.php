@inject('agent', 'Jenssegers\Agent\Agent')

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield("title")</title>

    <!-- Fonts and Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    @if(optional(auth()->user())->serviceCenter)
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @endif

    <!-- Core Assets -->
    @vite(['resources/css/app.css'])
    
    <!-- Page Specific Styles -->
    @stack('styles')
</head>

<body class="font-sans antialiased {{ optional(auth()->user())->serviceCenter ? 'dashboard-page' : 'landing-page' }}">
    <div class="flex min-h-screen {{ optional(auth()->user())->serviceCenter ? 'bg-gray' : 'bg-white' }}">
        <!-- Sidebar - Only for Dashboard -->
        @if(optional(auth()->user())->serviceCenter)
            <div class="fixed md:w-52 w-full bottom-0 md:top-0 md:right-0 h-16 md:h-screen bg-white border-t md:border-l border-gray-200 shadow-sm z-50">
                @include('layouts.aside')
            </div>
        @endif
         
        <!-- Main Content -->
        <div class="flex-1 {{ optional(auth()->user())->serviceCenter ? 'md:mr-52 mb-16 md:mb-0' : '' }}">
            @if(!optional(auth()->user())->serviceCenter || $agent->isMobile())
                <nav class="sticky top-0 z-40 bg-white border-b border-gray-200">
                    @include('layouts.navigation')
                </nav>
            @endif

            {{-- Breadcrumb - Only for Dashboard --}}
            @if(optional(auth()->user())->serviceCenter)
                {{-- <div class="breadcrumb-container fixed top-[3.25rem] md:top-1 left-0 md:left-0 right-0 md:right-52 z-30 bg-white border-b border-gray-200 transform transition-all duration-300 ease-in-out">
                    {{ Breadcrumbs::render(Request::route()->getName(), isset($value) ? $value : null) }}
                </div> --}}
            @endif

            <main class="p-4 {{ optional(auth()->user())->serviceCenter ? 'mt-9' : '' }}">
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Core Scripts -->
    @vite(['resources/js/app.js'])
    
    <!-- Page Specific Scripts -->
    @stack('scripts')

    @if(optional(auth()->user())->serviceCenter)
        <script>
            // Verify dependencies are loaded
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof jQuery === 'undefined') {
                    console.error('jQuery is not loaded');
                }
            });
        </script>
    @endif

    @if (session('alert'))
    <script>
        window.alertData = @json(session('alert'));
    </script>
    @endif
</body>
</html>