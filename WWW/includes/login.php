<?php
	echo '<div class="login">';
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
				SELECT *
				FROM iis_h_uzivatele
				WHERE email = '".$email."' AND heslo='".$heslo."'
			");
			if ( $prihlaseni && mysql_num_rows( $prihlaseni ) > 0 ) {
				$row = mysql_fetch_assoc( $prihlaseni );
				$_SESSION[ "user" ] = $row;
				header("Refresh:0");
			} else {
				$error_str = '
					<p class="error">
						Zadal jste chybný email nebo heslo.
					</p> ';
			}
		}
	}
	if ( $_SESSION['user'] == "" ) {
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
				</tbody>
			</table>
			<input type="submit" name="prihlaseni_submit" value="Přihlásit" />
			</form>
		';
	}
	else {
		echo '
			<div id="logout"> <a class="button" href="./odhlaseni.php"> Odhlásit se </a> </div>
			<div id="userinfo"> Přihlášen jako <span class="jmeno_prijmeni">'.$_SESSION['user']['jmeno'].'  '.$_SESSION['user']['prijmeni'].'</span>
			</div>
		';
	}
	echo '</div>';
?>