<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        {{ config('app.name', 'SILAKAN') }}
    </title>

    <link rel="stylesheet"
    href="{{ asset('assets/css/silakan.css') }}">


    <link rel="stylesheet"
    href="{{ asset('assets/css/bi-theme.css') }}">

    <link 
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
    @stack('styles')
</head>


<body>

<div class="app">

    <x-sidebar />


    <main class="main">

        <x-navbar />


        <section class="content">

            {{ $slot }}

        </section>


    </main>

</div>
@stack('scripts')
</body>

</html>