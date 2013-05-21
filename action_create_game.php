<?php
	require_once "common.php";
	if(isset($_POST["btn_create_game"])){
		$current_time = time();
		$cookie_expire_time = $current_time + 86400*365;
		$str_your_name = $_POST["str_your_name"];
		
		do{
			$game_code = newGameCode();
		}while(dbget("SELECT * from `game` WHERE `gameCode`='$game_code'"));
		
		$cards = array();
		$deck = array("spade","heart","club","diamond");
		foreach($deck as $d){
			foreach(range(8,13) as $number){
				$cards[] = $d.$number;
			}
			$cards[] = $d."1";
		}
		$cards[] = "spade7";
		$cards[] = "heart7";
		shuffle($cards);
		foreach ($cards as &$card)	$card = "`".$card."`";
		$card_list = implode(",", $cards);
		
		$players = array();
		for($i=0; $i<10; $i++){
			$players[] = "player1";
			$players[] = "player2";
			$players[] = "player3";
		}
		foreach ($players as &$player)	$player = "'".$player."'";
		$player_list = implode(",", $players);
		
		dbset("INSERT INTO `user`(`name`, `gameCode`, `role`) VALUES('$str_your_name', '$game_code', 'player')");
		dbset("INSERT INTO `game`(`gameCode`, `turn`, `player1`, `owner_lastmove`, $card_list) VALUES('$game_code', 'player1', '$str_your_name', $current_time, $player_list)");
		
		setcookie("str_your_name", $str_your_name, $cookie_expire_time);
		setcookie("game_code", $game_code, $cookie_expire_time);
		header("Location: screen_game.php");
	}
?>