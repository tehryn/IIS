<?php
	echo '<div class="registration">';
	$error_str = '<p class="error">';
	$prihlaseni_jmeno = "";
	// nacteni vsech parametru
	if ( isset( $_POST[ "prihlaseni_jmeno" ] ) ) {
		$prihlaseni_jmeno = trim( $_POST[ "prihlaseni_jmeno" ] );
	}
	if ( $prihlaseni_jmeno == "" ) {
		$error_str .= "Povinné pole 'Jméno' nebylo zadáno.<br>";
	}

	$prihlaseni_prijmeni = "";
	if ( isset( $_POST[ "prihlaseni_prijmeni" ] ) ) {
		$prihlaseni_prijmeni = trim( $_POST[ "prihlaseni_prijmeni" ] );
	}
	if ($prihlaseni_prijmeni == "" ) {
		$error_str .= "Povinné pole 'Příjmení' nebylo zadáno.<br>";
	}

	$prihlaseni_heslo = "";
	if ( isset( $_POST[ "prihlaseni_heslo" ] ) ) {
		$prihlaseni_heslo = trim( $_POST[ "prihlaseni_heslo" ] );
	}
	if ( $prihlaseni_heslo == "" ) {
		$error_str .= "Povinné pole 'Heslo' nebylo zadáno.<br>";
	}

	$prihlaseni_email = "";
	if ( isset( $_POST[ "prihlaseni_email" ] ) ) {
		$prihlaseni_email = trim( $_POST[ "prihlaseni_email" ] );
	}
	if ( $prihlaseni_email == "" ) {
		$error_str .= "Povinné pole 'Email' nebylo zadáno.<br>";
	}

	$prihlaseni_mesto = "";
	if ( isset( $_POST[ "prihlaseni_mesto" ] ) ) {
		$prihlaseni_mesto = trim( $_POST[ "prihlaseni_mesto" ] );
	}

	$prihlaseni_psc = "";
	if ( isset( $_POST[ "prihlaseni_psc" ] ) ) {
		$prihlaseni_psc = trim( $_POST[ "prihlaseni_psc" ] );
	}

	$prihlaseni_ulice = "";
	if ( isset( $_POST[ "prihlaseni_ulice" ] ) ) {
		$prihlaseni_ulice = trim( $_POST[ "prihlaseni_ulice" ] );
	}

	$prihlaseni_cislo_popisne = "";
	if ( isset( $_POST[ "prihlaseni_ulice" ] ) ) {
		$prihlaseni_cislo_popisne = trim( $_POST[ "prihlaseni_cislo_popisne" ] );
	}

	$error_str .= "</p>";
	if ( $error_str == '<p class="error"></p>' || !isset( $_POST[ "prihlaseni_submit" ] ) ) {
		$error_str = "";
	}

	// kontrola prihlaseni
	if ( $error_str == "" && isset( $_POST[ "prihlaseni_submit" ] ) ) {
		// kontrola duplicity
		$prihlaseni = mysql_query( "
			SELECT ID
			FROM iis_h_uzivatele
			WHERE email = '".$prihlaseni_email."'
		");
		if ( $prihlaseni && mysql_num_rows( $prihlaseni ) > 0 ) {
			echo '<p class="error">Tento email je jiz zaregistrován.</p>';
		}
		else {
			// vlozeni noveho uzivatele
			$insert = mysql_query("
				INSERT INTO iis_h_uzivatele( jmeno, prijmeni, email, heslo, mesto, ulice, cislo_popisne, psc )
				values(
					'".$prihlaseni_jmeno."',
					'".$prihlaseni_prijmeni."',
					'".$prihlaseni_email."',
					'".$prihlaseni_heslo."',
					'".$prihlaseni_mesto."',
					'".$prihlaseni_ulice."',
					'".$prihlaseni_cislo_popisne."',
				'".$prihlaseni_psc."'
				)
			");
			if ( !$insert ) {
				echo '
					<p class="error">Při registraci nastala neznámá chyba.
					Za tuto skutečnost se omlouváme a žádáme Vás, abyste se zkusil znovu
					zaregistrovat.</p>
				';
			}
			else {
				$prihlaseni = mysql_query( "
					SELECT *
					FROM iis_h_uzivatele
					WHERE email = '".$prihlaseni_email."' AND heslo='".$prihlaseni_heslo."'
				");
				$row = mysql_fetch_assoc( $prihlaseni );
				$_SESSION[ "user" ] = $row;
			}
		}
	}
	if( $_SESSION['user'] != "" ){
		header('Location: index.php');
	}
	else {
	// generovani obsahu stranky
	echo '
		<h3>Registrace:</h3>
		'.$error_str.'
		<form method="post">
			<table>
				<tbody>
					<tr>
						<td>
							Email (login):
						</td>
						<td>
							<input class="required" type="email" maxlength="127" name="prihlaseni_email" value="'.$prihlaseni_email.'" />
						</td>
					</tr>
					<tr>
						<td>
							Heslo:
						</td>
						<td>
							<input class="required" type="password" maxlength="127" name="prihlaseni_heslo" value="'.$prihlaseni_heslo.'" />
						</td>
					</tr>
					<tr>
						<td>
							Jméno:
						</td>
						<td>
							<input class="required" type="text" maxlength="127" name="prihlaseni_jmeno" value="'.$prihlaseni_jmeno.'" />
						</td>
					</tr>
					<tr>
						<td>
							Příjmení:
						</td>
						<td>
							<input class="required" type="text" maxlength="127" name="prihlaseni_prijmeni" value="'.$prihlaseni_prijmeni.'" />
						</td>
					</tr>
					<tr>
						<td>
							Město:
						</td>
						<td>
							<input type="text" name="prihlaseni_mesto" maxlength="127" value="'.$prihlaseni_mesto.'" />
						</td>
					</tr>
					<tr>
						<td>
							Ulice:
						</td>
						<td>
							<input type="text" name="prihlaseni_ulice" maxlength="127" value="'.$prihlaseni_ulice.'" />
						</td>
					</tr>
					<tr>
						<td>
							Číslo popisné:
						</td>
						<td>
							<input type="text" name="prihlaseni_cislo_popisne" maxlength="127" value="'.$prihlaseni_cislo_popisne.'" />
						</td>
					</tr>
					<tr>
						<td>
							PSČ:
						</td>
						<td>
							<input type="number" name="prihlaseni_psc" min="10000" max="99999" value="'.$prihlaseni_psc.'" />
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="submit" name="prihlaseni_submit" value="Registrovat" />
						</td>
					</tr>
				</tbody>
			</table>
		</form>'
	;
	echo '</div>';
}
?>
