<?php
	require_once "common.php";
	$current_time = time();
	$gameCode = $_COOKIE['game_code'];
	$res_server = dbget("SELECT `ownerExpiry` FROM `server`");
	$ownerExpiry = $res_server[0][0];
	$res_game = dbget("SELECT `owner_lastmove` FROM `game` WHERE `gameCode`='$gameCode'");
	$ownerLastmove = $res_game[0][0] + 300;
	while($ownerExpiry < $ownerLastmove){
		$ownerExpiry += 60;
	}
	$time_left = $ownerExpiry - $current_time;
?>
<!--Force IE6 into quirks mode with this comment tag-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Teen-do-paanch</title>
		<link href="screen_game.css" type="text/css" rel="stylesheet" />
		<script>
			var time_left = <?php echo $time_left; ?>;
			function setTime(){
				document.getElementById('expiry').innerHTML = time_left--;
			}
			setInterval(setTime, 1000);
		</script>
	</head>
	<body>
		<div id="panel-information">
			<div class="padding">
				Game Code: <?php echo $gameCode; ?><br>
				Ownership will be expired in <span id="expiry"></span> seconds
			</div>
		</div>
		<div id="panel-arena">
			<div class="padding">
			
			</div>
		</div>
		<div id="panel-people">
			<div class="padding">
				<div id="sub-panel-players">
					
				</div>
				<div id="sub-panel-spectators">
					
				</div>
			</div>
		</div>
	</body>
</html>