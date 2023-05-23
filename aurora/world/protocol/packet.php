<?php

namespace aurora\world\protocol;

class packet {
    // raw data from the client
    public $buffer;
    public $valid;
    public $value;
    public $type;

    public function __construct(string $buffer) {
        $this->buffer = $buffer;
        $this->valid = false;
        $this->value = null;
        $this->type = null;
    }

    public function validate() {
        switch( @ $this->buffer[0] ) {
            case "%": $this->type = "xt"; break;
            case "<": $this->type = "xml"; break;
            default: $this->valid = false; break;
        }
    }

    public function parse() {
        if( $this->type == "xt" ) {
            $this->value = array_filter(explode("%", $this->buffer), "strlen");
            $this->type = $this->value[2] . "#" . $this->value[3];
            $this->value = array_splice($this->value, 3);
            $this->valid = true;
            return;
        }

        if( $this->type == "xml" ) {
            $this->valid = true;
            $this->value = simplexml_load_string($this->buffer);
            $this->type = (string) @ $this->value->body->attributes()->action;
        }
    }
}