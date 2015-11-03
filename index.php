<?php
session_start();
error_reporting(E_WARNING);
include('city.php');
$computerWordKeyFirst = array_rand($citiesList);
$computerWordFirst    = $citiesList[$computerWordKeyFirst];


// Need to fix that array with used words
$usedWords = [];
if (isset($_SESSION['usedWords']) && is_array($_SESSION['usedWords'])) {
    $usedWords = $_SESSION['usedWords'];
}

function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}
function check($city, $wordsArray) {
	$l = substr($city,-1); // Getting last char of the player word
	if ( substr($city, -1) == "'") {
		$l = substr($city,-2);
		$l = substr($l, 0,-1);
	}
  shuffle($wordsArray); // Make some random ;)
	foreach ($wordsArray as $word) {
		if ( substr( $word,0,1 ) == strtoupper($l) ) {
			return $word;
			break;
		}
	}
}

// Check and clean player name
if (isset($_POST['playerName'])) {
    $_SESSION['playerName'] = clean($_POST['playerName']);
}
$player = isset($_SESSION['playerName']) ? $_SESSION['playerName'] : '';

// Check and clean player answer
if (isset($_POST['playerAnswer'])) {
	$_SESSION['playerAnswer'] = clean($_POST['playerAnswer']);

}
$playerAnswer = isset($_SESSION['playerAnswer']) ? $_SESSION['playerAnswer'] : '';


if ( !$player ) {
    include_once('template.php'); // enter name template
} elseif ($player!=null && !$playerAnswer) { // first word
		echo "Hello, $player! Let's start<br> My word is $computerWordFirst <br> Used words:  ";
	array_push($usedWords, $computerWordFirst);
	array_push($usedWords, $playerAnswer);
	foreach ($usedWords as $key) {
		echo $key;
		}
	include_once('answertemp.php');
}

// player's answer
if ($player!=null && $playerAnswer!=null && in_array($playerAnswer, $citiesList)) {
//  var_dump($playerAnswer);
//  var_dump(check($playerAnswer,$citiesList));
  $computerWord = check($playerAnswer,$citiesList);
  echo "My word is $computerWord <br> Used words:  ";
  include_once('answertemp.php'); // template with form for answer
} elseif ($player!=null && $playerAnswer!=null && !in_array($playerAnswer, $citiesList)) {                                            //wrong answer
	echo "Wrong city, try again";
	include_once('answertemp.php'); // template with form for answer
}	elseif ($player !=null && $playerAnswer !=null && in_array($playerAnswer, $usedWords)) {
  echo'Used word! Try again';
  include_once('answertemp.php');  // template with form for answer
}

// Need for destroy session. Test mode.
echo '<pre><a href="end.php">Destroy session</a></pre>';
