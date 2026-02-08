<?php

namespace App\Providers;

<<<<<<< HEAD
=======
use App\Models\Project;
use App\Policies\ProjectPolicy;
use Illuminate\Support\Facades\Gate;
>>>>>>> df3d086 (Delete a project)
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
<<<<<<< HEAD
        //
=======
        Gate::policy(Project::class, ProjectPolicy::class);
>>>>>>> df3d086 (Delete a project)
    }
}
