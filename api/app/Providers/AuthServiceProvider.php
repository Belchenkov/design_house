<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Design;
use App\Policies\CommentPolity;
use App\Policies\DesignPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Design::class => DesignPolicy::class,
        Comment::class => CommentPolity::class,
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
    }
}
