<?php

namespace App\Providers;

use App\Models\Story;
use App\Models\Comment;
use App\Models\User;
use App\Policies\StoryPolicy;
use App\Policies\CommentPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Story::class => StoryPolicy::class,
        Comment::class => CommentPolicy::class,
        User::class => UserPolicy::class,
        // ...other policies...
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // ...
    }
}
