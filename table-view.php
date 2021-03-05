<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" type="text/css"
          rel="stylesheet"/>
    <link href="table.css" rel="stylesheet">
    <title>Blackjack</title>
</head>
<body>
<div class="container">


    <div class="chip-bag">

        <?php
        echo "Player chips: " . ($_SESSION['chips'] ?? '0') . PHP_EOL;
        echo "Staked chips: " . ($_SESSION['bet'] ?? '0') . PHP_EOL;
        ?>

    </div>
    <div class="card-table">
        <div class="dealer-card-holder">
            <label>Dealer Cards</label>
            <?php

            if ((isset($_SESSION['run']) && $_SESSION['run'] === 'Hold') || $dealer->blackJackRule() || $player->blackJackRule()) {
                echo nl2br("\n");
                echo nl2br("\n" . $dealer->showHand('hand'));
                echo nl2br("\n" . $dealer->getScore());
            } else {
                echo nl2br("\n");
                echo nl2br("\n" . $dealer->showHand('first'));
                echo nl2br("\n" . '??');
            }
            ?>

        </div>
        <div class="player-card-holder">

            <?php

            echo nl2br("\n" . $player->getScore());
            echo nl2br("\n");
            echo nl2br("\n" . $player->showHand('hand'));

            ?>
            <label>Player Cards</label>
        </div>
    </div>


    <div class="navigation">
        <div class="navigationBlock">
            <?php if (isset($_SESSION['gameOver']) && $_SESSION['gameOver'] === true): ?>

                <a class="navigate" href="index.php">Start New Game</a>

            <?php elseif (!isset($_SESSION['betPlaced'])): ?>

                <form method="post">
                    <label for="bet"></label>
                    <input name="bet" id="bet" type="text">
                    <button type="submit">Place Bet</button>
                </form>

            <?php else: ?>

                <form method="post">
                    <input name="run" id="run" type="submit" value="Hit"/>
                    <input name="run" id="run" type="submit" value="Hold"/>
                    <input name="run" id="run" type="submit" value="Fold"/>
                </form>

            <?php endif; ?>
        </div>
    </div>

</div>
</body>
</html>