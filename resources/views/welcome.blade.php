<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'UMRED')</title>

       <script src="https://cdn.tailwindcss.com"></script>
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: '#4361ee',
                            secondary: '#3f37c9',
                            accent: '#4cc9f0',
                            dark: '#1e1b4b',
                        },
                        fontFamily: {
                            sans: ['Poppins', 'sans-serif'],
                        },
                    }
                }
            }
        </script>

    </head>
    <body class="bg-gray-100 text-gray-900">
         {{-- Navbar --}}
        @include('layouts.navbar')

        <main class="min-h-screen">
            @yield('content')
        </main>

        {{-- Footer --}}
        @include('layouts.footer')
    </body>
</html>
