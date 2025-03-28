<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="en">

<head>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title') - {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script src="{{ asset('js/init-alpine.js') }}"></script>
    <style>
        .bg-gk-dark {
            background-color: #151924;
        }

        .bg-gk-light {
            background-color: #595c63;
        }
    </style>
    @yield('styles')
</head>

<body>
    <main class="z-0">
        @yield('content')
    </main>

    <div class="fixed w-full h-full bg-black opacity-90 top-0 left-0 z-10"></div>
    <div class="fixed w-full h-full top-0 left-0 z-20">
        <div class="container flex flex-row items-center justify-center h-screen px-6 mx-auto">
            <div class="mb-4 text-center text-white">
                @include('auth.partials.login')
            </div>
        </div>
    </div>
</body>

</html>
