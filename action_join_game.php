<?php
	require_once "common.php";
	if(isset($_POST["btn_join_game"])){
		$current_time = time();
		$expire_time = $current_time + 86400*365;
		$str_your_name = $_POST["str_your_name"];
		$str_game_code = $_POST["str_game_code"];
		
		if(dbget("SELECT * FROM `game` WHERE `gameCode`='$str_game_code'")){
			if(dbget("SELECT * FROM `user` WHERE `gameCode`='$str_game_code' AND `name`='$str_your_name'")){
				header("Location: index.php");
			}else{
				dbset("INSERT INTO `user`(`name`, `gameCode`, `role`) VALUES('$str_your_name', '$str_game_code', 'spectator')");
				setcookie("str_your_name", $str_your_name, $expire_time);
				setcookie("game_code", $str_game_code, $expire_time);
				header("Location: screen_game.php");
			}
		}else{
			header("Location: index.php");
		}
	}
?>