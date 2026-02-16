<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    <!-- Styles -->
    @livewireStyles

    @vite(['resources/css/app.css', 'resources/sass/common.scss', 'resources/js/app.js'])
    {{-- allows other views to also add styles --}}
    @stack('styles')
</head>
<body class="antialiased w-full flex flex-col text-gray-200">
