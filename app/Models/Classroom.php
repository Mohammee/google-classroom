<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected static $disk = 'uploads';

    protected $fillable = ['name', 'subject', 'section', 'room', 'code', 'image'];


    public function getRouteKeyName()
    {
        return 'id';
    }

    public static function uploadImage($file)
    {
        return $file->store('/images', static::$disk);
    }
}
