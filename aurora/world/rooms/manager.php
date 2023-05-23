<?php

namespace aurora\world\rooms;

class manager {
    private $rooms = array();
    private $crumbs;
    private $world;

    public function __construct(& $world) {
        // pretty self explanatory
        $this->world = $world;
        $this->crumbs = json_decode(file_get_contents("crumbs/rooms.json"));
        
        foreach($this->crumbs as $id => & $crumb) {
            array_push($this->rooms, new room($world, $id, $crumb));
        }
    }

    public function get( $room_id ) {
        // there is probbaly an array function for this
        foreach($this->rooms as & $room) {
            if($room->id == $room_id) return $room;
        }
    }
}