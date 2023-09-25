<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements HasLocalePreference
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

//    public function setEmailAttribute($value)
//    {
//        $this->attributes['email'] = ucfirst($value);
//    }

    protected function email()
    {
        return Attribute::make(
            get: fn($value) => strtoupper($value),
            set: fn($value) => strtolower($value)
        );
    }

    public function classrooms()
    {
        return $this->belongsToMany(
            Classroom::class,
            'classroom_user',
            'user_id',
            'classroom_id',
            'id',
            'id'
        )->withPivot(['role', 'created_at']);
    }

    public function classworks()
    {
        return $this->belongsToMany(
            Classwork::class,
            'classwork_user',
            'user_id',
            'classwork_id',
            'id',
            'id'
        )->withPivot(['garde', 'submitted_at', 'status', 'created_at'])
            ->using(ClassworkUser::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'user_id', 'id');
    }

    // withdefault used with belongto and hasone
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'id')
            ->withDefault();
    }


//    public function receivesBroadcastNotificationsOn()
//    {
//        return "Notifications." . $this->id;
//    }

    public function routeNotificationForMail($notification = null)
    {
        // Return email address only...
//        return $this->email_address;

        // Return email address and name...
        return [$this->email => $this->name];
    }

    //when send notification send translation as notifiable need
    public function preferredLocale()
    {
        return $this->profile->locale;
    }

    public function routeNotificationForVonage($notification = null)
    {
        return '972592828060';
//        return $this->mobile;
    }

    public function routeNotificationForHadara($notification = null)
    {
        return '972592828060';
    }

    public function receivedMessages()
    {
        return $this->morphMany(Message::class, 'recipient');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    //fcm
    public function devices()
    {
        return $this->morphMany(DeviceToken::class, 'tokenable');
    }

    public function routeNotificationForFcm($notification = null)
    {
        // you can return array of all device token
        //you can return single token
//        $tokens = [];
//        foreach($this->devices as $device){
//            $tokens['token'] = $device->token;
//        }
        return $this->devices()->pluck('token')->toArray();
    }

}
