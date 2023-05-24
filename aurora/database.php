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

        $this->multi_query(file_get_contents("aurora.sql"));

        while($this->next_result() && $result = $this->store_result())
            $result->free();

        print("aurora @ initialised database " . self::$database . "\n");
    }

    public function authenticate($username, $login_key) {
        $statement = $this->prepare("select * from penguins where username = ?");
        $statement->bind_param("s", $username);
        $statement->execute();

        $result = $statement->get_result();
        $penguin = $result->fetch_assoc();
        
        if($result->num_rows == 0)
            return null;

        if( $penguin["login_key"] == $login_key ) {
            // for security reasons i guess....
            $this->refresh_key( $username );
            return $penguin;
        }
    }

    public function refresh_key($username) {
        // login key must be 15 characters long
        $login_key = substr(bin2hex(random_bytes(8)), 1);
        $statement = $this->prepare("update penguins set login_key = ? where username = ?");
        $statement->bind_param("ss", $login_key, $username);
        $statement->execute();
    }

    public function update_coins($penguin_id, $coins) {
        $statement = $this->prepare("update penguins set coins = ? where id = ?");
        $statement->bind_param("ii", $coins, $penguin_id);
        $statement->execute();
    }

    public function find_items($id) {
        $statement = $this->prepare("select * from items where penguin_id = ?");
        $statement->bind_param("i", $id);
        $statement->execute();
        
        return array_column($statement->get_result()->fetch_all(), 1);
    }

    public function create_item($penguin_id, $item_id) {
        $statement = $this->prepare("insert into items (penguin_id, item_id) values (?, ?)");
        $statement->bind_param("ii", $penguin_id, $item_id);
        $statement->execute();
    }

    public function update_item($penguin_id, $item_type, $item_id) {
        $statement = $this->prepare("update penguins set $item_type = ? where id = ?");
        $statement->bind_param("ii", $item_id, $penguin_id);
        $statement->execute();
    }
}