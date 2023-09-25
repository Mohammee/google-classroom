<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $primaryKey = 'name';
    protected $keyType = 'string';

    protected $fillable = ['name', 'value', 'group'];

    public static function get($name, $default = null)
    {
        return static::query()
            ->where('name', '=', $name)->value('value') ?? $default;
    }

    public static function set($name, $value, $group = 'app'){
        static::query()
            ->updateOrCreate([
                'name' => $name
            ], ['value' => $value, 'group' => $group]);
    }

}
