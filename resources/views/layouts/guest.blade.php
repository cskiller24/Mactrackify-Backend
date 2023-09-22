<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <link href="{{ asset('dist/tabler/css/tabler.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/tabler/css/tabler-flags.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/tabler/css/tabler-payments.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/tabler/css/tabler-vendors.min.css') }}" rel="stylesheet">

    <title>
        @yield('title', 'Mactrackify')
    </title>
</head>

<body class="h-100">
    @if($disableFlasher ?? false === false)
        @php
            if($errors->any()) {
            foreach($errors->all() as $message) {
                flasher($message, 'error');
            }
        }
        @endphp
    @endif
    <div class="d-flex justify-content-center align-items-center">
        @yield('content')
    </div>
    <script src="{{ asset('dist/tabler/js/tabler.min.js') }}"></script>
</body>

</html>
