<?php

namespace aurora\plugins;
use aurora;

class actions extends aurora\world\plugins\plugin {
    public function __construct(& $world) {
        parent::__construct($world);
        
        $this->add_listener("s#sp", "send_position");
        $this->add_listener("s#sf", "send_frame");
        $this->add_listener("s#sb", "snowball");
    }

    public function send_position($penguin, $packet) {
        if(is_null( $penguin->room )) return;

        $penguin->x = (int) ($packet->value[1] ?? 0);
        $penguin->y = (int) ($packet->value[2] ?? 0);
        $penguin->frame = 0;
        $penguin->room->broadcast("sp", $penguin->room->internal, $penguin->data["id"], $penguin->x, $penguin->y);
    }

    public function send_frame($penguin, $packet) {
        if(is_null( $penguin->room )) return;
        
        $frame = (int) ($packet->value[1] ?? 0);
        $penguin->frame = $frame;
        $penguin->room->broadcast("sf", $penguin->room->internal, $penguin->data["id"], $frame);
    }

    public function snowball($penguin, $packet) {
        if(is_null( $penguin->room )) return;

        $id = $penguin->data["id"];
        $x = (int) ($packet->value[1] ?? 0);
        $y = (int) ($packet->value[2] ?? 0);
        $penguin->room->broadcast("sb", $penguin->room->internal, $id, $x, $y);
    }
}