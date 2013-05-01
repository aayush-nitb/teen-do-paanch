<?php
	require "common.php";
	if(isset($_POST["btn_create_game"])){
		$expire_time = time()+86400*365;
		$str_your_name = $_POST["str_your_name"];
		$game_code = newGameCode();
		setcookie("str_your_name", $str_your_name, $expire_time);
		setcookie("game_code", $game_code, $expire_time);
		header("Location: screen_game.php");
	}
?>