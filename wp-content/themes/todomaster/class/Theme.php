<?php

namespace MPuget\TodoMaster;

use MPuget\TodoMaster\TimberTheme;


class Theme
{
    const NAME = 'todomaster';

    public TimberTheme $timber;

    public function __construct()
    {
        // Check if Timber exist
        TimberTheme::checkForTimber();


        // Global
        var_dump('3');
        $this->timber = new TimberTheme();
    }
}
