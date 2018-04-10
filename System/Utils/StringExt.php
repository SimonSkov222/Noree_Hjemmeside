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


//    $var = "Hello World";
//    $startWith = "Hello";
    
}