<?php
/*
	GoodCoder 2018
*/
function debug($var = Null, $exit = true){echo '<pre>'; var_dump($var); echo '</pre>'; if ($exit) exit();}

define('DS', DIRECTORY_SEPARATOR);

define('GC_ROOT', dirname(__FILE__).DS);

define('GC_APIS_FOLDER', dirname(GC_ROOT).DS.'apis'.DS);



?>