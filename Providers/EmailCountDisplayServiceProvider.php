<?php

namespace Modules\EmailCountDisplay\Providers;

use Illuminate\Support\ServiceProvider;

class EmailCountDisplayServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Hook into the folder.count filter to modify how counts are displayed
        \Eventy::addFilter('folder.count', function ($count, $folder, $counter, $folders) {

            // Only apply to TYPE_MINE folder
            if ($folder->type == \App\Folder::TYPE_MINE) {

                $pending_count = $folder->total_count - $folder->active_count;

                // Return combined active/total format
                if ($pending_count > 0) {
                    return $folder->active_count . '/' . $pending_count;
                }

                return $folder->active_count;
            }

            // Return false to let other filters/default logic handle other folder types
            return false;
        }, 10, 4);
    }

    public function register()
    {
    }

    /**
     * Module hooks.
     */
    public function hooks()
    {
        \Eventy::addFilter('javascripts', function ($javascripts) {
            $javascripts[] = \Module::getPublicPath('sidebarwebhook') . '/js/module.js';

            return $javascripts;
        });
    }
}
