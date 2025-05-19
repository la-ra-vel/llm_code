<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\ClientCase;
use App\Models\Group;
use App\Models\Quotation;
use App\Observers\ClientCaseObserver;
use App\Observers\ClientObserver;
use App\Observers\GroupPermissionObserver;
use App\Observers\QuotationObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\GroupPermission;
use App\Policies\GroupPermissionPolicy;

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
        Client::observe(ClientObserver::class);
        ClientCase::observe(ClientCaseObserver::class);
        Group::observe(GroupPermissionObserver::class);
        Quotation::observe(QuotationObserver::class);
        // Register the policies
        Gate::policy(GroupPermission::class, GroupPermissionPolicy::class);
    }
}
