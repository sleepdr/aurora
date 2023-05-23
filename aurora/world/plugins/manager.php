<?php

namespace aurora\world\plugins;

class manager {
    private $plugins = array();
    private $world;

    public function __construct(& $world) {
        $this->world = $world;
        
        $dir = opendir("aurora\\plugins");
  
        while($file = readdir($dir)) {
            if(str_ends_with($file, ".php") == false) continue;

            $name = explode(".", $file)[0];
            $plugin = "aurora\\plugins\\" . $name;

            array_push($this->plugins, new $plugin($world));
            print("aurora @ loaded plugin $name\n");
        }
    }

    public function handle_packet($penguin, $packet) {
        $type = $packet->type;
        $plugin = $this->find_plugin($packet->type);
        
        if($plugin == null)
            return print("aurora @ unhandled packet $type\n");

        $listener = $plugin->packets[ $type ];
        $plugin->$listener($penguin, $packet);
    }

    private function find_plugin($packet_type) {
        foreach($this->plugins as & $plugin) {
            if($plugin->has_listener($packet_type)) return $plugin;
        }
    }
}