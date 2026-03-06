<x-admin-layout>
    {{-- <h1>Selamat datang {{ Auth::user()->name }}</h1> --}}

    <form action="{{ route('admin.logout') }}" method="POST">
        @csrf
        <button type="submit">logout</button>
    </form>
</x-admin-layout>
