<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <meta name="description" content="Paces is a modern, responsive admin dashboard available on ThemeForest. Ideal for building CRM, CMS, project management tools, and custom web applications with a clean UI, flexible layouts, and rich features." />
        <meta name="keywords" content="Paces, admin dashboard, ThemeForest, Bootstrap 5 admin, responsive admin, CRM dashboard, CMS admin, web app UI, admin theme, premium admin template" />
        <meta name="author" content="Mohammed Alhassan" />
        <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" />
        <link href="{{ asset('plugins/jsvectormap/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
        <script src="{{ asset('js/config.js') }}"></script>
        <script src="{{ asset('js/demo.js') }}"></script>
        <script src="{{ asset('demo.js') }}"></script>
        <link href="{{ asset('css/vendors.min.css') }}" rel="stylesheet" type="text/css" />
        <link id="app-style" href="{{ asset('css/app.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- <link rel="stylesheet" href="https://jsdelivr.net"> -->
       <!-- <script src="https://cdn.jsdelivr.net/npm/@flaticon/flaticon-uicons@3.3.1/license.min.js"></script> -->
       <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@flaticon/flaticon-uicons@3.3.1/css/all/all.min.css"> -->
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
       

        <!-- @vite(['resources/css/app.css', 'resources/js/app.js']) -->
    </head>
    <body>
        <div class="wrapper">
             <!-- Header Menu Start -->
             <header class="app-topbar">
                @include('layouts.header')
            </header>
             <!-- Header Menu End -->

             <!-- Sidenav Menu start -->
             <div class="sidenav-menu">
              @include('layouts.sidebar')
            </div>
            <!-- Sidenav Menu End -->
            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
    <!-- <script src="{{ asset('js/vendors.min.js') }}"></script> -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/vendors.min.js') }}"></script>
    <script src="{{ asset('plugins/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('plugins/jsvectormap/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('js/maps/world-merc.js') }}"></script>
    <script src="{{ asset('js/maps/world.js') }}"></script>
    <script src="{{ asset('js/pages/custom-table.js') }}"></script>
    <script src="{{ asset('js/pages/dashboard-ecommerce.js') }}"></script>
</html>
