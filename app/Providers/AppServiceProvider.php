<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('hash_check', function ($attribute, $value, $parameters, $validator) {
            return Hash::check($value, current($parameters));
        });

        Validator::extend('mobile_check', function ($attribute, $value, $parameters, $validator) {
            return str_replace('_', '', $value);
        });

        Validator::extend('not_exists', function ($attribute, $value, $parameters, $validator) {
            return DB::table($parameters[0])
                ->where($parameters[1], $parameters[2], $parameters[3])
                ->where($parameters[4], $parameters[5], $parameters[6])
                ->exists();
        });
    }
}
