<?php

namespace App\Nova\Dashboards;

use Laravel\Nova\Cards\Help;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{


    public function label()
    {
        return 'Dashboard';
    }
        /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
        new UserInsights,
        ];
    }
}
