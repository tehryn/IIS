<?php
	include 'includes/db.php';
	session_unset();
	$_SESSION["user"] = "";
?>

<!DOCTYPE html>
<html>
<? include 'includes/head.php'; ?>
<body>
	<div id="wrapper">
		<?php
			$h2_nadpis = 'Odhlášení';
			include 'includes/header.php';
		?>
		<p class="ok">
			Odhlášení proběhlo úspěšně.
		</p>
		<?php
			include 'includes/login.php';
			if ( $_SESSION[ 'user' ] != "" ) {
				header( 'Location: index.php' );
			}
		?>
		<div id="footer">
		</div>
	</div>
</body>
</html>