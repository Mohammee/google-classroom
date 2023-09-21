<?php

namespace App\Models;

use App\Models\Scopes\UserClassroomScope;
use App\Observers\ClassroomObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Classroom extends Model
{
    use HasFactory, SoftDeletes;

    protected static $disk = 'uploads';

    protected $fillable = ['user_id', 'name', 'subject', 'section', 'room', 'code', 'image'];

    protected $appends = [
      'image_path'
    ];

    protected $hidden = [
      'image',
    ];

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

//    public function getImagePathAttribute(): string|null
//    {
//        $path = $this->attributes['image'] ?? null;
//        return isset($path) ? Storage::disk(self::$disk)->url($path) : null;
//    }


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
//        static::observe(ClassroomObserver::class);
//        static::addGlobalScope('user', function (Builder $builder){
//           $builder->where('user_id', Auth::id());
//        });

        static::addGlobalScope(new UserClassroomScope());


        // Creating, Created, Updating, updated, saving, saved
        //Deleting, Deleted, Restoring, Restored, ForceDeleting, ForceDeleting
        //Retried

//        static::creating(function(Classroom $classroom){
//            $classroom->code = Str::random(10);
//            $classroom->user_id = Auth::id();
//        });

//        static::forceDeleted(function(Classroom $classroom){
//            static::deleteImage($classroom->image);
//        });

//        static::deleted(function (Classroom $classroom){
//            $classroom->status = 'deleted';
//            $classroom->save();
//        });

//        static::restored(function (Classroom $classroom){
//            $classroom->status = 'active';
//            $classroom->save();
//        });
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

    public function join($user_id, $role = 'student')
    {
        $this->users()->attach($user_id, ['role' => $role, 'created_at' => now()]);
//        $this->users()->attach([$user_id], ['role' => $role, 'created_at' => now()]);
//        $this->users()->attach([
//            1 => [
//                'role' => $role,
//                'created_at' => now()
//            ],
//        ]);

//        return DB::table('classroom_user')->insert([
//            'classroom_id' => $this->id,
//            'user_id' => $user_id,
//            'role' => $role,
//            'created_at' => now()
//        ]);
    }


    public function getNameAttribute($value)
    {
        return strtoupper($value);
    }

    public function getImagePathAttribute()
    {
        return isset($this->image) ? Storage::disk(self::$disk)->url(
            $this->getAttribute('image')
        ) : 'https://placehold.co/600x400';
    }

    public function getShowUrlAttribute()
    {
        return route('classrooms.show', $this->id);
    }


    //relations

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function classworks()
    {
        return $this->hasMany(Classwork::class, 'classroom_id', 'id');
    }

    public function topics()
    {
        return $this->hasMany(Topic::class, 'classroom_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'classroom_user',
            'classroom_id',
            'user_id',
            'id',
            'id'
        )->withPivot(['role', 'created_at']);
//            ->as('join');
    }

    public function teachers()
    {
        return $this->users()->wherePivot('role', '=', 'teacher');
    }

    public function students()
    {
        return $this->users()->wherePivot('role', '=', 'student');
    }

    public function streams()
    {
        return $this->hasMany(Stream::class, 'classroom_id')->latest();
    }

    public function messages()
    {
        return $this->morphMany(Message::class, 'recipient');
    }

}


