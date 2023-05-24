<?php

namespace aurora\plugins;

use aurora;

class penguin extends aurora\world\plugins\plugin {
    public function __construct(& $world) {
        parent::__construct($world);
        // not implemented
        $this->add_listener("s#bl", "buddy_list");
        $this->add_listener("s#nl", "ignore_list");
        $this->add_listener("s#il", "item_list");
        $this->add_listener("s#h", "heartbeat");
    }

    public function buddy_list($penguin, $packet) {
        $penguin->write_xt("bl", "-1");
    }

    public function ignore_list($penguin, $packet) {
        $penguin->write_xt("nl", "-1");
    }

    public function item_list($penguin, $packet) {
        $penguin->write_xt("il", "-1");
    }

    public function heartbeat($penguin, $packet) {
        $penguin->write_xt("h", "-1");
    }
}