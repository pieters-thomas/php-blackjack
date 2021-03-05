<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require 'Suit.php';
require 'Card.php';
require 'Deck.php';
require 'Player.php';
require 'Blackjack.php';

session_start();
const MIN_BET = 5;
const CHIPS_MULTI = 2;
const TWENTY_ONE = 21;
const BLACKJACK_WIN = 10;
const BLACKJACK_LOSS = -5;

function checkBlackjack()
{

    $blackJackPlayer = $_SESSION['blackjack']->getPlayer()->blackjackRule();
    $blackJackDealer = $_SESSION['blackjack']->getDealer()->blackjackRule();

    if ($blackJackPlayer || $blackJackDealer) {

        if ($blackJackPlayer && $blackJackDealer) {
            echo '<div class="alert-warning top" role="alert">';
            echo "Blackjack Tie!";
            echo '</div>';
        } elseif ($blackJackPlayer) {
            echo '<div class="alert-warning top" role="alert">';
            echo "Blackjack Player!";
            echo '</div>';
            $_SESSION['chips'] += BLACKJACK_WIN;
        } elseif ($blackJackDealer) {
            echo '<div class="alert-warning top" role="alert">';
            echo "Blackjack Dealer!";
            echo '</div>';
            $_SESSION['chips'] += BLACKJACK_LOSS;
        }
        $_SESSION['gameOver'] = true;
        unset($_SESSION['blackjack']);

    }
}

function checkOutcome($player, $dealer)
{
    if ($dealer->getScore() <= TWENTY_ONE && $player->getScore() <= $dealer->getScore()) {
        $player->surrender();
    }
    if ($player->getScore() <= TWENTY_ONE && $player->getScore() > $dealer->getScore()) {
        $dealer->surrender();
    }

    if ($player->hasLost()) {
        $html = '<div class="alert-warning top" role="alert">You lose</div>';

    } elseif ($dealer->hasLost()) {
        $html = '<div class="alert-success top" role="alert">You win</div>';
        $_SESSION['chips'] += (int)$_SESSION['bet'] * CHIPS_MULTI;

    }
    echo $html ?? '';
    $_SESSION['gameOver'] = true;
    unset($_SESSION['blackjack'], $_SESSION['bet'], $_SESSION['betPlaced']);

}

if (empty($_SESSION['blackjack'])) {
    $_SESSION['blackjack'] = new Blackjack();
}
if (!isset($_SESSION['chips'])) {
    $_SESSION['chips'] = 100;
}

$player = $_SESSION['blackjack']->getPlayer();
$dealer = $_SESSION['blackjack']->getDealer();
$deck = $_SESSION['blackjack']->getDeck();

checkBlackjack();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['bet']) && !isset($_SESSION['betPlaced'])) {

        $post = htmlspecialchars($_POST['bet'], ENT_NOQUOTES);

        if ($post >= MIN_BET && is_numeric($post) && $post <= $_SESSION['chips']) {

            $_SESSION['chips'] -= (int)$post;
            $_SESSION['bet'] += (int)$post;
            $_SESSION['betPlaced'] = true;

        }
    }

    if (isset($_POST['run'])) {
        $_SESSION['run'] = htmlspecialchars($_POST['run'], ENT_NOQUOTES);
    }

    header("Location: index.php");
    exit;
}

if (isset($_SESSION['run'])) {

    switch ($_SESSION['run']) {

        case 'Hit':

            $player->hit($deck);
            if ($player->getScore() >= TWENTY_ONE) {
                $dealer->runDealer($deck);
                checkOutcome($player, $dealer);
            }

            break;

        case 'Hold':

            $dealer->runDealer($deck);
            checkOutcome($player, $dealer);
            break;

        case 'Fold':
            $player->surrender();
            checkOutcome($player, $dealer);
            break;
    }
}

//var_dump($_SESSION);
require 'table-view.php';
unset($_SESSION['run'], $_SESSION['gameOver']);

//TODO Move comparison score/break into hasLost method in player



