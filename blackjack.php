<?php
$card_deck = [
  [2, 2, 2, 2],
  [3, 3, 3, 3],
  [4, 4, 4, 4],
  [5, 5, 5, 5],
  [6, 6, 6, 6],
  [7, 7, 7, 7],
  [8, 8, 8, 8],
  [9, 9, 9, 9],
  [10, 10, 10, 10],
  [10, 10, 10, 10],
  [10, 10, 10, 10],
  [10, 10, 10, 10],
  ["ace", "ace", "ace", "ace"],
];

function get_random_card(&$deck): int
{
  $index1 = rand(0, (count($deck) - 1));
  $index2 = rand(0, (count($deck[$index1]) - 1));
  $card = $deck[$index1][$index2];

  array_splice($deck[$index1], $index2, 1);

  if (count($deck[$index1]) === 0) {
    array_splice($deck, $index1, 1);
  }
  
  if ($card == "ace") {
    $rand_ace = rand(1, 2);
    $rand_ace == 1 ? $card = 1 : $card = 11;
  }

  return $card;
}

function dealCards(&$deck): array
{
  $card1 = get_random_card($deck);
  $card2 = get_random_card($deck);

  return [$card1, $card2];
}

function whoWon($player_cards, $dealer_cards): int
{
  $playerValue = 21 - $player_cards[0] + $player_cards[1];
  $dealerValue = 21 - $dealer_cards[0] + $dealer_cards[1];

  if ($playerValue == $dealerValue) {
    return 0;
  } else {
    return $playerValue < $dealerValue ? 1 : -1;
  }
}

function game(): string
{
  global $card_deck;
  $playerPoints = 0;
  $dealerPoints = 0;
  $instantWin = "";
  for ($i = 0; $i < 5; $i++) {
    $player_cards = dealCards($card_deck);
    $dealer_cards = dealCards($card_deck);
    $playerValue = $player_cards[0] + $player_cards[1];
    $dealerValue = $dealer_cards[0] + $dealer_cards[1];

    $round = whoWon($player_cards, $dealer_cards);
    echo "<br> Player: $playerValue <br>";
    echo "Dealer: $dealerValue <br>";
    if ($playerValue == 21) {
      $instantWin = "Player";
      break;
    }
    if ($dealerValue == 21) {
      $instantWin = "Dealer";
    }
    if ($round == 0) {
      echo "Result: Unentschieden <br>";
    }
    if ($round == 1) {
      echo "Result: Player wins Round <br>";
      $playerPoints++;
    }
    if ($round == -1) {
      echo "Result: Dealer wins Round <br>";
      $dealerPoints++;
    }
    echo "<br> Player: $playerPoints vs. Dealer: $dealerPoints <br>";

  }
  if ($instantWin) {
    return "$instantWin wins with Blackjack";
  }

  return $playerPoints > $dealerPoints ? "Player wins the Game" : "Dealer wins the Game";
}

echo "<br>" . game();