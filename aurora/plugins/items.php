<?php

namespace aurora\plugins;
use aurora;

class items extends aurora\world\plugins\plugin {
    // handles purchasing of items and updates penguin clothing
    public $crumbs;
    public $items;
    
    public function __construct(& $world) {
        parent::__construct($world);

        $this->add_listener("s#ai", "add_item");
        $this->add_listener("s#up", "update_penguin");

        $this->crumbs = json_decode(file_get_contents("crumbs/items.json"));
        $this->items = array(
            "colour",
            "head",
            "face",
            "neck",
            "body",
            "hands",
            "feet",
            "flag",
            "photo",
        );
    }

    public function add_item($penguin, $packet) {
        $item_id = (int) ($packet->value[1] ?? null);
        $item = $this->crumbs->$item_id ?? null;

        if(array_search($item_id, $penguin->items) !== false)
            return $penguin->write_xt("e", "-1", "400");
        
        if(is_null($item))
            return $penguin->write_xt("e", "-1", "402");

        if($penguin->data["coins"] < $item->cost)
            return $penguin->write_xt("e", "-1", "401");

        $penguin->data["coins"] -= $item->cost;

        $this->world->database->create_item( $penguin->data["id"], $item_id );
        $this->world->database->update_coins( $penguin->data["id"], $penguin->data["coins"] );
        //
        $penguin->write_xt( "ai", "-1", $item_id, $penguin->data["coins"] );
    }

    public function update_penguin($penguin, $packet) {
        $new_items = array_slice($packet->value, 1);

        if(count($new_items) == count($this->items)) {
            foreach($new_items as $i => $item_id) {
                if(in_array($item_id, $penguin->items) or $item_id == 0) {
                    $item_type = $this->items[ $i ];
                    $penguin->data[ $item_type ] = $item_id;
                    
                    $this->world->database->update_item($penguin->data["id"], $item_type, $item_id);
                }
            }
        }

        $penguin->room->broadcast("up", $penguin->room->internal, $penguin->to_string());
    }
}