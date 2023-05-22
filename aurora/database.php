<?php

namespace aurora;

class database extends \mysqli {
    private static $database = "aurora";
    private static $username = "root";
    private static $password = "";
    private static $host = "127.0.0.1";

    public function __construct() {
        parent::__construct(
            self::$host,
            self::$username,
            self::$password,
            self::$database
        );

        $this->bootstrap();
    }

    private function bootstrap() {
        $this->multi_query(file_get_contents("aurora.sql"));
        print("aurora @ initialised database " . self::$database . "\n");
    }
}