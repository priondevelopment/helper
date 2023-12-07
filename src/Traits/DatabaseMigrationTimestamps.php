<?php

namespace PrionDevelopment\Helper\Traits;

use Illuminate\Database\Schema\Blueprint;

trait DatabaseMigrationTimestamps
{
    public function timestamps(Blueprint &$table)
    {
        if (isDatabaseDriver('sqlite')) {
            $table->timestamps();
        } else {
            $table->timestamp('created_at')->default(app('db')->raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(app('db')->raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        }
    }
}