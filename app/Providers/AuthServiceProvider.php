<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\ClassworkController;
use App\Models\Classroom;
use App\Models\Classwork;
use App\Models\Scopes\UserClassroomScope;
use App\Models\User;
use App\Policies\ClassworkPolicy;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */

    //if need to make one policy for more than one model use $model without strict type
    //auto discover must the folder policies with folder models level
    // you can use laravel auto_discovering if you named policy like ModelPolicy as (Policy known as post fixed)
    protected $policies = [
//        Classwork::class => ClassworkPolicy::class,
//        Classwork::class => classroom::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //Define Gates (abilities)

        //this is gate filters
        Gate::before(function(User $user, $ability){
          if($user->super_admin){
              return true;
          }
        });

        Gate::after(function (User $user, $ability, $result){
            $request = request();
            Log::info("The User {$user->name} with ability {$ability} in route {$request->url()} with result => " . ($result ? 'true' : 'false'));
        });

        Gate::define('classworks.view', function (User $user, Classwork $classwork) {
            $teacher = $user->classrooms()
                ->withoutGlobalScopes()
                ->wherePivot('role', '=', 'teacher')
                ->wherePivot('classroom_id', '=', $classwork->classroom_id)
                ->exists();
            $assigned = $user->classworks()
                ->wherePivot('classwork_id', '=', $classwork->id)
                ->exists();

            return ($teacher || $assigned);
//                ? Response::allow(): Response::deny('You are not assigned to this classwork.');
        });

        Gate::define('classworks.create', function (User $user, Classroom $classroom) {
             $result =  $user->classrooms()
                ->withoutGlobalScope(UserClassroomScope::class)
                ->wherePivot('role', '=', 'teacher')
                ->wherePivot('classroom_id', '=', $classroom->id)
                ->exists();

             return $result? Response::allow(): Response::deny();
        });

        Gate::define('submissions.create', function (User $user, Classwork $classwork){
            $teacher = $user->classrooms()
                ->withoutGlobalScopes()
                ->wherePivot('role', '=', 'teacher')
                ->wherePivot('classroom_id', '=', $classwork->classroom_id)
                ->exists();
            if($teacher){
                return false;
            }

            return $user->classworks()
                ->wherePivot('classwork_id', '=', $classwork->id)
                ->exists();

        });


//        Gate::before(function (User $user, $ability){
//         if($user->super_admin){
//             return true;
//         }
//        });

//        Gate::after(function (User $user, $ability, $result){
//            //used for log
//           dd($ability, $result);
//        });


//        Gate::define('classworks.create', function (User $user, Classroom $classroom){
//           return ($user->classrooms()
//               ->withoutGlobalScope(UserClassroomScope::class)
//           ->wherePivot('classroom_id', '=', $classroom->id)
//           ->wherePivot('role', '=', 'teacher')
//           ->exists()) ?
//               Response::allow(): Response::deny('You are not has access.');
//        });
    }
}
