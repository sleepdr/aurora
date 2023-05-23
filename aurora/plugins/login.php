<?php

namespace aurora\plugins;

use aurora;

class login extends aurora\world\plugins\plugin {
    // handles version checking and authentication
    public function __construct(& $world) {
        parent::__construct($world);
        // pascal case is so gross
        // lets just pretend its not here
        $this->add_listener("verChk", "version_check");
        $this->add_listener("login", "login");
    }

    public function version_check($penguin, $packet) {
        $version = @ $packet->value->body->ver->attributes()->v;
        $version == "097" && $penguin->write("<msg t='sys'><body action='apiOK' r='0'></body></msg>");
    }

    public function login($penguin, $packet) {
        $username = (string) @ $packet->value->body->login->nick;
        $login_key = (string) @ $packet->value->body->login->pword;
        $data = $this->world->database->authenticate($username, $login_key);
        
        if( $data == null )
            return $penguin->write_xt("e", "-1", "101");
        
        $penguin->data = $data;
        $penguin->items = $this->world->database->find_items( $data["id"] ); 
        $penguin->write_xt("l", "-1");
    }
}