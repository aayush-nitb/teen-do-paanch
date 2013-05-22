<?php
	require_once "common.php";
	$current_time = time();
	$gameCode = $_COOKIE['game_code'];
	$my_name = $_COOKIE['str_your_name'];
	$res_server = dbget("SELECT `ownerExpiry` FROM `server`");
	$ownerExpiry = $res_server[0][0];
	$res_game = dbget("SELECT * FROM `game` WHERE `gameCode`='$gameCode'");
	$ownerLastmove = $res_game[0]['owner_lastmove'] + 3600;
	while($ownerExpiry < $ownerLastmove){
		$ownerExpiry += 60;
	}
	$time_left = $ownerExpiry - $current_time;
	
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
	
	$top_player = '';
	if($res_game[0]['player1'] === $my_name){
		$my_player = 'player1';
		$left_player = 'player2';
		$right_player = 'player3';
	}
	elseif($res_game[0]['player2'] === $my_name){
		$my_player = 'player2';
		$left_player = 'player3';
		$right_player = 'player1';
	}
	elseif($res_game[0]['player3'] === $my_name){
		$my_player = 'player3';
		$left_player = 'player1';
		$right_player = 'player2';
	}
	else{
		$my_player = 'spectator';
		$top_player = 'player1';
		$left_player = 'player3';
		$right_player = 'player2';
	}
	
	$left_count = 0;
	$right_count = 0;
	$top_count = 0;
	$my_card = array();
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
		if($res_game[0][$card] === $my_player) $my_card[] = $card;
		if($res_game[0][$card] === $top_player) $top_count++;
		if($res_game[0][$card] === $left_player) $left_count++;
		if($res_game[0][$card] === $right_player) $right_count++;
	}
	
	$res_transaction = dbget("SELECT max(`number`) FROM `transaction` WHERE `gameCode`='$gameCode'");
	if($res_transaction[0][0]){
		$transaction_number = $res_transaction[0][0];
	}
	else{
		$transaction_number = "0";
	}
?>
<!--Force IE6 into quirks mode with this comment tag-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Teen-do-paanch</title>
		<link href="screen_game.css" type="text/css" rel="stylesheet" />
		<script src="jquery-1.9.1.js"></script>
		<script src="jquery-ui-1.10.3.js"></script>
		<script>
			var refresh_time = 1000;
			var time_left = <?php echo $time_left; ?>;
			var transaction_number = <?php echo $transaction_number; ?>;
			
			setInterval(function(){
				document.getElementById('expiry').innerHTML = time_left--;
				$.ajax({
					url: 'gameStatus.php',
					data: 'transaction_number=' + transaction_number + '&query=' + $("#query").text(),
					type: 'get',
					dataType: 'json',
					success: function(data){
						$("#dynamic-panel-players").html(data["LIST_OF_PLAYERS"]);
						$("#dynamic-panel-spectators").html(data["LIST_OF_SPECTATORS"]);
						if(data["transaction"]){
							alert("animate card " + data["transaction"]["attribute"] + " from " + data["transaction"]["from"] + " to " + data["transaction"]["to"]);
							transaction_number++;
						}
						$("#query").text("");
					}
				}).fail(function(){
					console.log("failed to get game status");
				});
			}, refresh_time);
			
			$(function(){
				<?php if($my_player !== 'spectator'){ ?>
					$("#area-my .card").draggable({
						addClasses: false,
						revert: "invalid"
					});
					<?php if($my_player === $res_game[0]['turn']){ ?>
						$("#container-trick").droppable({
							addClasses: false,
							drop: function(event, ui){
								var card = $(ui.draggable).attr('class');
								$(ui.draggable).remove();
								$("#area-trick").append("<div class='"+ card +"' id='card1'></div>");
								$("#query").text('through ' + card);
							}
						});
					<?php } ?>
				<?php } ?>
			});
		</script>
	</head>
	<body>
		<div id="panel-information">
			<div class="padding">
				Game Code: <?php echo $gameCode; ?><br>
				Ownership will be expired in <span id="expiry"></span> seconds
				<div id="query"></div>
			</div>
		</div>
		<div id="panel-arena">
			<div class="center padding">
				<div id="container-row1">
					<div id="area-my">
						<?php
							if($my_player === 'spectator'){
								for($i=0; $i<$top_count; $i++){
									echo "<div class='hidden card' id='card$i'></div>\n";
								}
							}else{
								if($my_card){
									foreach($my_card as $key=>$value){
										echo "<div class='card $value' id='card$key'></div>\n";
									}
								}
							}
						?>
					</div>
				</div>
				<div id="container-row2">
					<div id="area-left">
						<?php
							for($i=0; $i<$left_count; $i++){
								echo "<div class='card' id='card$i'></div>\n";
							}
						?>
					</div>
					<div id="container-trick">
						<div id="area-trick"></div>
					</div>
					<div id="area-right">
						<?php
							for($i=0; $i<$right_count; $i++){
								echo "<div class='card' id='card$i'></div>\n";
							}
						?>
					</div>
				</div>
			</div>
		</div>
		<div id="panel-people">
			<div class="padding">
				<div class="sub-panel-people">
					<div class="heading">Players</div>
					<div id="dynamic-panel-players">
						<?php echo $LIST_OF_PLAYERS; ?>
					</div>
				</div>
				<div class="sub-panel-people">
					<div class="heading">Spectators</div>
					<div id="dynamic-panel-spectators">
						<?php echo $LIST_OF_SPECTATORS; ?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>