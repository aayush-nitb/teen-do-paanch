<?php
	require_once "common.php";
	$gameCode = $_COOKIE['game_code'];
	
	$LIST_OF_PLAYERS = "";
	$res_user = dbget("SELECT `name` FROM `user` WHERE `gameCode`='$gameCode' and `role`='player'");
	if($res_user){
		foreach($res_user as $user){
			$LIST_OF_PLAYERS .= "<div>".$user['name']."</div>\n";
		}
	}
	
	$LIST_OF_SPECTATORS = "";
	$res_user = dbget("SELECT `name` FROM `user` WHERE `gameCode`='$gameCode' and `role`='spectator'");
	if($res_user){
		foreach($res_user as $user){
			$LIST_OF_SPECTATORS .= "<div>".$user['name']."</div>\n";
		}
	}
	
	echo json_encode(array("LIST_OF_PLAYERS" => $LIST_OF_PLAYERS, "LIST_OF_SPECTATORS" => $LIST_OF_SPECTATORS));
?>