<?php

namespace aurora\world;

use aurora;

class world extends aurora\server {
    private $penguins = array();
    private $plugins;

    public $database;
    public $rooms;

    public function __construct($host, $port) {
        $this->rooms = new aurora\world\rooms\manager( $this );
        $this->plugins = new aurora\world\plugins\manager( $this );
        $this->database = new aurora\database;
        
        parent::__construct($host, $port);
    }

    public function find_username($username) {
        foreach($this->penguins as & $penguin) {
            if($penguin->data && !strcasecmp($penguin->data["username"], $username)) {
                return $penguin;
            }
        }
    }

    private function find_socket($socket) {
        foreach($this->penguins as & $penguin) {
            if($penguin->socket == $socket) return $penguin;
        }
    }

    private function handle_packet($penguin, $packet) {
        print("aurora @ $packet->buffer\n");

        if($packet->buffer == "<policy-file-request/>") {
            $penguin->write("<cross-domain-policy><allow-access-from domain='*' to-ports='*'/></cross-domain-policy>");
            return;
        }

        $this->plugins->handle_packet($penguin, $packet);
    }

    protected function handle_accept($socket) {
        array_push($this->penguins, new penguin($this, $socket));
    }

    protected function handle_close($socket) {
        $penguin = $this->find_socket($socket);
        $penguin->room && $penguin->room->remove_penguin($penguin);
        array_splice($this->penguins, array_search($penguin, $this->penguins), 1);
    }

    protected function handle_read($socket, $data) {
        $penguin = $this->find_socket($socket);
        $packets = $penguin->protocol->dump($data);
        
        foreach($packets as & $packet) {
            $this->handle_packet($penguin, $packet);
        }
    }
}