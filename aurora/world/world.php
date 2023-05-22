<?php

namespace aurora\world;

use aurora;

class world extends aurora\server {
    private $penguins = array();

    private function find_penguin($socket) {
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
    }

    protected function handle_accept($socket) {
        array_push($this->penguins, new penguin($this, $socket));
    }

    protected function handle_close($socket) {
        $penguin = $this->find_penguin($socket);
    }

    protected function handle_read($socket, $data) {
        $penguin = $this->find_penguin($socket);
        $packets = $penguin->protocol->dump($data);
        
        foreach($packets as & $packet) {
            $this->handle_packet($penguin, $packet);
        }
    }
}