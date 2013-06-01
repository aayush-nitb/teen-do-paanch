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
	
	$top_player = '';
	if($res_game[0]['player1'] === $my_name){
		$my_player = 'player1';
		$left_player = 'player3';
		$right_player = 'player2';
	}
	elseif($res_game[0]['player2'] === $my_name){
		$my_player = 'player2';
		$left_player = 'player1';
		$right_player = 'player3';
	}
	elseif($res_game[0]['player3'] === $my_name){
		$my_player = 'player3';
		$left_player = 'player2';
		$right_player = 'player1';
	}
	else{
		$my_player = 'spectator';
		$top_player = 'player1';
		$left_player = 'player3';
		$right_player = 'player2';
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
	
	$name_left = $res_game[0][$left_player];
	if($name_left === ""){
		$name_left_filler = "<div class='vacant name' id='name-left'>vacant</div>";
	}
	else{
		$name_left_filler = "<div class='name' id='name-left'>$name_left</div>";
	}
	
	$name_right = $res_game[0][$right_player];
	if($name_right === ""){
		$name_right_filler = "<div class='vacant name' id='name-right'>vacant</div>";
	}
	else{
		$name_right_filler = "<div class='name' id='name-right'>$name_right</div>";
	}
	
	$left_count = 0;
	$right_count = 0;
	$top_count = 0;
	$my_card = array();
	$trick = array();
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
		if($res_game[0][$card] === "current_trick") $trick[] = $card;
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
	
	$current_trick = "";
	$trick_card1 = isset($trick[0])? $trick[0]: "";
	$trick_card2 = isset($trick[1])? $trick[1]: "";
	$trick_card3 = isset($trick[2])? $trick[2]: "";
	$res_transaction = dbget("SELECT `attribute`,`from` FROM `transaction` WHERE `gameCode`='$gameCode' AND `action`='through card' AND `to`='current_trick' AND (`attribute`='$trick_card1' OR `attribute`='$trick_card2' OR `attribute`='$trick_card3') ORDER BY `number`");
	if($res_transaction){
		foreach($res_transaction as $transaction){
			$card = $transaction['attribute'];
			if($transaction['from'] === $my_player) $id = "card1";
			elseif($transaction['from'] === $top_player) $id = "card1";
			elseif($transaction['from'] === $left_player) $id = "card2";
			elseif($transaction['from'] === $right_player) $id = "card3";
			$current_trick .= "\n<div class='card $card' id='$id'></div>";
		}
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
			var my_player = "<?php echo $my_player; ?>";
			var left_player = "<?php echo $left_player; ?>";
			var right_player = "<?php echo $right_player; ?>";
			var top_player = "<?php echo $top_player; ?>";
			
			setInterval(function(){
				document.getElementById('expiry').innerHTML = time_left--;
				$.ajax({
					url: 'gameStatus.php',
					data: 'transaction_number=' + transaction_number + '&query=' + $("#query").text(),
					type: 'get',
					dataType: 'json',
					cache: false,
					success: function(data){
						$("#dynamic-panel-players").html(data["LIST_OF_PLAYERS"]);
						$("#dynamic-panel-spectators").html(data["LIST_OF_SPECTATORS"]);
						$("#dynamic-panel-spectators .option").click(function(){
							$("#query").text('select ' + $(this).text());
						});
						if(data["transaction"]){
							if(data["transaction"]["action"] === "through card"){
								if(data["transaction"]["from"] === left_player){
									var count_cards = $("#area-left .card").length;
									var random_number = Math.ceil(Math.random()*count_cards);
									var random_card = $("#area-left .card:nth-child(" + random_number + ")");
									$(random_card).animate({
										'left': '300px',
										'top': '145px'
									}, 500, function(){
										$(random_card).remove();
										$("#area-trick").append("<div class='card " + data["transaction"]["attribute"] + "' id='card2'></div>");
										if(data["transaction"]["next_turn"] === my_player) $("#container-trick").droppable("enable");
									});
								}
								else if(data["transaction"]["from"] === right_player){
									var count_cards = $("#area-right .card").length;
									var random_number = Math.ceil(Math.random()*count_cards);
									var random_card = $("#area-right .card:nth-child(" + random_number + ")");
									$(random_card).animate({
										'left': '-257px',
										'top': '127px'
									}, 500, function(){
										$(random_card).remove();
										$("#area-trick").append("<div class='card " + data["transaction"]["attribute"] + "' id='card3'></div>");
									});
								}
								else if(data["transaction"]["from"] === top_player){
									var count_cards = $("#area-my .card").length;
									var random_number = Math.ceil(Math.random()*count_cards);
									var random_card = $("#area-my .card:nth-child(" + random_number + ")");
									$(random_card).animate({
										'left': '165px',
										'top': '255px'
									}, 500, function(){
										$(random_card).remove();
										$("#area-trick").append("<div class='card " + data["transaction"]["attribute"] + "' id='card1'></div>");
									});
								}
								else if(data["transaction"]["from"] === my_player){
									$("#container-trick").droppable("disable");
								}
							}
							else if(data["transaction"]["action"] === "select player"){
								location = "screen_game.php";
							}
							else if(data["transaction"]["action"] === "move current_trick"){
								if(data["transaction"]["to"] === left_player){
									$("#area-trick").animate({
										'left': '-150px',
										'opacity': '0'
									}, 1000, function(){
										$(this).html('');
										$(this).css('left', '0px');
										$(this).css('top', '0px');
										$(this).css('opacity', '1');
									});
								}
								else if(data["transaction"]["to"] === right_player){
									$("#area-trick").animate({
										'left': '150px',
										'opacity': '0'
									}, 1000, function(){
										$(this).html('');
										$(this).css('left', '0px');
										$(this).css('top', '0px');
										$(this).css('opacity', '1');
									});
								}
								else if(data["transaction"]["to"] === top_player){
									$("#area-trick").animate({
										'top': '-150px',
										'opacity': '0'
									}, 1000, function(){
										$(this).html('');
										$(this).css('left', '0px');
										$(this).css('top', '0px');
										$(this).css('opacity', '1');
									});
								}
								else if(data["transaction"]["to"] === my_player){
									$("#area-trick").animate({
										'top': '-150px',
										'opacity': '0'
									}, 1000, function(){
										$(this).html('');
										$(this).css('left', '0px');
										$(this).css('top', '0px');
										$(this).css('opacity', '1');
										$("#container-trick").droppable("enable");
									});
								}
							}
							transaction_number = data["transaction"]["number"];
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
					$("#container-trick").droppable({
						<?php if($my_player !== $res_game[0]['turn']) echo "disabled:true,"; ?>
						addClasses: false,
						drop: function(event, ui){
							var card = $(ui.draggable).attr('class');
							$(ui.draggable).remove();
							$("#area-trick").append("<div class='"+ card +"' id='card1'></div>");
							$("#query").text('through ' + card);
						}
					});
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
					<div class="name" id="name-my"><?php echo ($my_player === "spectator")? $res_game[0][$top_player]: $res_game[0][$my_player]; ?></div>
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
					<?php echo $name_left_filler; ?>
					<div class="filler"></div>
					<?php echo $name_right_filler; ?>
				</div>
				<div id="container-row3">
					<div id="area-left">
						<?php
							for($i=0; $i<$left_count; $i++){
								echo "<div class='card' id='card$i'></div>\n";
							}
						?>
					</div>
					<div id="container-trick">
						<div id="area-trick"><?php echo $current_trick; ?>
						</div>
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