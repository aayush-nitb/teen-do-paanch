<?php
	require_once "common.php";
	$gameCode = $_COOKIE['game_code'];
	$my_name = $_COOKIE['str_your_name'];
	$res_game = dbget("SELECT * FROM `game` WHERE `gameCode`='$gameCode'");
	$res_transaction = dbget("SELECT max(`number`) FROM `transaction` WHERE `gameCode`='$gameCode'");
	if($res_transaction[0][0]){
		$transaction_number = $res_transaction[0][0];
	}
	else{
		$transaction_number = 0;
	}
	if(isset($_GET['transaction_number'])){
		$current_transaction_number = intval($_GET['transaction_number']);
	}
	else{
		$current_transaction_number = 0;
	}
	if(isset($_GET['query'])){
		$query = $_GET['query'];
	}
	else{
		$query = "";
	}
	
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
	
	if($current_transaction_number < $transaction_number){
		$current_transaction_number++;
		$next_transaction_res = dbget("SELECT * FROM `transaction` WHERE `gameCode`='$gameCode' AND `number`=$current_transaction_number");
		$next_transaction = $next_transaction_res[0];
	}
	else{
		$next_transaction = null;
	}
	
	if(preg_match('/^through card (\S+) ui-draggable-dragging$/', $query, $match) && $my_player === $res_game[0]['turn']){
		$card = $match[1];
		if($res_game[0][$card] === $my_player){
			$transaction_number++;
			if(dbset("INSERT INTO `transaction`(`gameCode`,`number`,`action`,`attribute`,`from`,`to`) VALUES('$gameCode',$transaction_number,'through card','$card','$my_player','current_trick')")){
				if(dbset("UPDATE `game` SET `$card`='current_trick' WHERE `gameCode`='$gameCode'")){
					$current_trick = array($card);
					$cards = array();
					$deck = array("spade","heart","club","diamond");
					foreach($deck as $d){
						if($d === "spade") $cards[] = "spade7";
						if($d === "heart") $cards[] = "heart7";
						foreach(range(8,13) as $number){
							$cards[] = $d.$number;
						}
						$cards[] = $d."1";
					}
					foreach($cards as $card){
						if($res_game[0][$card] === 'current_trick') $current_trick[] = $card;
					}
					if(count($current_trick) == 3){
						//find next trick number
						//find trick holder
						//update game set card=trick, trick=holder, turn=holder
						//insert transaction
					}
					else{
						if($res_game[0]['turn'] === "player1") $next_turn = "player2";
						elseif($res_game[0]['turn'] === "player2") $next_turn = "player3";
						elseif($res_game[0]['turn'] === "player3") $next_turn = "player1";
						dbset("UPDATE `game` SET `turn`='$next_turn' WHERE `gameCode`='$gameCode'");
					}
				}
			}
		}
	}
	elseif(preg_match('/^select (\S+)$/', $query, $match) && $my_player === "player1"){
		$player_name = $match[1];
		if($res_game[0]["player2"] === ""){
			$transaction_number++;
			if(dbset("INSERT INTO `transaction`(`gameCode`,`number`,`action`,`attribute`,`from`,`to`) VALUES('$gameCode',$transaction_number,'select player','player2','','$player_name')")){
				if(dbset("UPDATE `game` SET `player2`='$player_name' WHERE `gameCode`='$gameCode'")){
					dbset("UPDATE `user` SET `role`='player' WHERE `gameCode`='$gameCode' AND `name`='$player_name'");
				}
			}
		}
		elseif($res_game[0]["player3"] === ""){
			$transaction_number++;
			if(dbset("INSERT INTO `transaction`(`gameCode`,`number`,`action`,`attribute`,`from`,`to`) VALUES('$gameCode',$transaction_number,'select player','player3','','$player_name')")){
				if(dbset("UPDATE `game` SET `player3`='$player_name' WHERE `gameCode`='$gameCode'")){
					dbset("UPDATE `user` SET `role`='player' WHERE `gameCode`='$gameCode' AND `name`='$player_name'");
				}
			}
		}
	}
	
	$LIST_OF_PLAYERS = "";
	$res_user = dbget("SELECT `name` FROM `user` WHERE `gameCode`='$gameCode' and `role`='player'");
	if($res_user){
		foreach($res_user as $user){
			$LIST_OF_PLAYERS .= "<div>".$user['name']."</div>\n";
		}
	}
	
	$info = "";
	if($my_player === "player1"){
		if($res_game[0]["player2"] === ""){
			$info = "Select second player";
		}
		elseif($res_game[0]["player3"] === ""){
			$info = "Select third player";
		}
	}
	
	$LIST_OF_SPECTATORS = "<div class='info'>$info</div>\n";
	$option = ($my_player === "player1")? "option": "";
	$res_user = dbget("SELECT `name` FROM `user` WHERE `gameCode`='$gameCode' and `role`='spectator'");
	if($res_user){
		foreach($res_user as $user){
			$LIST_OF_SPECTATORS .= "<div class='user $option'>".$user['name']."</div>\n";
		}
	}
	
	echo json_encode(array("LIST_OF_PLAYERS" => $LIST_OF_PLAYERS, "LIST_OF_SPECTATORS" => $LIST_OF_SPECTATORS, "transaction" => $next_transaction));
?>