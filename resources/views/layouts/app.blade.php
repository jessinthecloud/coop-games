@include('layouts.header')

    <x-navigation></x-navigation>

    <div
        class="container min-h-screen flex flex-column justify-center items-start mx-auto"
    >
        <div id="content-wrapper">
            @yield('content')
        </div>
        <!-- #content-wrapper -->
    </div>

@include('layouts.footer')