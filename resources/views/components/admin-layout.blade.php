<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'Admin Internlog') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- theme noty sunset --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/noty@3.1.4/lib/noty.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/noty@3.1.4/lib/themes/sunset.css">

    {{-- Flowbite CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
</head>

<body>

    <x-sidebar />
    {{ $slot }}

    <script src="https://cdn.jsdelivr.net/npm/noty@3.1.4/lib/noty.min.js"></script>

    {{-- Flowbite JS --}}
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>

</html>
