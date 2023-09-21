<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
@if(Auth::guard('admin')->user()->two_factor_secret && Auth::guard('admin')->user()->two_factor_secret)
    <form action="{{ route('two-factor.disable') }}" method="post">
        @csrf
        @method('delete')
        <button class="btn btn-primary">Disable 2FA</button>
    </form>

@else
    @if(session('status') == 'two-factor-authentication-enabled')
        <div class="mb-4 font-medium text-sm">
            Please finish configuring two factor authentication below.
        </div>
    @endif

    {!! $user->twoFactorQrCodeSvg() !!}

    <form action="{{ route('two-factor.disable') }}" method="post" class="form my-5">
        @csrf
        @method('DELETED')
        <button class="btn btn-primary">Disable 2FA</button>
    </form>

@endif
</body>
</html>
