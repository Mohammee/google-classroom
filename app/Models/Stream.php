<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Stream extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
       'user_id', 'content', 'classroom_id'
    ];

    public function uniqueIds()
    {
        return [
            'id'
        ];
    }

     protected static function booted()
     {
//        static::creating(function (stream $stream){
//           $stream->id = Str::uuid();
//        });
     }

    public function getUpdatedAtColumn()
    {

    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
