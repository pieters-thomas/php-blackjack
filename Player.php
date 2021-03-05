<?php


class Player
{
    private array $cards;
    private bool $lost = false;
    private bool $blackjackRule = false;
    private const BREAK = 21;

    public function __construct($deck)
    {
        $this->cards[] = $deck->drawCard();
        $this->cards[] = $deck->drawCard();

        if ($this->getScore() === self::BREAK){
            $this->blackjackRule = true;
        }

    }

    public function hit($deck): void
    {
        $this->cards[] = $deck->drawCard();

        if ($this->getScore() > self::BREAK) {
            $this->lost = true;
        }
    }

    public function surrender(): void
    {
        $this->lost = true;
    }

    public function getScore(): int
    {
        $totalScore = 0;
        foreach ($this->cards as $card) {
            $totalScore += $card->getValue();
        }
        return $totalScore;
    }

    public function hasLost(): bool
    {
        if ($this->getScore() > self::BREAK){ $this->lost = true;}
        return $this->lost;
    }

    public function showHand( $show): void
    {
        if($show === 'hand'){
            foreach ($this->cards as $card) {
                echo $card->getUnicodeCharacter(true);
        }}elseif($show === 'first'){
            echo $this->cards[0]->getUnicodeCharacter(true);
            }
        }

    public function getBreak(): int
    {
        return self::BREAK;

    } public function blackjackRule()
    {
        return $this->blackjackRule;
    }


}

class Dealer extends Player
{

    private const DEALER_BREAK = 15;

    public function runDealer($deck): void
    {
        if ($this->getScore() < self::DEALER_BREAK) {
            $this->hit($deck);
            $this->runDealer($deck);
        }
    }
}