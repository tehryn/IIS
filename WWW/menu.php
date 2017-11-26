<?php
	include 'includes/db.php';
?>

<!DOCTYPE html>
<html>
<?php include 'includes/head.php'; ?>
<body>
	<div id="wrapper">
		<?php
			$h2_nadpis = 'Nabídka jídel';
			include 'includes/header.php';
			include 'includes/login.php';
			?>
		<p>
			Zde můžežete vidět naši nabídku jídel.
			Zaměřujeme se především na upravované ryby všeho druhu.
			Preferujeme ryby tuzemské, avšak v našem jídelníčku
			můžete najít i ryby mořské.
		</p>
		<?php
		include 'includes/food_menu.php';
		?>
		<div id="footer">
		</div>
	</div>
</body>
</html>
