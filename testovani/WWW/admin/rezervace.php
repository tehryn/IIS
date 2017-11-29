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
				$h2_nadpis = 'Správa zaměstnanců';
				if ( $_SESSION['user'] == '' ) {
					header( 'Location: index.php' );
				}
				else {
					$h2_nadpis = 'Správa restaurace Sunny Night';
					include 'includes/header.php';
					include 'includes/reservations.php';
				}
			?>
		</div>
	</div>
</body>
</html>