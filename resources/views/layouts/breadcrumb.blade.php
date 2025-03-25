@unless ($breadcrumbs->isEmpty())
<div class="flex items-center justify-between text-sm ps-4 py-2">
    <div class="flex items-center">
        @foreach ($breadcrumbs as $breadcrumb)
            @if (!is_null($breadcrumb->url) && !$loop->last)
                <a class="text-gray-500" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
                <i class="material-icons-round text-gray-400 text-base mx-2">chevron_left</i>
            @else
                <span class="text-gray-500">{{ $breadcrumb->title }}</span>
            @endif
        @endforeach
    </div>
    
    <div class="relative ml-4">
        <button id="userDropdown" class="flex items-center text-gray-700 px-4 py-2 hover:bg-gray-100 rounded-lg transition-colors duration-200">
            <span class="material-icons-round text-gray-600 ml-2">account_circle</span>
            {{auth()->user()->name}}
            <span class="material-icons-round text-gray-400 mr-1 transition-transform duration-200">expand_more</span>
        </button>
        
        <div id="userMenu" class="hidden absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
            <a href="{{route('users.profile', auth()->user()->id)}}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                <span class="material-icons-round text-gray-500 ml-2">person</span>
                نمایش پروفایل
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                    <span class="material-icons-round text-gray-500 ml-2">logout</span>
                    خروج از حساب کاربری
                </button>
            </form>
        </div>
    </div>    
</div>
@endunless