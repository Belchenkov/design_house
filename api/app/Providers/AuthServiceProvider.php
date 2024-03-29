<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Design;
use App\Models\Invitation;
use App\Models\Message;
use App\Models\Team;
use App\Policies\CommentPolity;
use App\Policies\DesignPolicy;
use App\Policies\InvitationPolicy;
use App\Policies\MessagePolicy;
use App\Policies\TeamPolicy;
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
        Team::class => TeamPolicy::class,
        Invitation::class => InvitationPolicy::class,
        Message::class => MessagePolicy::class,
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
