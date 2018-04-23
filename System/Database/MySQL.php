<?php

namespace System\Database;

use System\Utils\StringExt;

class MySQL
{
    /** 
     * @var \mysqli 
     */
    private static $conn;
    private static $prefix;
    

    public static function Connect($host, $username, $password, $database, $prefix = "") 
    {
        self::$conn = new \mysqli($host, $username, $password, $database);
        self::$prefix = $prefix;
    }
    
    public static function Disconnect() 
    {
        self::$conn->close();
    }
    
    public static function GetRow($table, $columns, $arg = "") 
    {
        $result = self::GetRows($table, $columns, $arg);
        
        if ($result && count($result) > 0) {
            return $result[0];
        }
        return false;
    }
    
    public static function GetRows($table, $columns, $arg = "") 
    {
        
        for ($i = 0; $i < count($columns); $i++) 
        {
            $columns[$i] = self::$conn->real_escape_string($columns[$i]);
        }
        
        $columnsStr = "`". implode("`, `", $columns)."`";
        $cmd = "SELECT {1} FROM {0}{2};";
        
        $cmd = StringExt::Format($cmd, $table, $columnsStr, $arg);
        
        return self::Query($cmd);
//        self::Insert($table, $values = array("Column1" => "Hej", "Column2" => "1"));
    }
        
    public static function Insert($table, $values) 
    {
        
//        
//        $values = array(
//            "Column1" => "Value",
//            "Column2" => 1
//        );
//        
        
        $valuesR = array();
        
        foreach ($values as $key => $value) {
            if (is_int($value)) {
                $valuesR[] = $value;
            }
            else {
                $valuesR[] = "'". self::$conn->real_escape_string($value). "'";
            }
        }
//        echo "\n".'$values'."\n";
//        print_r($values);  
//        echo '$valuesR'."\n";
//        print_r($valuesR);

        // `Column1`, `Column2`
        //  Column1`, `Column2
        $columns = array_keys($values);
        $columnsStr = "`". implode("`, `", $columns). "`";
        
        $valuesStr = implode(", ", $valuesR);
        $cmd = "INSERT INTO {0} ({1}) VALUES ({2})";
        $cmd = StringExt::Format($cmd, $table, $columnsStr, $valuesStr);
        
        self::Query($cmd);
    }

    public static function Update($table, $values, $arg) 
    {
        
        //"UPDATE persons SET email='peterparker_new@mail.com', col=val"
        $valuesR = array();
        
        foreach ($values as $column => $value) {
            if (!is_int($valuesR)) {
                $value = "'". self::$conn->real_escape_string($value). "'";
            }
            
            
            $valuesR[] = $column. "=". $value;
        }
        
        $columns = implode(", ", $valuesR);
        
        $cmd = "UPDATE {0} SET {1} {2}";
        $cmd = StringExt::Format($cmd, $table, $columns, $arg);
        
        self::Query($cmd);
    }

    public static function Delete() {}

    public static function Exist() {}

    public static function Create() {}

    public static function Query($cmd) 
    {
        $result = self::$conn->query($cmd);
        
        if ($result) {
            return $result->fetch_all();
        }
        
        return false;
    }


    
}
