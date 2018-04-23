<?php

namespace System\Utils;

class ControllerBasic
{


    protected function View($view) {
        global $G_Template_Data;
        
        ob_start();
        $filename = "Content/Views/".$view."View.php";
        require_once $filename;
        
        $G_Template_Data["Content"] = ob_get_contents();
        ob_end_clean();
    }
    
    public function __destruct() 
    {
        global $G_Template_Data;
        //print_r($_SERVER
        //
        $G_Template_Data["Debug"] = ob_get_contents();
        ob_end_clean();
        
        $template = __Root."/Content/Template".__Template."/".$G_Template_Data["Design"].".php";
        require_once $template;
        
    }
}