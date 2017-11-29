<?php
	include '../includes/db.php';
	session_unset();
	$_SESSION["user"] = "";
	header( 'Location: index.php' );
?>

<!DOCTYPE html>
<html>
<? include '../includes/head.php'; ?>
<body>
</body>
</html>