<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    @yield('extra-css')

    <title>@yield('title', config('app.name'))</title>
</head>

<body class="d-flex flex-column h-100">

@include('layout.partials._nav')

<!-- Begin page content -->
<main class="">
    <div class="container mt-xl-5">

        @yield('content')
    </div>
</main>

@include('layout.partials._footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>


<script>
    const userId = '{{ Auth::id() }}'
</script>
@stack('extra-js')

@vite(['resources/js/app.js'])

</body>
</html>
