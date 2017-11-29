<?php
	header( 'Content-Type: text/html; charset=utf-8' );
	$db = mysql_connect('localhost:/var/run/mysql/mysql.sock', 'xmatej52', 'konbo7ur');
	mysql_set_charset('utf8',$db);
    if (!$db) die('nelze se pripojit '.mysql_error());
    if (!mysql_select_db('xmatej52', $db)) die('database neni dostupna '.mysql_error());
	session_start();
	// je uzivatel prihlasen?
	if ( !isset( $_SESSION[ "user" ] ) ) {
		$_SESSION["user"] = "";
	}
	if ( !isset( $_SESSION['LastRequest'] ) ) {
		$_SESSION['LastRequest'] = "";
	}
	// detekce F5
	$_SESSION["refresh"] = false;
	$RequestSignature = md5($_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING'].print_r($_POST, true));
	if ($_SESSION['LastRequest'] == $RequestSignature) {
		$_SESSION["refresh"] = true;
	}
	else {
		$_SESSION['LastRequest'] = $RequestSignature;
	}
?>