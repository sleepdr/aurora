<?php

namespace aurora;

// basic tcp server
abstract class server {
    public $port;
    public $host;
    public $socket;
    public $sockets;

    public function __construct( $host, $port ) {
        $this->host = $host;
        $this->port = $port;
        $this->socket = null;
        $this->sockets = (array) null;
        //
        $this->create_socket();
        $this->listen();
    }

    // create the server socket
    private function create_socket() {
        $this->socket = socket_create(2, 1, 6) or $this->socket_error();

        socket_bind($this->socket, $this->host, $this->port) or $this->socket_error();
        socket_listen($this->socket, 5) or $this->socket_error();

        ob_implicit_flush();
        set_time_limit(0);
    }

    // calls die after a socket error occurs
    private function socket_error() {
        die("aurora @ " . socket_strerror(socket_last_error()));
    }

    // io loop
    private function listen() {
        while(1) {
            $read = array_merge($this->sockets, array( $this->socket ));
            $write = null;
            $except = null;

            socket_select($read, $write, $except, null);
            
            foreach($read as & $socket) {
                if($socket == $this->socket) {
                    $client = socket_accept($socket);
                    $client && array_push($this->sockets, $client) && $this->handle_accept($client);
                    continue;
                }

                $data = socket_read($socket, 512);

                if($data) {
                    $this->handle_read($socket, $data);
                    continue;
                }

                $this->handle_close($socket);
                array_splice($this->sockets, array_search($socket, $this->sockets), 1);
            }
        }
    }

    // override these functions
    abstract protected function handle_close( $socket );
    abstract protected function handle_accept( $socket );
    abstract protected function handle_read( $socket, $data );
} 