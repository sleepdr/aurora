<?php

namespace aurora\world\rooms;

class room {
    public $world;
    public $penguins;
    // room data
    public $id;
    public $name;
    public $internal;
    public $is_game;
    
    public function __construct(& $world, $id, $crumb) {
        $this->world = $world;
        $this->penguins = (array) null;

        $this->id = $id;
        $this->name = $crumb->name;
        $this->internal = $crumb->internal;
        $this->is_game = $crumb->game == "true";
    }

    public function broadcast(...$data) {
        foreach($this->penguins as & $penguin) {
            $penguin->write_xt(...$data);
        }
    }

    public function add_penguin(& $penguin) {
        if($this->is_game)
            return $penguin->send_xt("jg", $this->internal, $this->id);

        array_push($this->penguins, $penguin);

        $penguin->write_xt("jr", $this->internal, $this->id, $this->to_string());
        $this->broadcast("ap", $this->internal, $penguin->to_string());
    }

    public function remove_penguin(& $penguin) {
        $this->broadcast( "rp", $this->internal, $penguin->data["id"] );
        array_splice($this->penguins, array_search($penguin, $this->penguins), 1);
    }

    public function to_string() {
        return implode("%", array_map(function($penguin) {
            return $penguin->to_string();
        }, $this->penguins));
    }
}