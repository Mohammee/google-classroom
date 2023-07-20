<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Classroom extends Model
{
    use HasFactory;

    protected static $disk = 'uploads';

    protected $fillable = ['name', 'subject', 'section', 'room', 'code', 'image'];


    public function getRouteKeyName()
    {
        return 'id';
    }

    public static function deleteImage($path)
    {
        if(!is_null($path) && Storage::disk(self::$disk)->exists($path)){
            Storage::disk(self::$disk)->delete($path);
        }
    }

    public static function uploadImage($file)
    {
        return $file->store('/images', self::$disk);
    }

//    public function image():Attribute
//    {
//        return new Attribute(
//            get: fn($value) => $value ? Storage::disk(self::$disk)->url($value): null
//        );
//    }

    public function getImagePathAttribute(): string|null
    {
        $path = $this->attributes['image'] ?? null;
        return isset($path) ? Storage::disk(self::$disk)->url($path): null;
}
}
