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
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.theme.default.css') }}">
    <!-- Magnific Popup -->
    <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.css') }}">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/media.css') }}">
    <style>
        html {
            scroll-behavior: smooth;
        }

        [x-cloak] {
            display: none !important;
        }

        body.disabled *:not(.modal-form, .modal-form *) {
            filter: blur(4px);
            pointer-events: none;
        }

        .modal-form,
        .modal-receipt {
            border-radius: 15px;
            position: fixed;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 999;
            top: 50dvh;
            box-shadow: 0 0 0 3000px rgb(0 0 0 / 40%);
        }

        .table-footer {
            padding-top: 16px;
        }

        .spinner {
            border: 3px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top: 3px solid #3498db;
            width: 24px;
            height: 24px;
            animation: spin 0.5s ease-in infinite;
            display: block;
            margin: 6px auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .custom-select .custom-select-container.customSelect .custom-select-panel {
            background-color: rgb(212 213 231);
        }

        .custom-button {
            text-transform: uppercase;
            text-decoration: none;
            text-align: center;
            font-size: 20px;
            line-height: 21px;
            font-weight: 800;
            font-style: italic;
            color: var(--font-color-primary);
            display: inline-block;
            background-color: var(--color-pink);
            border-radius: 20px;
            border: none;
            outline: none;
            padding: 15px 40px;
        }

        a,
        button {
            cursor: pointer;
        }

        .container-form .body .form .input-row span {
            display: block;
        }

        .section-table .container-table .table-block .table-body .table-tabs-content .table-block__content .row>div {
            text-align: center;
        }

        .upload-btn {
            display: flex;
            font-size: 22px;
            font-weight: 700;
            font-style: italic;
            background-color: #fff;
            border-radius: 50px;
            padding: 10px 60px;
            gap: 8px;
            color: var(--color-blue-dark);
        }

        .hidden {
            display: none !important;
        }

        .custom-select {
            font-size: 19px;
            font-family: 'Montserrat';
            font-weight: 800;
            font-style: italic;
            color: var(--color-blue-dark);
            padding: 13px 25px;
            padding-right: 75px;
            border-radius: 13px;
            -webkit-appearance: none;
            -moz-appearance: none;
            -o-appearance: none;
            appearance: none;
        }

        .select_arrow {
            display: flex;
            background-color: var(--color-pink);
            padding: 12px;
            border-radius: 13px;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            margin-left: -50px;
            pointer-events: none;
        }

        @media only screen and (max-width: 722px) {
            .section-header .container-navigation-mobile {
                padding-bottom: 20px;
            }

            .section-header .container-navigation {
                /* display: flex; */
                flex-direction: column;
                gap: 16px;
                margin-bottom: 20px;
            }

            .section-header .container-navigation .container-navigation_column-01 {
                display: flex;
                flex-direction: column;
                gap: 16px;
            }
            .section-header .container-header .container-header_column-02 .image img {
                width: auto;
            }

            .sm-hide {
                display: none;
            }
            .wrapper-profile .container-profile .block-profile .block-profile__column-01 a {
                display: block;
                padding: 20px;
                margin-top: 32px;
            }
            .wrapper-profile .container-profile .block-notification {
                display: block;
            }
            .wrapper-profile .container-profile .block-notification .notification {
                width: auto;
            }
        }

        @media only screen and (min-width: 722px) {
            .md-hide {
                display: none;
            }
        }

        .change-lang {
            text-transform: capitalize;
            font-size: 12px;
            font-family: 'Montserrat';
            font-weight: 700;
            text-decoration: none;
            position: relative;
            color: #fff;
        }
        .change-lang img {
            position: absolute;
            left: -15px;
            top: -15px;
            z-index: -1;
        }
    </style>
    <script defer src="{{ asset('assets/script/mask.js') }}"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script type="text/javascript">
        document.addEventListener('alpine:init', () => {
            Alpine.store('modal', {
                signIn: false,
                registration: false,
                receipt: false,
                receiptPage: 0,
                voucher: false,
                voucherData: {},
                modal: {}
            });
            Alpine.store('nav', {
                play(e) {
                    if (!Alpine.store('user').token) {
                        e.preventDefault();
                        Alpine.store('modal').signIn = true;
                    }
                }
            });
            Alpine.store('user', {
                token: localStorage.getItem('token') || null,
                setToken(token) {
                    this.token = token;
                    localStorage.setItem('token', token);
                },
                info: JSON.parse(localStorage.getItem('userInfo')) || {},
                setInfo(info) {
                    this.info = info;
                    localStorage.setItem('userInfo', JSON.stringify(info)); // stringify the info object
                },
                updateProfile() {
                    const profile = document.getElementById('profile-block');
                    if (profile) {
                        profile.dispatchEvent(new CustomEvent('update-data'));
                    }
                },
                logOut() {
                    localStorage.removeItem('token');
                    localStorage.removeItem('userInfo');
                    this.token = null;
                    this.info = {};
                }
            });
            Alpine.store('service', {
                async signIn(data) {
                    const response = await fetch('/api/{{ app()->getLocale() }}/signIn', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();
                    if (!result.success) {
                        throw result.errors || {
                            message: [result.message]
                        };
                    }
                    Alpine.store('user').setToken(result.data.token);
                    window.location.href = `/{{ app()->getLocale() }}/profile`;
                },
                async logOut() {
                    const response = await fetch('/api/{{ app()->getLocale() }}/logout', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${ Alpine.store('user').token }`,
                            'Accept': 'application/json',
                        }
                    });

                    const result = await response.json();

                    Alpine.store('user').logOut();
                    window.location.href = `/{{ app()->getLocale() }}`;
                }
            });
        });
    </script>
</head>

<body class="@yield('bodyClass', '')">
    @include('partials.header')

    @include('partials.modals.receipt')
    @include('partials.modals.voucher')
    @include('partials.modals.modal')

    @yield('content')


    @include('partials.footer')


    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Owl Carousel -->
    <script src="{{ asset('assets/script/owl.carousel.min.js') }}"></script>
    <!-- Magnific Popup -->
    <script src="{{ asset('assets/script/magnific-popup.js') }}"></script>

    <!-- JavaScript -->
    <script src="{{ asset('assets/script/javascript.js') }}"></script>
</body>

</html>