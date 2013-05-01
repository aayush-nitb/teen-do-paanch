<?php
	if(isset($_POST["btn_create_game"])){
		$str_your_name = $_POST["str_your_name"];
		setcookie("str_your_name", $str_your_name, time()+86400*365);
		$_COOKIE["str_your_name"];
	}
?>