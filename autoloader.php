<?php

function loadClass($classname){
    //add absolute path to class directory
    $root = $_SERVER["DOCUMENT_ROOT"];
    $classdir = 'classes';
    $classfile = strtolower($classname) . '.class.php';
    //include the file from directory
    include($root . '/' . $classdir . '/' . $classfile);
}

//register functions with php auto loader
spl_autoload_register('loadClass');


?>