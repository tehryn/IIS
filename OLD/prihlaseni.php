<?php
	include 'db.php';
	$email = "";
	if ( isset( $_POST[ 'prihlaseni_email' ] ) ) {
		$email = trim( $_POST[ 'prihlaseni_email' ] );
	}

	$heslo = "";
	if ( isset( $_POST[ 'prihlaseni_heslo' ] ) ) {
		$heslo = trim( $_POST[ 'prihlaseni_heslo' ] );
	}

	$error_str = "";
	if ( isset( $_POST[ 'prihlaseni_submit' ] ) ) {
		if ( $email == "" || $heslo == "" ) {
			$error_str = '
			<p class="error">
				Nezadal jste přihlašovací údaje.
			</p> ';
		}
		else {
			$prihlaseni = mysql_query( "
				SELECT ID
				FROM iis_h_uzivatele
				WHERE email = '".$email."' AND heslo='".$heslo."'
			");
			if ( $prihlaseni && mysql_num_rows( $prihlaseni ) > 0 ) {
				$row = mysql_fetch_assoc( $prihlaseni );
				$_SESSION[ "user" ] = $row[ 'ID' ];
			} else {
				$error_str = '
					<p class="error">
						Zadal jste chybný email nebo heslo.
					</p> ';
			}
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Sunny Night</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<div id="wrapper">
		<div id="header" >
			<div id="logo">
				<h1><a><span>Sunny Night</span></a></h1>
			</div>
			<div id="menu-wrapper">
				<div id="menu">
					<a href="./index.php">Domu</a>
					<span class="sep"> | </span>
					<a href="./menu.php">Menu</a>
					<span class="sep"> | </span>
					<a href="./rezervace.php">Rezervace</a>
					<span class="sep"> | </span>
					<a href="./kontakt.php">Kontakt</a>
					<span class="sep"> | </span>
					<a href="./prihlaseni.php">Přihlášení</a>
					<span class="sep"> | </span>
					<a href="./registrace.php">Registrace</a>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<h2> Vítejte v restauraci Sunny Night! </h2>
		<?php
			if ( $_SESSION[ 'user' ] == "" ) {
				echo '
					<h3>Přihlášení:</h3>
					'.$error_str.'
					<form method="post">
					<table>
						<tbody>
							<tr>
								<td>
									Email:
								</td>
								<td>
									<input class="required" type="text" name="prihlaseni_email" />
								</td>
							</tr>
							<tr>
								<td>
									Heslo:
								</td>
								<td>
									<input class="required" type="password" name="prihlaseni_heslo" />
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<input type="submit" name="prihlaseni_submit" value="Přihlásit" />
								</td>
							</tr>
						</tbody>
					</table>
					</form>
				';
			}
			else {
				echo '
					<span>
						Jste přihlášen, přejete si
						<a href="./logout.php?">odhlásit se</a>?
					</span>
				';
			}
		?>
		<div id="footer">
		</div>
</body>
</html>