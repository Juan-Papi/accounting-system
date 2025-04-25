<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Fitnet Sport Center</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Fontawesome --}}
    <script src="https://kit.fontawesome.com/8adc7a3e69.js" crossorigin="anonymous"></script>

    {{-- dropzone --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css"
        integrity="sha512-jU/7UFiaW5UBGODEopEqnbIAHOI8fO6T99m7Tsmqs2gkdujByJfkCbbfPSN4Wlqlb9TGnsuC0YgUgWkRBK7B9A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- sweetalert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- DropZone cdn --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"
        integrity="sha512-U2WE1ktpMTuRBPoCFDzomoIorbOyUv0sP8B+INA3EzNAhehbzED1rOJg6bCqPf/Tuposxb5ja/MAUnC8THSbLQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Styles -->
    @livewireStyles

    {{-- por la pasarela de pago --}}
    {{-- con los stack nosotros decimos que mas adelante cuando extendamos la plantilla podamos pasar codigo css o javascript --}}
    @stack('css')
    {{-- con los stack nosotros decimos que mas adelante cuando extendamos la plantilla podamos pasar codigo css o javascript --}}
    {{-- cuando ocupemos mediante el push el codigo se colocará justo donde hayamos definido el stack js del app blade --}}
</head>

<body class="font-sans antialiased">
    <x-banner />

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @livewire('navigation-menu')


        <!-- Page Content -->
        <main class="pt-1">
            {{ $slot }}
        </main>
    </div>

    @stack('modals')

    @livewireScripts

    {{-- por la pasarela de pago --}}
    {{-- con los stack nosotros decimos que mas adelante cuando extendamos la plantilla podamos pasar codigo css o javascript --}}
    @stack('js')
    {{-- desde el otro lado donde extendemos la plantilla le pasamos mediante push y haciendo referencia al stack que queramos ocupar --}}
</body>

</html>
