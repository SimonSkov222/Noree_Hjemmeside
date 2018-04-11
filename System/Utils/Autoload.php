<?php

spl_autoload_register(function ($class_name)
{
    //echo $class_name . ".php\n";
        include $class_name . '.php';
    
});


