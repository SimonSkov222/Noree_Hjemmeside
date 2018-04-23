<?php
namespace System\Database;

class MySQLColumn{
    
    const NOT_NULL = 2;
    const PRIMARY_KEY = 4;
    const UNIQUE = 8;
    const AUTO_INCREMENT = 16;


    private $name;
    private $type;
    private $flags;
    private $foreignKey;


    public function __construct($name, $type, $size = NULL, $flags = 0, $foreignKeyTable = NULL, $foreignKeyColumn = NULL) {
        $this->SetName($name);
        $this->SetType($type, $size);
        $this->SetFlags($flags);
        $this->SetForeignKey($foreignKeyTable, $foreignKeyColumn);
        
        
        
        echo "flag:: {$flags} \n";
    }
    
    public function GetName(){ return $this->name; }
    
    public function SetName($value){ $this->name = $value; return $this;}
    
    public function GetType(){ return $this->type; }
    
    public function SetType($type, $size) { 
        $this->type = $type;
        if ($size > 0) {
            $this->type.= "($size)";
        }
        return $this;
    }
    
    public function HasFlags($flag){
        return ($this->flags & $flag) == $flag;
    }
    
    public function SetFlags($args){
        $params = func_get_args();
        $this->flags = 0;
        foreach ($params as $value) {
            $this->flags += $value;
        }
        return $this;
    }

    public function GetForeignKey(){ return $this->foreignKey;}
    
    public function SetForeignKey($table, $column) {
        if (!is_null($table) && !is_null($column)) {
            $this->foreignKey = "$table($column)";
        }
        return $this;
    }
    
    
}

