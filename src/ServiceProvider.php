<?php

namespace Yasaie\Tracker;

/**
 * Class    ServiceProvider
 *
 * @author  Payam Yasaie <payam@yasaie.ir>
 * @since   2019-08-19
 *
 * @package Yasaie\Tracker
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (!class_exists('CreateDictionariesTable')) {
            $table_file = 'create_trackers_table.php';
            $this->publishes([
                __DIR__ . '/../database/migrations/' . $table_file => database_path('migrations/' . date('Y_m_d_His', time()) . '_' . $table_file),
            ], 'migrations');
        }
    }
}
