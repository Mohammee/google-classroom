<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User;

class Admin extends User
{
    use HasFactory, HasApiTokens, Notifiable, TwoFactorAuthenticatable;

    public function devices()
    {
        return $this->morphMany(DeviceToken::class, 'tokenable');
    }
}
