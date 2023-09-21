<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserClassroomScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {


        if($id = Auth::id()){
            //use where with clause function  to make two condition like (c1 || c2)
            $builder->where(function(Builder $query) use ($id){
                $query->where('classrooms.user_id', '=', $id)
                    //tack querybuilder
                    ->orWhereExists(function (\Illuminate\Database\Query\Builder $query) use ($id){
                        $query->select(DB::raw('1'))
//                            ->from('classroom_user as cu')
                            ->from('classroom_user')
                            ->whereColumn('classroom_user.classroom_id', '=', 'classrooms.id')
                            ->where('classroom_user.user_id', '=', $id);
                    });
            });

//            ->orWhereRaw('exists (select 1 from classroom_user where classroom_id = classroom.id and user_id = ?)',[$id]);
        }
    }

    //select * from classrooms
    //where user_id = ?
    //or classroom_id in (select classroom_id from classroom_user where user_id = ?)
    //or exists (select 1 from classroom_user where classroom_id = classroom.id and user_id = ?)

}
