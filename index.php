<?php
header('Content-Type: application/javascript');

echo "ss\n";


require_once __DIR__."/System/Utils/Autoload.php";

System\Utils\StringExt::Test();
System\Database\MySQL::Update("MinTable", array("Name" => "Kim", "Age" => 12));
