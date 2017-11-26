<?php
	include 'includes/db.php';
?>

<!DOCTYPE html>
<html>
<?php include 'includes/head.php'; ?>
<body>
	<div id="wrapper">
		<?php
			$h2_nadpis = 'Rezervace';
			include 'includes/header.php';
			include 'includes/login.php';
		?>
		<p>
			Zde se nachází formulář k rezervaci stolů.
			Nejprve prosím vyplňte, na kdy si přejete vytvořit rezervaci.
			Následně se vám objeví tabulka s čísly stolů, které lze v daný termín rezervovat.
			Zde si postupně můžete vybrat až 3 stoly.
			Pro objednávku s více než 3 stoly proveďte rezervaci osobně nebo nás kontaktujte e-mailem nebo telefonicky.
		</p>
		<?php
			include 'includes/create_rezervation.php';
		?>
		<div id="footer">
		</div>
	</div>
</body>
</html>
