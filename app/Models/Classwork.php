<?php

namespace App\Models;

use App\Enums\ClassworkType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classwork extends Model
{

    use HasFactory;

    const TYPE_ASSIGNMENT = ClassworkType::ASSIGNMENT;
    const TYPE_MATERIAL = ClassworkType::MATERIAL;
    const TYPE_QUESTION = ClassworkType::QUESTION;

    const STATUS_PUBLISHED = 'published';
    const STATUS_DRAFT = 'draft';

    protected $fillable = [
        'classroom_id',
        'user_id',
        'topic_id',
        'title',
        'description',
        'type',
        'status',
        'published_at',
        'options'
    ];

    protected $casts = [
        'options' => 'json',
        'classroom_id' => 'integer',
        // when use format for cast this working in json
//        'published_at' => 'datetime:Y-m-d',
        'published_at' => 'datetime',
        'type' => ClassworkType::class

    ];

    protected static function booted()
    {
        static::creating(function (Classwork $classwork) {
            $classwork->published_at = now();
        });
    }

    public function scopeFilter(Builder $builder, $filers)
    {
        $builder->when($filers['search'] ?? null, function ($builder, $value) {
            // this is important to make ( or ) and
            $builder->where(function ($query) use ($value) {
                $query->where('title', 'like', "%$value%")
                    ->orWhere('description', 'like', "%$value%");
            });
        })
            ->when($filers['type'] ?? null, function ($builder, $value) {
            });
    }

    public function getPublishedDateAttribute()
    {
        if ($this->published_at) {
            return $this->published_at->format('Y-m-d');
        }

        return null;
    }

//    public function getOptionsAttribute($value)
//    {
//
//        return json_decode($value, true);
//    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id', 'id');
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id', 'id')
            ->withDefault(['name' => 'assigment']);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'classwork_user')
            ->withPivot(['grade', 'submitted_at', 'status', 'created_at'])
            ->using(ClassworkUser::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
//            ->latest();
    }

}
