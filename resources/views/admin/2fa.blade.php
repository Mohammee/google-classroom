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
@if($user->two_factor_secret && $user->two_factor_confirmed_at)
    <h3>Recovery Codes</h3>

    <ul>
        @foreach($user->recoveryCodes() as $code)
            <li>{{ $code }}</li>
        @endforeach
    </ul>

    <form action="{{ route('two-factor.disable') }}" method="post">
        @csrf
        @method('DELETE')
        <button class="btn btn-primary">Disable 2FA</button>
    </form>

@else
    @if(session('status') == 'two-factor-authentication-enabled')
        <div class="mb-4 font-medium text-sm">
            Please finish configuring two factor authentication below.
        </div>

    {!! $user->twoFactorQrCodeSvg() !!}

    <form action="{{ route('two-factor.confirm') }}" method="post" class="form my-5">
        @csrf
        <p>Enter to Confirm Enable 2FA</p>
        <input type="text" name="code" class="form-control">
        <button class="btn btn-primary">Confirm</button>
    </form>
    @else
        <form action="{{ route('two-factor.enable') }}" method="post">
            @csrf
            <button class="btn btn-primary">Enable 2FA</button>
        </form>
    @endif

@endif
</body>
</html>
