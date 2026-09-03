<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login — SILAKAN KPwBI Provinsi Sulawesi Utara">
    <meta name="theme-color" content="#003b73">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>Login | SILAKAN — KPwBI Prov. Sulut</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/logo-bi2.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo-bi2.png') }}">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/silakan.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bi-theme.css') }}">

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

<div class="login-page">
    {{ $slot }}
</div>

</body>
</html>