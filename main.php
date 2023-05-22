<?php

namespace aurora;

spl_autoload_register(function( $class ) {
    $path = join("\\", array(__DIR__, "$class.php"));
    file_exists($path) && include $path;
});

$database = new database();
$blizzard = new world\world("127.0.0.1", "6114");