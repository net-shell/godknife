@extends('layouts.guest')
@section('title', 'Вход')

@section('content')
    <div class="container flex flex-row items-center justify-center h-screen px-6 mx-auto">
        <div class="mb-4 text-center text-white">
            @include('auth.partials.login')
        </div>
    </div>
@endsection
