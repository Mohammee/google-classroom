<?php

namespace App\Models;

use App\Models\Scopes\UserClassroomScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Classroom extends Model
{
    use HasFactory, SoftDeletes;

    protected static $disk = 'uploads';

    protected $fillable = ['user_id', 'name', 'subject', 'section', 'room', 'code', 'image'];


    public function getRouteKeyName()
    {
        return 'id';
    }

    public static function deleteImage($path)
    {
        if (!is_null($path) && Storage::disk(self::$disk)->exists($path)) {
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
        return isset($path) ? Storage::disk(self::$disk)->url($path) : null;
    }


//local scope
    public function scopeStatus(Builder $builder, $status = 'active')
    {
        $builder->where('status', '=', $status);
    }

    public function scopeRecent(Builder $builder)
    {
        $builder->orderBy('updated_at', 'desc');
    }


    //global scope

    //booted vs boot
    protected static function booted()
    {
//        static::addGlobalScope('user', function (Builder $builder){
//           $builder->where('user_id', Auth::id());
//        });

        static::addGlobalScope(new UserClassroomScope());
    }

//    protected static function boot()
//    {
//        parent::boot();
////        static::addGlobalScope('user', function (Builder $builder){
////           $builder->where('user_id', Auth::id());
////        });
//
//        static::addGlobalScope(new UserClassroomScope());
//    }

}
