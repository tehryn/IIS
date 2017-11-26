<?php
	include 'db.php';
	$rezervace_datum = date("Y-m-d");
	$error_str = '<p class="error">';
	if ( isset( $_POST[ "rezervace_datum" ] ) ) {
		$rezervace_datum = trim( $_POST[ "rezervace_datum" ] );
	}
	if ( $rezervace_datum == "" ) {
		$error_str .= "Povinné pole 'Datum rezervace' nebylo zadáno.<br>";
	}

	$rezervace_od = date("h:i");
	if ( isset( $_POST[ "rezervace_od" ] ) ) {
		$rezervace_od = trim( $_POST[ "rezervace_od" ] );
	}
	if ( $rezervace_od == "" ) {
		$error_str .= "Povinné pole 'Od kdy' nebylo zadáno.<br>";
	}

	$rezervace_do = "20:00";
	if ( isset( $_POST[ "rezervace_do" ] ) ) {
		$rezervace_do = trim( $_POST[ "rezervace_do" ] );
	}
	if ( $rezervace_do == "" ) {
		$error_str .= "Povinné pole 'Do kdy' nebylo zadáno.<br>";
	}

	$error_str .= "</p>";
	if ( $error_str == '<p class="error"></p>' || !isset( $_POST[ 'submit_rezervace_potvrdit' ] ) ) {
		$error_str = "";
	}

	$od = $rezervace_datum." ".$rezervace_od.":00";
	$do = $rezervace_datum." ".$rezervace_do.":00";
	if ( $error_str == ""  && isset( $_POST[ 'submit_rezervace_potvrdit' ] ) ) {
		$od_int = strtotime($od);
		$do_int = strtotime($do);
		if ( $od_int >= $do_int ) {
			$error_str = '<p class="error">
				čas \'Od kdy\' je větší nebo rovno než čas \'Do kdy\'.
				</p>'
			;
		}
		if ( $od_int < time() && $rezervace_datum == date("Y-m-d") ) {
			$error_str = '<p class="error">
				čas \'Od kdy\' musí být zadán v budoucnosti.
				</p>'
			;
		}
	}


	if ( isset( $_POST[ 'submit_rezervace_rezervovat' ] ) && $error_str == "" ) {
		$rezervace_stul = "";
		if ( isset( $_POST[ "rezervace_check" ] ) ) {
			$rezervace_stul = trim( $_POST[ "rezervace_check" ] );
		}
		if ( $rezervace_stul == "" ) {
			$error_str .= "Nebyl vybrán stůl k rezervaci.<br>";
		}
		if ( $_SESSION[  'user' ] != "" ) {
			$ok = mysql_query( "
				INSERT INTO iis_h_rezervace
				(odkdy, dokdy, uzivatel, stul)
				values( STR_TO_DATE( '".$od."', '%Y-%m-%d %H:%i:%s'), STR_TO_DATE( '".$do."', '%Y-%m-%d %H:%i:%s'), ".$_SESSION[ 'user' ].", ".$rezervace_stul."   )
			" );
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Rezervace</title>
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
		<h2>Rezervace</h2>
		<?php
			if ( $_SESSION[ 'user' ] == "" ) {
				if ( isset( $_POST[ 'submit_rezervace_rezervovat' ] ) ) {
					echo '<p class="error">
						Pro vytvoření rezervace se musíte <a href="./prihlaseni.php" targe="blank">přihlásit</a>. Pokud nemáte
						vytvořený účet, <a href="./registrace.php">zaregistrujte se</a>.
						</p>'
					;
				}
				else {
					echo '<p class="warning">
						Pro vytvoření rezervace se musíte <a href="./prihlaseni.php" targe="blank">přihlásit</a>. Pokud nemáte
						vytvořený účet, <a href="./registrace.php">zaregistrujte se</a>.
						</p>'
					;
				}

			}
			if (!( $rezervace_od && $rezervace_do && $rezervace_datum && $error_str == "" )) {

				echo "<p class=\"warning\">
						K rezervaci stolu je nejprve nutné potvrdit zadané datum a čas.
					</p>";
			}
			echo $error_str;
		?>
		<h3>Nová rezervace</h3>
		<form action="#" method="post">
			<table>
				<tbod>
					<tr>
							<td>
								Datum rezervace:
							</td>
							<td>
								<input class="required" type="date" name="rezervace_datum" min="<?php echo date('Y-m-d');?>" value="<?php echo "$rezervace_datum"; ?>" />
							</td>
					</tr>
					<tr>
							<td>
								Od kdy:
							</td>
							<td>
								<input class="required" type="time" name="rezervace_od" min="08:00:00" max="20:00:00" value="<?php echo "$rezervace_od"; ?>" /><br />
							</td>
					</tr>
					<tr>
							<td>
								Do kdy:
							</td>
							<td>
								<input class="required" type="time" name="rezervace_do" min="08:00:00" max="20:00:00" value="<?php echo "$rezervace_do"; ?>" /><br />
							</td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="submit" name="submit_rezervace_potvrdit" value="Potvrdit" />
						</td>
					</tr>
				</tbod>
			</table>
		</form>
		<form  action="#" method="post">
		<table>
			<thead>
				<tr>
					<th><span>Ozn.</span></th>
					<th><span>Číslo stolu</span></th>
					<th><span>Lokace</span></th>
					<th><span>Počet židlí</span></th>
				</tr>
			</thead>
			<tbody>
				<?php
					$rezervace_data = false;
					if ( isset( $_POST[ 'submit_rezervace_potvrdit' ] ) && $rezervace_do != "" && $rezervace_od != "" && $rezervace_datum != "" ) {
						$rezervace_data = mysql_query( "
							SELECT iis_h_stul.ID, lokace, pocet_zidli
							FROM iis_h_stul
							WHERE ID NOT IN (
								SELECT DISTINCT stul
								FROM iis_h_rezervace
								WHERE
									( STR_TO_DATE( '".$od."', '%Y-%m-%d %H:%i:%s') BETWEEN odkdy AND dokdy ) OR
									( STR_TO_DATE( '".$od."', '%Y-%m-%d %H:%i:%s') BETWEEN odkdy AND dokdy )
							)
							order by pocet_zidli, iis_h_stul.ID, lokace
						");
					}
					else {
						$rezervace_data = mysql_query( "
						SELECT iis_h_stul.ID, lokace, pocet_zidli
						FROM iis_h_stul
						order by pocet_zidli, iis_h_stul.ID, lokace
						");
					}
					if ( $rezervace_data && mysql_num_rows( $rezervace_data ) > 0 ) {
						while( $row = mysql_fetch_assoc( $rezervace_data ) ) {
							$name = "rezervace_check";
							echo "<tr>";
								if ( $rezervace_od && $rezervace_do && $rezervace_datum && $error_str == "" ) {
									echo '<td><label><input type="radio" name="'.$name.'" value="'.$row[ "ID" ].'" '.( isset( $_POST[ $name ] ) ? "checked" : "" ).'></label></td>';
								}
								else {
									echo '<td><label><input type="radio" name="'.$name.'" value="'.$row[ "ID" ].'" disabled></label></td>';
								}
								echo "<td>".$row[ "ID" ]."</td>";
								echo "<td>".$row[ "lokace" ]."</td>";
								echo "<td>".$row[ "pocet_zidli" ]."</td>";
							echo "</tr>";
						}
					} else {
						echo '<tr><td colspan="4" class = "no_data_in_table">Je nám líto, ale v zadaný čas jsou všechny stoly již zarezervované.</td></tr>';
					}
				?>
			</tbody>
		</table>
		<?php
			echo '
				<h3>Vaše rezervace</h3>
				<table>
					<thead>
						<tr>
							<td>
								Ozn.
							</td>
							<td>
								Číslo stolu
							</td>
							<td>
								Lokace
							</td>
							<td>
								Počet židlí
							</td>
							<td>
								Odkdy
							</td>
							<td>
								Dokdy
							</td>
						</tr>
					</thead>
			';
			$vase_rezervace = mysql_query ( "
				SELECT ID, odkdy, dokdy,
			");
		?>
		<input type="hidden" name="rezervace_od" value="<?php echo $rezervace_od;?>" />
		<input type="hidden" name="rezervace_do" value="<?php echo $rezervace_do;?>" />
		<input type="hidden" name="rezervace_datum" value="<?php echo $rezervace_datum;?>" />
		<input type="submit" name="submit_rezervace_rezervovat" value="Rezervovat" />
		</form>

		<div id="footer">
		</div>
</body>
</html>