<?php

namespace System\Database;

use System\Utils\StringExt;

class MySQL
{
    const LOGIC_AND = 1;   
    const LOGIC_OR = 0;



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
        $table = self::$prefix. $table;
        for ($i = 0; $i < count($columns); $i++) 
        {
            $columns[$i] = self::$conn->real_escape_string($columns[$i]);
        }
        
        $columnsStr = "`". implode("`, `", $columns)."`";
        $cmd = "SELECT {1} FROM {0} {2};";
        
        $cmd = StringExt::Format($cmd, $table, $columnsStr, $arg);
        
        return self::Query($cmd);
//        self::Insert($table, $values = array("Column1" => "Hej", "Column2" => "1"));
    }
        
    public static function Insert($table, $values) 
    {
        $table = self::$prefix. $table;
        $valuesR = array();
        
        foreach ($values as $value) {
            if (is_int($value)) {
                $valuesR[] = $value;
            }
            else {
                $valuesR[] = "'". self::$conn->real_escape_string($value). "'";
            }
        }
        
        $columns = array_keys($values);
        $columnsStr = "`". implode("`, `", $columns). "`";
        
        $valuesStr = implode(", ", $valuesR);
        $cmd = "INSERT INTO {0} ({1}) VALUES ({2})";
        $cmd = StringExt::Format($cmd, $table, $columnsStr, $valuesStr);
        
        self::Query($cmd);
    }

    public static function Update($table, $values, $arg) 
    {
        $table = self::$prefix. $table;
        //"UPDATE persons SET email='peterparker_new@mail.com', col=val"
        $valuesR = array();
        
        foreach ($values as $column => $value) {
            if (!is_int($valuesR)) {
                $value = "'". self::$conn->real_escape_string($value). "'";
            }
            
            
            $valuesR[] = "`{$column}` = {$value}";
        }
        
        $columns = implode(", ", $valuesR);
        
        $cmd = "UPDATE {0} SET {1} {2}";
        $cmd = StringExt::Format($cmd, $table, $columns, $arg);
        
        self::Query($cmd);
    }

    public static function Delete($table, $where, $logic = self::LOGIC_AND) 
    {
        $table = self::$prefix. $table;
        $valuesR = array();
        
        foreach ($where as $column => $value) {
            if (!is_int($valuesR)) {
                $value = "'". self::$conn->real_escape_string($value). "'";
            }
            
            
            $valuesR[] = "`$column` = $value";
        }

        $columns = implode($logic === self::LOGIC_OR ? " OR " : " AND ", $valuesR);
        
        
        
        $cmd = "DELETE FROM {0} WHERE {1}";
        $cmd = StringExt::Format($cmd, $table, $columns);
        
        self::Query($cmd);

//         Delete(navn, array("id"=>3, "ad"="lol"))      
//        "DELETE FROM MyGuests WHERE id=3 OR id=12 AND navn=Simon"
    }

    public static function Exist($table, $values) 
    {
        $table = self::$prefix. $table;
        $valuesR = array();
        
        foreach ($values as $column => $value) {
            if (!is_int($valuesR)) {
                $value = "'". self::$conn->real_escape_string($value). "'";
            }
            
            
            $valuesR[] = "`$column` = $value";
        }
        
        $columns = implode(" AND ", $valuesR);
        
        $cmd = "SELECT COUNT(*) FROM {0} WHERE {1}";
        $cmd = StringExt::Format($cmd, $table, $columns);
        
        $data = self::Query($cmd);
        
        return is_array($data) && $data[0][0] > 0;
//        SELECT COUNT(*) AS total FROM table1 WHERE ID=12 AND NAme=Simon
    }

    public static function Create($table, $columns)
    {
        $table = self::$prefix. $table;
        
        $values = array();
        $unique = array();
        $primaryKey = NULL;
        $foreignKey = array();
        
        foreach ($columns as $column) {
           // $column = new MySQLColumn();
            
            $str = "`{$column->GetName()}` {$column->GetType()}";
            
            if ($column->HasFlags(MySQLColumn::NOT_NULL)) {
                $str .= " NOT NULL";
            }
            if ($column->HasFlags(MySQLColumn::PRIMARY_KEY)) {
                $primaryKey = " PRIMARY KEY ({$column->GetName()})";
            }
            if ($column->HasFlags(MySQLColumn::UNIQUE)) {
                $unique[] = " UNIQUE ({$column->GetName()})";
            }
            if ($column->HasFlags(MySQLColumn::AUTO_INCREMENT)) {
                $str .= " AUTO_INCREMENT";
            }
            if ($column->GetForeignKey()) {
                $foreignKey[] = "FOREIGN KEY ({$column->GetName()}) REFERENCES {$column->GetForeignKey()}";
            }
            $values[] = $str;
        }
        $merge = array_merge($values, array($primaryKey), $unique, $foreignKey);
        $merge = array_filter($merge, function($var){return !is_null($var);} );
        
        
        $cmd = "CREATE TABLE {0} (\n   {1}\n);";
        $cmd = StringExt::Format($cmd, $table, implode(",\n   ", $merge));
        
        $data = self::Query($cmd);
    }

    public static function Query($cmd) 
    {
        $result = self::$conn->query($cmd);
        print_r(self::$conn->errno."\n");        
        print_r(self::$conn->error."\n");        
        print_r($cmd."\n");



        if ($result && !is_bool($result)) {
            //$result = new \mysqli_result();
            $data = array();
            while ($row = $result->fetch_array(\MYSQLI_NUM)) {
                $data[] = $row;
            }
            
            return $data;
        }
        
        return false;
    }


    
}
