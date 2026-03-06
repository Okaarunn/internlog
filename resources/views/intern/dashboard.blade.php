<x-intern-layout title="Dashboard Aplikasi Internlog">
    <h1>Selamat datang {{ Auth::user()->name }}</h1>


    {{-- <x-success-notification></x-success-notification> --}}

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">logout</button>
    </form>


</x-intern-layout>
