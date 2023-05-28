<?php

namespace aurora\plugins;
use aurora;

class minigames extends aurora\world\plugins\plugin {
    // games that reward coins equal to the score
    private static $score_games = array("904", "905", "906", "912", "916", "917", "918", "919", "950", "952");
    private static $max_coins = 1000;

    public function __construct($world) {
        parent::__construct($world);
        
        $this->add_listener("z#zo", "game_over");
        $this->add_listener("s#ac", "add_coins");
    }

    public function game_over($penguin, $packet) {
        if( $penguin->room && $penguin->room->is_game ) {
            $score = $packet->value[1] ?? 0;
            $coins = array_search($penguin->room->id, self::$score_games) ?
                floor( $score ) :
                floor( $score / 10 );
            //
            //
            if( $coins > self::$max_coins )
                return;

            $penguin->coins_earned = $coins;
            $penguin->write_xt("zo", "-1");
        }
    }

    public function add_coins($penguin, $packet) {
        if( $penguin->room && $penguin->room->is_game ) {
            $penguin->data["coins"] += $penguin->coins_earned;
            $this->world->database->update_coins( $penguin->data["id"], $penguin->data["coins"] );
            // reset coins for future games
            $penguin->coins_earned = 0;
            $penguin->write_xt( "ac", "-1", $penguin->data["coins"] );
        }
    }
}