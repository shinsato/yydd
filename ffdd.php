#!/usr/bin/php -q
<?php
	require_once('classes/Random.php');
	require_once('classes/Player.php');
	require_once('classes/Human.php');
	require_once('classes/Fighter.php');
	require_once('classes/Battle.php');
	require_once('classes/FBFriend.php');
	
	if(isset($argv[1]))
	{
		$seed = intval($argv[1]);
	} else {
		$seed = mt_rand(0, 2147483647);
	}

	$battle = 'friends';

	Random::init($seed);

	echo "Using seed: " . Random::getSeed() . "\n\n";

	require_once('battles/' . $battle . '/Battle.php');

	$battle->start();

?>
