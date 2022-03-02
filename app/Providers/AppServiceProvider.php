<?php

namespace App\Providers;

use App\Models\Application;
use App\Models\Attachment;
use App\Models\User;
use App\Observers\ApplicationObserver;
use App\Observers\AttachmentObserver;
use App\Services\YoutubeStatsService;
use App\Observers\UserObserver;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        setlocale(LC_TIME, 'ru_RU.UTF-8');
        Carbon::setLocale(config('app.locale'));

        Attachment::observe(AttachmentObserver::class);
        User::observe(UserObserver::class);

        \Blade::if('administrator', function () {
            return backpack_user()->hasRole('administrator');
        });

        \Blade::if('moderator', function () {
            return backpack_user()->hasRole('moderator');
        });

        $this->app->bind('YoutubeStatsService', YoutubeStatsService::class);
    }
}
