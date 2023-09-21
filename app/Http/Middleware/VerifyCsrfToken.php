<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    //when use api like payment gateway
    //webhook is event come from third party to you application sometime
    //webhook third party send request to you not you send request
    //Webhooks allow external services to be notified when certain events happen
    protected $except = [
//        '/login'
    ];
}
