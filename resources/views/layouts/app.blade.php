<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" :class="{ 'theme-dark': dark }" x-data="data()">

<head>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GodKnife') }}</title>
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <script src="https://cdn.tiny.cloud/1/um58igmni57s76quz66y5o5brcsb3lmjd5uldskd84uzvxxi/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="{{ asset('js/init-alpine.js') }}"></script>
    @livewireStyles
    @livewireScripts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    @if (Auth::check())
        <div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">
            @include('layouts.partials.admin-desktop_sidebar')
            <div class="flex flex-col flex-1 w-full">
                @include('layouts.partials.navigation')
                @if (session()->has('success'))
                    <script>
                        setTimeout(function() {
                            document.querySelector('.alert').remove();
                        }, 5000);
                    </script>
                    <div role="alert"
                        class="absolute z-10 flex items-center w-auto p-2 mb-2 leading-none text-green-100 bg-blue-600 rounded-full alert top-right-5 lg:rounded-full lg:inline-flex">
                        <span class="flex px-2 py-1 mr-3 text-xs font-bold uppercase bg-blue-500 rounded-full">
                            Успех
                        </span>
                        <span class="flex-auto mr-2 font-semibold text-left">{{ session('success') }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </div>
                    @php
                        session()->forget('success');
                    @endphp
                @endif

                @if (session()->has('error'))
                    <script>
                        setTimeout(function() {
                            document.querySelector('.alert').remove();
                        }, 5000);
                    </script>
                    <div role="alert"
                        class="absolute z-10 flex items-center w-auto p-2 mb-2 leading-none text-red-100 bg-red-800 rounded-full alert top-right-5 lg:rounded-full lg:inline-flex">
                        <span
                            class="flex px-2 py-1 mr-3 text-xs font-bold uppercase bg-red-500 rounded-full">ERROR</span>
                        <span class="flex-auto mr-2 font-semibold text-left">{{ session('error') }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </div>
                    @php
                        session()->forget('error');
                    @endphp
                @endif
                <main class="h-full flex flex-row justify-between overflow-y-auto bg-blue-100 dark:bg-gray-900">
                    <div class="flex-1">
                        @yield('content')
                    </div>
                    <div class="">
                        @include('layouts.partials.contacts_sidebar')
                    </div>
                </main>
            </div>
        </div>
    @elseif (Auth::check() && Auth::user()->is_private === 0)
        <div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">
            @include('layouts.desktop_sidebar')
            @include('layouts.mobile_sidebar')
            <div class="flex flex-col flex-1 w-full">
                @include('layouts.navigation')
                <main class="h-full overflow-y-auto bg-blue-100 dark:bg-gray-900">
                    @if (auth()->user()->banned_to > now('Europe/Sofia'))
                        <div
                            class="absolute z-10 flex items-center w-auto p-2 mb-2 leading-none text-red-100 bg-red-800 rounded-full bottom-right-5 lg:rounded-full lg:inline-flex">
                            <span class="flex px-2 py-1 mr-3 text-xs font-bold uppercase bg-red-500 rounded-full">BANNED
                                USER</span>
                            <span class="flex-auto mr-2 font-semibold text-left">Your Account is banned to
                                {{ auth()->user()->banned_to }}</span>
                        </div>
                    @endif
                    @if (session()->has('success'))
                        <script>
                            setTimeout(function() {
                                document.querySelector('.alert').remove();
                            }, 5000);
                        </script>
                        <div role="alert"
                            class="absolute z-10 flex items-center w-auto p-2 mb-2 leading-none text-green-100 bg-blue-600 rounded-full alert top-right-5 lg:rounded-full lg:inline-flex">
                            <span
                                class="flex px-2 py-1 mr-3 text-xs font-bold uppercase bg-blue-500 rounded-full">SUCCESS</span>
                            <span class="flex-auto mr-2 font-semibold text-left">{{ session('success') }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </div>
                        @php
                            session()->forget('success');
                        @endphp
                    @endif

                    @if (session()->has('error'))
                        <script>
                            setTimeout(function() {
                                document.querySelector('.alert').remove();
                            }, 5000);
                        </script>
                        <div role="alert"
                            class="absolute z-10 flex items-center w-auto p-2 mb-2 leading-none text-red-100 bg-red-800 rounded-full alert top-right-5 lg:rounded-full lg:inline-flex">
                            <span
                                class="flex px-2 py-1 mr-3 text-xs font-bold uppercase bg-red-500 rounded-full">ERROR</span>
                            <span class="flex-auto mr-2 font-semibold text-left">{{ session('error') }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </div>
                        @php
                            session()->forget('error');
                        @endphp
                    @endif
                    <div class="flex flex-row">
                        <div>
                            @yield('content')
                        </div>
                        <div class="flex-1">
                            @include('layouts.partials.contacts_sidebar')
                        </div>
                    </div>
                </main>
            </div>
        </div>
    @else
        <div class="flex items-center justify-center h-screen">
            <div class="flex flex-col items-center justify-center">
                <img src="{{ asset('images/website/security.gif') }}" alt="security" class="w-1/2" />
                <h1 class="mt-2 text-3xl font-bold text-center">
                    We have locked your account. Please contact admin.
                </h1>
                <div class="flex gap-6">
                    <a href="mailto:support@godknife.com?subject=My Account is Locked &body=Hello%20Admin%2C"
                        class="flex items-center justify-center px-4 py-2 mt-4 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-purple">
                        Contact Admin
                    </a>
                    <a href="{{ route('logout') }}"
                        class="flex items-center justify-center px-4 py-2 mt-4 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-purple">
                        Log Out
                    </a>
                </div>
            </div>
        </div>
    @endif
</body>

</html>
