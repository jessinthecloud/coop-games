<nav class="w-full mb-8">
    <div class="container flex flex-wrap items-center h-full mx-auto">
        <ul class="flex flex-wrap
            md:max-w-3/4
        ">
            <li class="menu-item">
                <a href="{{ route('home') }}">Home</a>
            </li>
            <li class="menu-item">
                <a href="{{ route('games.index') }}">Games</a>
            </li>
            <li class="menu-item">
                <a href="{{ route('about') }}">About</a>
            </li>
            <li class="menu-item">
                <a href="{{ route('contact') }}">Contact</a>
            </li>
        </ul>
        
        <livewire:search-box/>
        
        @if (Route::has('login'))
            <ul class="flex flex-wrap justify-end">
                <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                    @auth
                        <a href="{{ url('/home.blade.php') }}" class="text-sm text-gray-700 underline">Home</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a>
                        @endif
                    @endauth
                </div>
            </ul>
        @endif
    </div>
</nav>