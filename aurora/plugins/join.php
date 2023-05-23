<?php

namespace aurora\plugins;

use aurora;

class join extends aurora\world\plugins\plugin {
    // handles joining the server or different rooms
    public function __construct(& $world) {
        parent::__construct($world);
        
        $this->add_listener("s#js", "join_server");
        $this->add_listener("s#jr", "join_room");
    }

    public function join_server($penguin, $packet) {
        $rooms = array("100", "300", "800", "804");
        $id = array_rand($rooms);
        
        $penguin->write_xt("js", "-1", "-1", "1", "0", "0");
        $penguin->room = $this->world->rooms->get( $rooms[$id] );
        $penguin->room->add_penguin($penguin);
    }

    public function join_room($penguin, $packet) {
        $penguin->room && $penguin->room->remove_penguin($penguin);
        
        $penguin->room = $this->world->rooms->get( (int) ( $packet->value[1] ?? 0 ) );
        
        if($penguin->room) {
            $penguin->x = (int) ( $packet->value[2] ?? 0 );
            $penguin->y = (int) ( $packet->value[3] ?? 0 );
            $penguin->frame = 0;

            $penguin->room->add_penguin($penguin);
        }
    }
}