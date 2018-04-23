<?php

namespace Content\Controllers;

use System\Utils\ControllerBasic;

class HomeController extends ControllerBasic
{
    public function Home() 
    {
        $this->View("/Home");
    }
    
}

