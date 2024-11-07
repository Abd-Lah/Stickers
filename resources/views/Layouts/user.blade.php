<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="{{asset('css/main.css')}}" />
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

    <title>Stickers</title>
</head>

<body>
<!-- Header -->
@include('user.includes.header')

@yield('content')

<!-- Footer -->
@include('user.includes.footer')

@if(request()->is('cart'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cart = JSON.parse(localStorage.getItem('cart')) || {};
            if (Object.keys(cart).length === 0) {
                window.location.href = '/';
            }
        });
    </script>
@endif

<!-- Scripts -->
<script>
    const btnOpenSidebar = document.querySelector("#btn-menu")
    const sidebar = document.querySelector("#sidebar")
    const btnCloseSidebar = document.querySelector("#close-sidebar")


    btnOpenSidebar.addEventListener("click", function () {
        sidebar.style.left = "0"

    })

    btnCloseSidebar.addEventListener("click", function () {
        sidebar.style.left = "-100%"
    })


    /* click outside start */
    document.addEventListener("click", (event) => {
        if (!event.composedPath().includes(sidebar) && !event.composedPath().includes(btnOpenSidebar)) {
            sidebar.style.left = "-100%"
        }
    })
</script>
</body>

</html>
