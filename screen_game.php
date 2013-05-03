<?php
	require_once "common.php";
	$gameCode = $_COOKIE['game_code'];
	$server = dbget("SELECT `ownerExpiry` FROM `server`");
	$ownerExpiry = $server[0][0];
	$owner = dbget("SELECT `owner_lastmove` FROM `game` WHERE `gameCode`='$gameCode'");
	$ownerLastmove = $owner[0][0];
	$time_left = 360 + $ownerExpiry - $ownerLastmove;
?>
<html>
	<head>
		<script>
			var time_left = <?php echo $time_left; ?>;
			function setTime(){
				document.getElementById('body').innerHTML = time_left--;
			}
			setInterval(setTime, 1000);
		</script>
	</head>
	<body id="body">
	
	</body>
</html>