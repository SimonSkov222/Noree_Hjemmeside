<?php
namespace System\Networking;

use System\Utils\StringExt;

Routing::Start();

class Routing
{
    private static $routes;
    
    public static function Start()
    {
        echo 'Routing...';
        
        $uri = self::GetUri();
        self::$routes = self::GetAllRoutes();
        echo "myRoute: " .self::GetRoute($uri)."\n";
        
    }
    
    private static function GetUri()
    {
        $uri = filter_input(INPUT_GET, "Route");
        if ($uri == "") 
        {
            $uri = "/Home";
        }
        
        return $uri;
    }
    
    private static function GetLang() {}
    
    private static function GetRoute($uri) 
    {
        $routeKeys = array_keys(self::$routes);
        
        print_r($routeKeys);
        
        $parts = explode("/", $uri);
        $route = "";
        $check = "";
        
        foreach ($parts as $part) 
        {
            if ($part = "") { continue; }
            $check .= strtolower("/$part");
            
            for ($i = count($routeKeys); $i >= 0; $i--)
            {
                $keyLow = strtolower($routeKeys[$i]);
                
                if (!StringExt::StartsWith($keyLow, $check))
                {
                    unset($routeKeys[$i]);
                }
                
                if ($keyLow == $check)
                {
                    $route = $routeKeys[$i];
                }
            }
        }
        
        if ($route = "") 
        {
            $route = "/NotFound";
        }
        
        return $route;
    }
    
    private static function GetAllRoutes() 
    {
        $content = file_get_contents(__DIR__."/Routes.json");
        $json = json_decode($content, true);
        
        print_r($json);
        
        return $json;
    }
    
    
}