<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Orbit')</title>
	<!-- Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
	<!-- Owl Carousel -->
	<link rel="stylesheet"  href="{{ asset('assets/css/owl.carousel.min.css') }}">
	<link rel="stylesheet"  href="{{ asset('assets/css/owl.theme.default.css') }}">
	<!-- Magnific Popup -->
	<link rel="stylesheet"  href="{{ asset('assets/css/magnific-popup.css') }}">
	<!-- Styles -->
	<link rel="stylesheet"  href="{{ asset('assets/css/main.css') }}">
	<link rel="stylesheet"  href="{{ asset('assets/css/media.css') }}">
    <style>
        [x-cloak] { display: none !important; }
        
        body.disabled *:not(.section-modal, .section-modal *) {
            filter: grayscale(100%) blur(5px);
            pointer-events: none;
        }
        .section-modal {
            border-radius: 15px;
            position: fixed;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 999;
            top: 50dvh;
            box-shadow: 0 0 0 3000px rgb(0 0 0 / 40%);
        }
        /* #select-year .container-form {
            background-color: var(--color-blue-dark);
        } */
    </style>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="@yield('bodyClass', '')">
@include('partials.header')

@yield('content')


@include('partials.footer')

    
<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Owl Carousel -->
<script src="{{ asset('assets/script/owl.carousel.min.js') }}"></script>
<!-- Custom-select.js -->
<!-- <script src="{{ asset('assets/script/custom-select.min.js') }}"></script> -->
<!-- Magnific Popup -->
<script src="{{ asset('assets/script/magnific-popup.js') }}"></script>
<!-- jQuery.Maskedinput -->
<script src="{{ asset('assets/script/jquery.maskedinput.min.js') }}"></script>

<!-- JavaScript -->
<script src="{{ asset('assets/script/javascript.js') }}"></script>
</body>
</html>
