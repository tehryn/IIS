<?php
	include '../includes/db.php';
?>

<!DOCTYPE html>
<html>
<?php include '../includes/head.php'; ?>
<body>
	<div id="wrapper">
		<div id="index_admin">
			<?php
				$h2_nadpis = 'Správa restaurace Sunny Night';
				include 'includes/header.php';
				include '../includes/login.php';
				if ( $_SESSION['user'] != '' && $_SESSION['user']['pravo'] == 'zakaznik') {
					header( 'Location: ../index.php' );
				}
				include 'includes/users.php';
			?>
		</div>
	</div>
</body>
</html>