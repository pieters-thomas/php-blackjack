<?php

use JetBrains\PhpStorm\Pure;

class Blackjack
{
    private Player $player;
    private Dealer $dealer;
    private Deck $deck;

    public function __construct()
    {
        $deck = new Deck();
        $deck->shuffle();
        $this->deck = $deck;
        
        $this->player = new Player($deck);
        $this->dealer = new Dealer($deck);

    }

    public function getPlayer():object
    {
        return $this->player;
    }

    public function getDealer():object
    {
        return $this->dealer;
    }

    public function getDeck():object
    {
        return $this->deck;
    }
}