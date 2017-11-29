<?php
	include 'includes/db.php';
?>

<!DOCTYPE html>
<html>
<?php include 'includes/head.php'; ?>
<body>
	<div id="wrapper">
		<?php
			$h2_nadpis = 'Registrace';
			include 'includes/header.php';
		?>
		<p>
			Zde se nachází jednoduchý formulář registrace nového uživatele.
			Tyto informace nebudeme nikde zveřejňovat. Slouží nám pouze k získání informací
			o klientovi, který vytváří objednávku a rezervaci. Jméno, příjmení, e-mail a heslo
			jsou pro nás důležité, a jsou zvyrazněné. Prosím, nezapomeňte tyto políčka vyplnit.
		</p>
		<?php
			include 'includes/registration_field.php';
		?>
		<div id="footer">
		</div>
	</div>
</body>
</html>
