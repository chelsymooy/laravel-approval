<?php

namespace Chelsymooy\Approval\Providers;

use Illuminate\Support\ServiceProvider;

use Chelsymooy\Approval\Models\Inbox;

class ApprovalServiceProvider extends ServiceProvider {

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        // REGISTER CONFIG
        $this->publishes([
            __DIR__.'/../../config/approval.php' => config_path('approval.php'),
        ]);

        // REGISTER MIGRATION
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        // REGISTER OBSERVER
        // \Chelsymooy\Approval\Models\Table::observe(new \Chelsymooy\Approval\Observers\SetApproval);
        // \Chelsymooy\Approval\Models\Table::observe(new \Chelsymooy\Approval\Observers\SendToInboxFromTable);
        \Chelsymooy\Approval\Models\Inbox::observe(new \Chelsymooy\Approval\Observers\Approve);
        \Chelsymooy\Approval\Models\Inbox::observe(new \Chelsymooy\Approval\Observers\Decline);
    }
}
