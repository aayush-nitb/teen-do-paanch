<?php
	require_once "common.php";
	if(isset($_POST["btn_create_game"])){
		$current_time = time();
		$expire_time = $current_time + 86400*365;
		$str_your_name = $_POST["str_your_name"];
		
		do{
			$game_code = newGameCode();
		}while(dbget("SELECT * from `game` WHERE `gameCode`='$game_code'"));
		
		dbset("INSERT INTO `user`(`name`, `gameCode`, `role`) VALUES('$str_your_name', '$game_code', 'player')");
		dbset("INSERT INTO `game`(`gameCode`, `turn`, `player1`, `owner_lastmove`) VALUES('$game_code', '$str_your_name', '$str_your_name', $current_time)");
		
		setcookie("str_your_name", $str_your_name, $expire_time);
		setcookie("game_code", $game_code, $expire_time);
		header("Location: screen_game.php");
	}
?>