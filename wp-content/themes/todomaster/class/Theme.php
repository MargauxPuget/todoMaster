<?php

namespace mpuget\TodoMaster;

use mpuget\TodoMaster\TimberTheme;


class Theme
{
    const NAME = 'todomaster';

    public TimberTheme $timber;

    public function __construct()
    {
        // Check if Timber exist
        TimberTheme::checkForTimber();
    }
}
