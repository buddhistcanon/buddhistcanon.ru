<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bc-background">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=alice:400|open-sans:400,600,700" rel="stylesheet"/>

    <!-- Scripts -->
    {{--    @routes--}}
    @vite('resources/js/app.js')
    @inertiaHead
</head>
<body class="font-sans antialiased h-full">

@inertia

@include('yandex-metric')
</body>
</html>
