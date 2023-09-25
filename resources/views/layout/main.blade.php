<!DOCTYPE html>
<html dir="{{ App::isLocale('ar')? 'rtl': 'ltr' }}" lang="{{ App::currentLocale() }}">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    {{--    @if(App::currentLocale == 'ar')--}}
    @if(App::isLocale('ar'))
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.rtl.min.css"
              integrity="sha384-PRrgQVJ8NNHGieOA1grGdCTIt4h21CzJs6SnWH4YMQ6G5F5+IEzOHz67L4SQaF0o"
              crossorigin="anonymous">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
              integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    @endif

    @stack('extra-css')

    <title>{{ $title ?? config('app.name') }}</title>
</head>

<body class=" h-100">

@include('layout.partials._nav')

<!-- Begin page content -->
<main class="">
    <div class="container mt-xl-5">
        {{--{{ $test }}--}}
        {{ $slot }}
    </div>
</main>

@include('layout.partials._footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

<script>
    const userId = '{{ Auth::id() }}';
    const _token = '{{ csrf_token() }}';
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


@stack('extra-js')

@vite(['resources/js/app.js', 'resources/js/fcm.js'])

</body>
</html>
