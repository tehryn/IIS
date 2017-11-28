<?php
	echo '<div class=rezervation>';
	$pocet_rezervaci = 0;
	if ( isset( $_POST[ 'submit_rezervace_zrusit' ] ) && isset( $_POST[ 'zrusit_rezervaci_check' ] ) ) {
		foreach( $_POST['zrusit_rezervaci_check'] as $zrusit_id ) {
			mysql_query( "
				DELETE FROM iis_h_rezervace
				WHERE id = ".$zrusit_id
			);
		}
	}
	if ( $_SESSION[ 'user' ] == "" ) {
		if ( isset( $_POST[ 'submit_rezervace_rezervovat' ] ) ) {
			echo '<p class="error">
				Pro vytvoření rezervace se musíte přihlásit. Pokud nemáte
				vytvořený účet, <a href="./registrace.php">zaregistrujte se</a>.
				</p>'
			;
		}
		else {
			echo '<p class="warning">
				Pro vytvoření rezervace se musíte přihlásit. Pokud nemáte
				vytvořený účet, <a href="./registrace.php">zaregistrujte se</a>.
				</p>'
			;
		}
	}
	else {
		$rezervace_pocet = mysql_query( "
			SELECT COUNT(1) AS pocet
			FROM iis_h_stul, iis_h_rezervace
			WHERE iis_h_rezervace.stul = iis_h_stul.ID
				AND iis_h_rezervace.odkdy > NOW()
				AND uzivatel = ".$_SESSION['user']['ID']."
		");
		if ( $rezervace_pocet && mysql_num_rows( $rezervace_pocet ) ) {
			$row = mysql_fetch_assoc( $rezervace_pocet );
			$pocet_rezervaci = $row[ 'pocet' ];
		}
	}

	$rezervace_datum = date("Y-m-d");
	$error_str = '<p class="error">';
	if ( isset( $_POST[ "rezervace_datum" ] ) ) {
		$rezervace_datum = trim( $_POST[ "rezervace_datum" ] );
	}
	if ( $rezervace_datum == "" ) {
		echo "<p class=\"error\">Povinné pole 'Datum rezervace' nebylo zadáno.</p>";
	}

	$rezervace_od = date("H:i");
	if ( isset( $_POST[ "rezervace_od" ] ) ) {
		$rezervace_od = trim( $_POST[ "rezervace_od" ] );
	}
	if ( $rezervace_od == "" ) {
		echo "<p class=\"error\">Povinné pole 'Od kdy' nebylo zadáno.</p>";
	}

	$rezervace_do = "23:00";
	if ( isset( $_POST[ "rezervace_do" ] ) ) {
		$rezervace_do = trim( $_POST[ "rezervace_do" ] );
	}
	if ( $rezervace_do == "" ) {
		echo "<p class=\"error\">Povinné pole 'Do kdy' nebylo zadáno.</p>";
	}

	$od = $rezervace_datum." ".$rezervace_od.":00";
	$do = $rezervace_datum." ".$rezervace_do.":00";
	$vse_zadano = false;
	if ( $rezervace_od && $rezervace_do && $rezervace_datum && ( isset( $_POST[ 'submit_rezervace_potvrdit' ] ) || isset( $_POST[ 'submit_rezervace_rezervovat' ] ) ) ) {
		$od_int = strtotime($od);
		$do_int = strtotime($do);
		$vse_zadano = true;
		if ( $od_int >= $do_int ) {
			echo '<p class="error">
				Čas \'Od kdy\' je větší nebo rovno než čas \'Do kdy\'.
				</p>'
			;
			$vse_zadano = false;
		}
		if ( $od_int < time() && $rezervace_datum == date("Y-m-d") ) {
			echo '<p class="error">
				Čas \'Od kdy\' musí být zadán v budoucnosti.
				</p>'
			;
			$vse_zadano = false;
		}
	}

	if ( isset( $_POST[ 'submit_rezervace_rezervovat' ] ) && $vse_zadano  ) {
		$rezervace_stul = "";
		if ( isset( $_POST[ "rezervace_check" ] ) ) {
			$rezervace_stul = trim( $_POST[ "rezervace_check" ] );
		}
		if ( $rezervace_stul == "" ) {
			echo "<p class=\"error\">Nebyl vybrán stůl k rezervaci.</p>";
		}
		elseif ( $_SESSION[  'user' ] != "" && !$_SESSION[ 'refresh' ] && $pocet_rezervaci <= 2 ) {
			$jmeno = $_SESSION['user']['jmeno'].' '.$_SESSION['user']['prijmeni'];
			$kontakt = $_SESSION['user']['email'];
			$uzivatel = $_SESSION[ 'user' ][ "ID" ];
			$ok = mysql_query( "
				INSERT INTO iis_h_rezervace
				(odkdy, dokdy, uzivatel, stul, jmeno, kontakt)
				values( STR_TO_DATE( '$od', '%Y-%m-%d %H:%i:%s'), STR_TO_DATE( '$do', '%Y-%m-%d %H:%i:%s'), $uzivatel, $rezervace_stul, '$jmeno', '$kontakt' )
			" );
			if ( $ok ) {
				$pocet_rezervaci++;
				echo '<p class="ok">Rezervace proběhla úspěšně.</p>';
			}
			else {
				echo '<p class="error">Zadal/a jste nesprávné údaje.</p>';
			}
		}
	}

	if ( $pocet_rezervaci > 2 && isset( $_POST[ 'submit_rezervace_rezervovat' ] ) ) {
		echo '<p class="error">Pokud si chcete rezervovat více jak 3 stoly, rezervaci proveďte buď osobně nebo nás kontaktujte.</p>';
	}
	else {
		echo '<p class="warning">Pokud si chcete rezervovat více jak 3 stoly, rezervaci proveďte buď osobně nebo nás kontaktujte.</p>';
	}
	echo '
		<h3>Nová rezervace</h3>
		<h4>Datum a čas rezervace</h4>
		<form action="#" method="post">
			<table>
				<tbody>
					<tr>
							<td>
								Datum rezervace:
							</td>
							<td>
								<input class="required" type="date" name="rezervace_datum" min="'.date("Y-m-d").'" value="'.$rezervace_datum.'" />
							</td>
					</tr>
					<tr>
							<td>
								Od kdy:
							</td>
							<td>
								<input class="required" type="time" name="rezervace_od" min="08:00:00" max="22:00:00" value="'.$rezervace_od.'" /><br />
							</td>
					</tr>
					<tr>
							<td>
								Do kdy:
							</td>
							<td>
								<input class="required" type="time" name="rezervace_do" min="08:00:00" max="23:00:00" value="'.$rezervace_do.'" /><br />
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
	';
	if ( $vse_zadano ) {
		echo '
			<h4>Volné stoly na den '.DateTime::createFromFormat('Y-m-d', $rezervace_datum)->format('d. m. Y').' od '.$rezervace_od.' do '.$rezervace_do.' hodin</h4>
			<form  action="#" method="post">
			<table class="ohranicena_tabulka">
				<thead>
					<tr>
						<th><span>Ozn.</span></th>
						<th><span>Číslo stolu</span></th>
						<th><span>Lokace</span></th>
						<th><span>Počet židlí</span></th>
					</tr>
				</thead>
				<tbody>
		';
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
			order by ID
		");
		if ( $rezervace_data && mysql_num_rows( $rezervace_data ) > 0 ) {
			while( $row = mysql_fetch_assoc( $rezervace_data ) ) {
				$name = "rezervace_check";
				$disabled = $pocet_rezervaci > 2 ? "disabled":"";
				echo "<tr>";
					echo '<td><label><input type="radio" name="'.$name.'" value="'.$row[ "ID" ].'" '.$disabled.'></label></td>';
					echo "<td>".$row[ "ID" ]."</td>";
					echo "<td>".$row[ "lokace" ]."</td>";
					echo "<td>".$row[ "pocet_zidli" ]."</td>";
				echo "</tr>";
			}
		} else {
			echo '<tr><td colspan="4" class = "no_data_in_table">Je nám líto, ale v zadaný čas jsou všechny stoly již zarezervované.</td></tr>';
		}
		echo '
				</tbody>
			</table>
			<input type="hidden" name="rezervace_od" value="'.$rezervace_od.'" />
			<input type="hidden" name="rezervace_do" value="'.$rezervace_do.'" />
			<input type="hidden" name="rezervace_datum" value="'.$rezervace_datum.'" />
			<input type="submit" name="submit_rezervace_rezervovat" value="Rezervovat" />
			</form>';
	}

	if ( $_SESSION[ 'user' ] != "" ) {
		$rezervace_data = mysql_query( "
			SELECT iis_h_stul.ID AS stul_id, iis_h_rezervace.ID, lokace, pocet_zidli, DATE_FORMAT(odkdy, '%d. %m. %Y %H:%i') AS odkdy, DATE_FORMAT(dokdy, '%H:%i') as dokdy
			FROM iis_h_stul, iis_h_rezervace
			WHERE iis_h_rezervace.stul = iis_h_stul.ID
				AND iis_h_rezervace.odkdy > NOW()
				AND uzivatel = ".$_SESSION['user']['ID']."
			order by odkdy
		");
		if ( $rezervace_data ) {
			echo '
				<h3>Vaše rezervace</h3>
				<form method="post">
				<table class="ohranicena_tabulka">
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
								Datum a čas
							</td>
						</tr>
					</thead>
					<tbody>
			';
			if ( $rezervace_data && mysql_num_rows( $rezervace_data ) > 0 ) {
				while( $row = mysql_fetch_assoc( $rezervace_data ) ) {
					$name = "zrusit_rezervaci_check[]";
					echo "<tr>";
						echo '<td><label><input type="checkbox" name="'.$name.'" value="'.$row[ "ID" ].'"></label></td>';
						echo "<td>".$row[ "stul_id" ]."</td>";
						echo "<td>".$row[ "lokace" ]."</td>";
						echo "<td>".$row[ "pocet_zidli" ]."</td>";
						echo "<td>".$row[ "odkdy" ]." - ".$row[ "dokdy" ]."</td>";
					echo "</tr>";
				}
			} else {
				echo '<tr><td colspan="5" class ="no_data_in_table">Aktuálně němáte žádné rezervace.</td></tr>';
			}

			echo '
					</tbody>
				</table>
				<input type="hidden" name="rezervace_od" value="'.$rezervace_od.'" />
				<input type="hidden" name="rezervace_do" value="'.$rezervace_do.'" />
				<input type="hidden" name="rezervace_datum" value="'.$rezervace_datum.'" />
			';
			if ( mysql_num_rows( $rezervace_data ) > 0 ) {
				echo '<input type="submit" name="submit_rezervace_zrusit" value="Stornovat" />';
			}
			if ( isset($_POST[ 'submit_rezervace_rezervovat' ] ) || isset( $POST[  'submit_rezervace_potvrdit' ] ) ) {
				echo '
					<input type="hidden" name="submit_rezervace_potvrdit" value="1" />
				</form>';
			}
			else {
				echo '</form>';
			}
		}
	}
	echo '</div>';
?>
