<?php

namespace aurora\world;

class penguin {
    public $protocol;
    public $socket;
    public $world;
    public $items;
    public $data;

    // game crumbs
    public $x;
    public $y;
    public $frame;
    public $coins_earned;
    //

    public function __construct(world $world, $socket) {
        $this->protocol = new protocol\protocol;
        $this->socket = $socket;
        $this->world = $world;
    }

    public function write($buffer) {
        socket_write($this->socket, $buffer . "\x00");
    }

    public function to_string() {
        return implode("|", array(
            $this->data->id,
            $this->data->username,
            $this->data->colour,
            $this->data->head,
            $this->data->face,
            $this->data->neck,
            $this->data->body,
            $this->data->hands,
            $this->data->feet,
            $this->data->flag,
            $this->data->photo,
            $this->x,
            $this->y,
            $this->frame,
            "1",
            "0",
        ));
    }
}