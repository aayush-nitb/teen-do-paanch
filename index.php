<?php
	if(isset($_COOKIE['str_your_name'])){
		$str_your_name = $_COOKIE['str_your_name'];
	}else{
		$str_your_name = '';
	}
?>
<!--Force IE6 into quirks mode with this comment tag-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Teen-do-paanch</title>
		<link href="index.css" type="text/css" rel="stylesheet" />
	</head>
	<body>
		<div id="screen_main">
			<form action="action_create_game.php" method="post">
				<div class="heading">
					Create Game
				</div>
				<div class="input">
					<span>Your Name</span>
					<input type="text" name="str_your_name" value="<?php echo $str_your_name; ?>" />
				</div>
				<div class="action">
					<input value="Create Game" type="submit" name="btn_create_game" />
				</div>
			</form>
			<form action="action_join_game.php" method="post">
				<div class="heading">
					Join Game
				</div>
				<div class="input">
					<span>Your Name</span>
					<input type="text" name="str_your_name" value="<?php echo $str_your_name; ?>" />
				</div>
				<div class="input">
					<span>Game Code</span>
					<input type="text" name="str_game_code" />
				</div>
				<div class="action">
					<input value="Join Game" type="submit" name="btn_join_game" />
				</div>
			</form>
		</div>
	</body>
</html>