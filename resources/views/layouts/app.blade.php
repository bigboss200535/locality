<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }} | {{ $title ?? 'POS Application' }}</title>
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
        <!-- <link href="{{ asset('css/all.min.css') }}" rel="stylesheet" type="text/css" /> -->
       <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> -->
         <link href="{{ asset('flaticon/css/all.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('fontawesome/css/all.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- jQuery (Required for Select2) -->
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/4.0.0/jquery.min.js"></script> -->
           <!-- Select2 CSS -->
        <!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" /> -->
        <!-- Select2 JavaScript -->
       
       
       

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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.basic-select-one').select2();
        });
    </script>
     <!-- <script> 
        let timer;

            $('input[name=search]').on('keyup', function(){
                clearTimeout(timer);
                timer=setTimeout(function(){
                    $('#search_form').submit();

                },500);

            });
    </script> -->
</html>
