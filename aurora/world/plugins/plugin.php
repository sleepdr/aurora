<?php

namespace aurora\world\plugins;

class plugin {
    // listeners for each packet type
    public $packets = array();
    public $world;

    public function __construct(& $world) {
        $this->world = $world;
    }
    
    public function add_listener($packet_type, $callback) {
        $this->packets[ $packet_type ] = $callback;
    }

    public function has_listener($packet_type) {
        return array_key_exists($packet_type, $this->packets);
    }
}