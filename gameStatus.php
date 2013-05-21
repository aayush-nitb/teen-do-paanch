<?php
	require_once "common.php";
	$gameCode = $_COOKIE['game_code'];
	$my_name = $_COOKIE['str_your_name'];
	$res_game = dbget("SELECT * FROM `game` WHERE `gameCode`='$gameCode'");
	$LIST_OF_PLAYERS = "";
	$LIST_OF_SPECTATORS = "";
	
	if($res_game[0]['player1'] === $my_name){
		$my_player = 'player1';
	}
	elseif($res_game[0]['player2'] === $my_name){
		$my_player = 'player2';
	}
	elseif($res_game[0]['player3'] === $my_name){
		$my_player = 'player3';
	}
	else{
		$my_player = 'spectator';
	}
	
	if(isset($_GET['query'])){
		$query = $_GET['query'];
	}
	else{
		$query = null;
	}
	if($query && $my_player !== 'spectator'){
		$LIST_OF_PLAYERS .= "<div>gotcha</div>";
		if(preg_match('/^through (.*)$/', $query, $match)){
			$card = $match[1];
			if($res_game[0][$card] === $my_player){
				$res_transaction = dbget("SELECT max(`number`) FROM `transaction` WHERE `gameCode`='$gameCode'");
				$transaction_number = $res_transaction[0][0];
				if($transaction_number){
					$transaction_number++;
				}
				else{
					$transaction_number = 1;
				}
				if(dbset("INSERT INTO `transaction`(`gameCode`,`number`,`attribute`,`from`,`to`) VALUES('$gameCode',$transaction_number,'$card','$my_player','current_trick')")){
					dbset("UPDATE `game` SET `$card`='current_trick' WHERE `gameCode`='$gameCode'");
				}
			}
		}
	}
	
	$res_user = dbget("SELECT `name` FROM `user` WHERE `gameCode`='$gameCode' and `role`='player'");
	if($res_user){
		foreach($res_user as $user){
			$LIST_OF_PLAYERS .= "<div>".$user['name']."</div>\n";
		}
	}
	
	$res_user = dbget("SELECT `name` FROM `user` WHERE `gameCode`='$gameCode' and `role`='spectator'");
	if($res_user){
		foreach($res_user as $user){
			$LIST_OF_SPECTATORS .= "<div>".$user['name']."</div>\n";
		}
	}
	
	echo json_encode(array("LIST_OF_PLAYERS" => $LIST_OF_PLAYERS, "LIST_OF_SPECTATORS" => $LIST_OF_SPECTATORS));
?>