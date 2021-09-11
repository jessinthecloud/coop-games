<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

{{--        <!-- Fonts -->--}}
{{--        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">--}}

    <!-- Styles -->
    @livewireStyles
    
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/common.css') }}" rel="stylesheet">
    {{-- allows other views to also add styles --}}
    @stack('styles')

    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body class="antialiased w-full flex flex-col text-gray-200">