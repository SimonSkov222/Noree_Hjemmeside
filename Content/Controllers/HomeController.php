<?php

namespace Content\Controllers;

use System\Utils\ControllerBasic;

class HomeController extends ControllerBasic
{
    public function Home() 
    {
        echo "Home";
        $this->View("/Home");
    }
}

