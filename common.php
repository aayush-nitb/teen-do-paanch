<?php
	function dbget($q)
	{
		$cn = mysql_connect("localhost","","");
		mysql_select_db("teen-do-paanch",$cn);
		$r = mysql_query($q,$cn);
		if(!$r) die("INVALID QUERY");
		$i=0; while($d=mysql_fetch_array($r)) $data[$i++] = $d;
		mysql_close($cn);
		if(isset($data)) return $data;
	}
	function dbset($q)
	{
		$cn = mysql_connect("localhost","","");
		mysql_select_db("teen-do-paanch",$cn);
		$r = mysql_query($q,$cn);
		mysql_close($cn);
		return $r;
	}
	function newGameCode() {
		return substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 8);
	}
?>