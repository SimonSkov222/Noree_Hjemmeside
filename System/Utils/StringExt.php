<?php
namespace System\Utils;

class StringExt
{
    public static function Test() 
    {
         echo "StringExt::Test();\n\n";
         
         echo "heh";
    }
    
    public static function StartsWith($str, $check)
    {
        $len = strlen($check);
        
        if (strlen($str) < $len) 
        {
            return false;
        }
        
        $start = substr($str, 0, $len);
        
        return $start == $check;
    }
    
    public static function EndsWith($str, $check)
    {
        
        $len = strlen($str);
        $len2 = strlen($check);
        $end = substr($str, $len - $len2);
        
        return $end == $check;
    }

    public static function Format($str, $args) 
    {
        $params = func_get_args();
        
        
        for ($i = 1; $i < count($params); $i++) 
        {
            
            $param = preg_replace("/(?<!\/)\{\d+}/", "/$0", $params[$i]);
            $str = preg_replace("/(?<!\/)\{". ($i-1). "}/", $param, $str);
        }
        
        return $str;
    }    
}

//echo StringExt::Format("Hej {0} {1} {0}\n", "Kim {1} {1} {1}", "Leeee");
