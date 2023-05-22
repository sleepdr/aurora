<?php

namespace aurora\world\protocol;

// parses incoming packets from the client
class protocol {
    private $buffer = "";

    public function dump(string $data) {
        $this->buffer .= $data;
        $packets = array();
        
        if(substr($this->buffer, -1) == "\x00") {
            $raw_packets = explode("\x00", $this->buffer);
            $this->buffer = "";

            foreach($raw_packets as $packet) {
                try {
                    if(strlen($packet) == 0) 
                        continue;

                    $packet = new packet( $packet );
                    $packet->validate();
                    $packet->parse();
                    $packet->valid && array_push($packets, $packet);
                    
                } catch(object $error) {
                    print("aurora @ received invalid packet\n$error\n");
                    return;
                }
            }
        }

        return $packets;
    }
}