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
        $route = self::GetRoute($uri);
        
        self::CallRoute($route);
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
        print_r($parts);
        
        $route = "";
        $check = "";
        
        foreach ($parts as $part) 
        {
            if (trim($part) == "") { continue; }
            $check .= strtolower("/$part");
            
            for ($i = count($routeKeys)-1; $i >= 0; $i--)
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
        if ($route == "") 
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
    
    private static function CallRoute($route)
    {
        $split = explode("::", self::$routes[$route]);
        
        $namespace  = "Content/Controllers";
        $classname = $split[0];
        $method = $split[1];
        
        $controllerName = str_replace("/", "\\", $namespace.$classname);
        $controlller = new $controllerName();
        
        $controlller->$method();
    }
    
    
}