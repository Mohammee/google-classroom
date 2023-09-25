<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Classroom;
use App\Models\Classwork;
use App\Models\Post;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Spatie\Ignition\Solutions\OpenAi\OpenAiSolutionProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // this code not working because laravel start session in middleware so we make a middleware
        //middleware used for modification or intercept for request
//        $user = Auth::user();
//        App::setLocale($user->profile->locale);

        //using changed with you errors
//        $aiSolutionProvider = new OpenAiSolutionProvider(config('services.openai.key'));
////        $aiSolutionProvider->useCache(app('cache'));
//        \Spatie\Ignition\Ignition::make()
//            ->addSolutionProviders([
//                $aiSolutionProvider,
//                // other solution providers...
//            ])->register();
//
//        dd($aiSolutionProvider);


        \DB::listen(function ($query) {
            \Log::info(
                $query->sql,
                $query->bindings,
                $query->time
            );
        });

        \Model::preventLazyLoading(true);

        Relation::enforceMorphMap([
            'classwork' => Classwork::class,
            'post' => Post::class,
            'user' => User::class,
            'classroom' => Classroom::class,
            'admin' => Admin::class,
        ]);

        Paginator::useBootstrapFive();
//        Paginator::defaultSimpleView('vendor.pagination.bootstrap');
//        Paginator::defaultView('vendor.pagination.default');

//        ResourceCollection::withoutWrapping();


        //load settings
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $settings = Cache::get('app-settings');
        if(! $settings){
            $settings = Setting::pluck('value', 'name');
            Cache::put('app-settings', $settings);
        }

        foreach($settings as $name => $value){
            Config::set($name, $value);
        }
    }
}
