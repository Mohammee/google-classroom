<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'plan_feature')
            ->withPivot(['feature_value']);
    }

    public function price(): Attribute
    {
        return new Attribute(
            get: fn($v) => $v/100,
            set: fn($v) => $v/100,
        );
    }
}
