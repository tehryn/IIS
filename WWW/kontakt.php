<?php
	include 'includes/db.php';
?>

<!DOCTYPE html>
<html>
<?php include 'includes/head.php'; ?>
<body>
	<div id="wrapper">
			<?php
				$h2_nadpis = 'Kontakt';
				include 'includes/header.php';
				include 'includes/login.php';
			?>
			<div id="kontakt">
				<h3>Restaurace Sunny Night</h3>
				<table class="neohranicena_tabulka">
					<tbody>
						<tr><td>Adresa:</td><td>Božetěchova 2, Brno</td></tr>
						<tr><td>Tel:</td><td>101 010 001</td></tr>
						<tr><td>E-mail:</td><td>info@sunny-night.cz</td></tr>
						<tr><td>IČO:</td><td>110 10 011</td></tr>
					</tbody>
				</table>
				<h4>Otevírací doba</h4>
				<table class="neohranicena_tabulka">
					<tbody>
						<tr><td>Pondělí</td><td>8:00 - 23:00</td></tr>
						<tr><td>Úterý</td><td>8:00 - 23:00</td></tr>
						<tr><td>Středa</td><td>8:00 - 23:00</td></tr>
						<tr><td>Čtvrtek</td><td>8:00 - 23:00</td></tr>
						<tr><td>Pátek</td><td>8:00 - 23:00</td></tr>
						<tr><td>Sobota</td><td>8:00 - 23:00</td></tr>
						<tr><td>Neděle</td><td>8:00 - 23:00</td></tr>
					</tbody>
				</table>
			</div>
			<div id="mapa">
				<iframe id="mapa_stredni" src="https://api.mapy.cz/frame?params=%7B%22x%22%3A16.597116348972207%2C%22y%22%3A49.22654394073814%2C%22base%22%3A%2227%22%2C%22layers%22%3A%5B%5D%2C%22zoom%22%3A16%2C%22url%22%3A%22https%3A%2F%2Fmapy.cz%2Fs%2F2dYyS%22%2C%22mark%22%3A%7B%22x%22%3A%2216.597116348972204%22%2C%22y%22%3A%2249.226543940738125%22%2C%22title%22%3A%22ulice%20Bo%C5%BEet%C4%9Bchova%201%2F2%2C%20Brno%22%7D%2C%22overview%22%3Afalse%7D&amp;width=500&amp;height=333&amp;lang=cs" width="500" height="333" style="border:none" frameBorder="0"></iframe>
				<iframe id="mapa_mala" src="https://api.mapy.cz/frame?params=%7B%22x%22%3A16.597116348972207%2C%22y%22%3A49.22654394073814%2C%22base%22%3A%2227%22%2C%22layers%22%3A%5B%5D%2C%22zoom%22%3A16%2C%22url%22%3A%22https%3A%2F%2Fmapy.cz%2Fs%2F2dYyS%22%2C%22mark%22%3A%7B%22x%22%3A%2216.597116348972204%22%2C%22y%22%3A%2249.226543940738125%22%2C%22title%22%3A%22ulice%20Bo%C5%BEet%C4%9Bchova%201%2F2%2C%20Brno%22%7D%2C%22overview%22%3Afalse%7D&amp;width=400&amp;height=280&amp;lang=cs" width="400" height="280" style="border:none" frameBorder="0"></iframe>
			</div>
			<div id="footer">
			</div>
	</div>
</body>
</html>
