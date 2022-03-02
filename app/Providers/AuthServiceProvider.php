<?php

namespace App\Providers;

use App\Models\Application;
use App\Models\Competition;
use App\Models\Jury;
use App\Models\Nomination;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Slide;
use App\Policies\ApplicationPolicy;
use App\Policies\CompetitionPolicy;
use App\Policies\JuryPolicy;
use App\Policies\NominationPolicy;
use App\Policies\PagePolicy;
use App\Policies\PartnerPolicy;
use App\Policies\SlidePolicy;
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
        Application::class => ApplicationPolicy::class,
        Nomination::class => NominationPolicy::class,
        Competition::class => CompetitionPolicy::class,
        Jury::class => JuryPolicy::class,
        Page::class => PagePolicy::class,
        Partner::class => PartnerPolicy::class,
        Slide::class => SlidePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

    }
}
