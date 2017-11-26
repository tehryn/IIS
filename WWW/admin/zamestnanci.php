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
				include 'includes/header.php';
				if ( $_SESSION['user'] == '' ) {
					header( 'Location: index.php' );

				}
				elseif ( $_SESSION['user']['pravo'] == 'zakaznik') {
					header( 'Location: ../index.php' );
				}
				elseif ( preg_match('/spravce|vedouci/i', $_SESSION['user']['pravo'])	 ) {
					include 'includes/employees.php';
				}
				else {
					header( 'Location: index.php' );
				}
			?>
		</div>
	</div>
</body>
</html>