<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible"
        content="IE=edge" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0" />
    <link rel="icon"
        href="{{asset('frontend/favicon-32x32.png')}}"
        sizes="16x16"
        type="image/png">
    <title>Welcome | Ya-Hub</title>
    <link rel="stylesheet"
        href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p"
        crossorigin="anonymous" />
    <link rel="stylesheet"
        href="{{asset('frontend/assets/css/bootstrap.css')}}" />
    <link rel="stylesheet"
        href="{{asset('frontend/Style/index.css')}}" />
    <script src="{{asset('frontend/assets/js/jquery.slim.min.js')}}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body>@yield('content') <script src="{{asset('frontend/assets/js/bootstrap.bundle.js')}}"></script>
    <script src="{{asset('frontend/Script/Script.js')}}"></script>
    <script>
    $(document).ready(function() {
        @if(\Session::has('register_success'))
        // setTimeout(() => {
        //     window.location.href = "{{env('MAIN_APP_URL')}}";
        // }, 2000);
        Swal.fire({
            title: "Signed Up",
            text: "",
            icon: "success",
            confirmButtonText: "Go to main website and login.",
        }).then((value) => {
            if (value.isConfirmed) {
                window.location.href = "{{env('MAIN_APP_URL')}}";
            }
        });
        @endif
        @if(\Session::has('logout_success'))
        Swal.fire({
            title: " Successfully Logged Out",
            text: "",
            icon: "success",
            confirmButtonText: "Go to main website.",
        }).then((value) => {
            if (value.isConfirmed) {
                window.location.href = "{{env('MAIN_APP_URL')}}";
            }
        });
        @endif
        @if(\Session::has('account_deleted'))
        Swal.fire({
            title: "We'll Miss You.",
            text: "",
            icon: "success",
            confirmButtonText: "Go to main website.",
        }).then((value) => {
            if (value.isConfirmed) {
                window.location.href = "{{env('MAIN_APP_URL')}}";
            }
        });
        @endif
    })
    </script>
</body>

</html>
