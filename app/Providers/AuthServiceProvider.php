<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\User' => 'App\Policies\UserPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
        /* define a admin user role */
        Gate::define('isAdmin', function($user) {
            return auth()->user()->getRoleNames()->first() == 'admin';
        });

        /* define a user role */
        Gate::define('isUser', function($user) {
            return auth()->user()->getRoleNames()->first() == 'user';
        });

        Gate::before(function (User $user){
            if(auth()->user()->getRoleNames(['super-admin','admin'])){
                return true;
            }
        });

        /*Only super-admin and admin allows to create a user*/
        Gate::define('create-user',function($user){
            return auth()->user()->getRoleNames()->first() == 'super-admin' || auth()->user()->getRoleNames()->first() == 'admin';
        });

        /*Only super-admin and admin allows to update a user*/
        Gate::define('update-user',function($user){
            return auth()->user()->getRoleNames()->first() == 'super-admin';
        });

        /*Only super-admin allows to delete a user*/
        Gate::define('delete-user',function($user){
            return auth()->user()->getRoleNames()->first() == 'super-admin';
        });
    }
}
