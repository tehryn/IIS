<?php
	header( 'Content-Type: text/html; charset=utf-8' );
	$db = mysql_connect('localhost:/var/run/mysql/mysql.sock', 'xmatej52', 'konbo7ur');
	mysql_set_charset('utf8',$db);
    if (!$db) die('nelze se pripojit '.mysql_error());
    if (!mysql_select_db('xmatej52', $db)) die('database neni dostupna '.mysql_error());
	session_start();
	if ( !isset( $_SESSION[ "user" ] ) ) {
		$_SESSION["user"] = "";
	}
?>