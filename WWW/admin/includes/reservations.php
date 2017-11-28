<?php
	$pravo = $_SESSION['user']['pravo'];
	if ( !($pravo == 'zamestnanec' || $pravo == 'spravce' || $pravo == 'vedouci') ) {
		header('Location: index.php');
	}
	else {
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

		$jmeno = (isset($_POST['jmeno'])?trim($_POST['jmeno']):"");
		$kontakt = (isset($_POST['kontakt'])?trim($_POST['kontakt']):"");
		if ( $jmeno == '' && isset( $_POST['submit_rezervace_potvrdit'] ) ) {
			echo '<p class="error"> Povinné pole \'Na jméno\' není zadáné.</p>';
		}
		if ( $kontakt == '' && isset( $_POST['submit_rezervace_potvrdit'] ) ) {
			echo '<p class="error"> Povinné pole \'Kontakt\' není zadáné.</p>';
		}

		$od = $rezervace_datum." ".$rezervace_od.":00";
		$do = $rezervace_datum." ".$rezervace_do.":00";
		$vse_zadano = false;
		if ( $rezervace_od && $rezervace_do && $rezervace_datum && $jmeno && $kontakt && ( isset( $_POST[ 'submit_rezervace_potvrdit' ] ) || isset( $_POST[ 'rezervovat' ] ) ) ) {
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

		if ( $vse_zadano && isset( $_POST['rezervovat']) ) {
			$rezervovano = false;
			if ( isset( $_POST['rezervace_check'] ) ) {
				$zamestnanec = $_SESSION['user']['ID'];
				mysql_query("START TRANSACTION");
				foreach( $_POST['rezervace_check'] as $stul ) {
					$rezervovano = true;
					$ok = mysql_query( "
						INSERT INTO iis_h_rezervace(jmeno, kontakt, zamestnanec, stul, odkdy, dokdy)
						values( '$jmeno', '$kontakt', $zamestnanec, $stul, '$od', '$do' )
					");
					if (!$ok) {
						mysql_query("ROLLBACK");
						$rezervovano = false;
						echo '<p class="error">Zadal/a jste neplatné údaje</p>';
						break;
					}
				}
				if ( $rezervovano ) {
					mysql_query("COMMIT");
					echo '<p class="ok">Rezervace vytvořena</p>';
				}
			}
		}

		if ( isset( $_POST['zrusit_rezervaci_check'] ) ) {
			foreach( $_POST['zrusit_rezervaci_check'] as $zrusit_id ) {
				mysql_query( "
					DELETE FROM iis_h_rezervace
					WHERE ID = ".$zrusit_id
				);
			}
		}

		echo '
			<h3>Nová rezervace</h3>
			Zde můžete vyplnit rezervaci uživatele, který nemusí být zaregistrován.
			Existující rezervace
			Po zadání časového intervalu se vykreslí tabulka obsahující veškeré rezervace.
			<form method="post">
				<table>
					<tbody>
						<tr><td>Na jméno:</td><td><input name="jmeno" type="text" maxlength="127" class="required" value="'.$jmeno.'"></td>
						<tr><td>Kontakt:</td><td><input name="kontakt" type="text" maxlength="127" class="required" value="'.$kontakt.'"></td>
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
					$name = "rezervace_check[]";
					echo "<tr>";
						echo '<td><label><input type="checkbox" name="'.$name.'" value="'.$row[ "ID" ].'"></label></td>';
						echo "<td>".$row[ "ID" ]."</td>";
						echo "<td>".$row[ "lokace" ]."</td>";
						echo "<td>".$row[ "pocet_zidli" ]."</td>";
					echo "</tr>";
				}
			} else {
				echo '<tr><td colspan="4" class = "no_data_in_table">V zadaný čas nejsou žádná volná místa.</td></tr>';
			}
			echo '
					</tbody>
				</table>
				<input type="hidden" name="rezervace_od" value="'.$rezervace_od.'" />
				<input type="hidden" name="rezervace_do" value="'.$rezervace_do.'" />
				<input type="hidden" name="jmeno" value="'.$jmeno.'" />
				<input type="hidden" name="kontakt" value="'.$kontakt.'" />
				<input type="hidden" name="rezervace_datum" value="'.$rezervace_datum.'" />
				<input type="hidden" name="potvrdit" value="1" />
				<input type="submit" name="rezervovat" value="Rezervovat" />
				</form>'
			;
		}

		$datetime = new DateTime('today');
		$od = $datetime->format('Y-m-d');
		$do = $od;
		if ( isset($_POST['storno_odkdy']) ) {
			$od = trim( $_POST['storno_odkdy'] );
		}
		if ( $od == "" ) {
			echo '<p class="error"> Povinné pole \'Od kdy\' není zadáné.</p>';
		}
		if ( isset($_POST['storno_dokdy']) ) {
			$do = trim( $_POST['storno_dokdy'] );
		}
		if ( $do == "" ) {
			echo '<p class="error"> Povinné pole \'Do kdy\' není zadáné.</p>';
		}
		$vse_zadano = false;
		if ( $do != '' && $od != '' && isset( $_POST['potvrdit'] ) ) {
			$od_int = strtotime($od.' 00:00:00');
			$do_int = strtotime($do.' 23:59:59');
			$vse_zadano = true;
			if ( $od_int >= $do_int ) {
				echo '<p class="error">
					Čas \'Od kdy\' je větší nebo rovno než čas \'Do kdy\'.
					</p>'
				;
				$vse_zadano = false;
			}
		}
		echo '<h3>Existujici rezervace</h3>';
		echo '<form method="post"><table class="neohranicena_tabulka"><tbody>';
		echo '<tr><td>Od kdy:</td><td><input type="date" name="storno_odkdy" min="'.$datetime->format('Y-m-d').'" class="required" value="'.$od.'"></td></tr>';
		echo '<tr><td>Do kdy:</td><td><input type="date" name="storno_dokdy" min="'.$datetime->format('Y-m-d').'" class="required" value="'.$do.'"></td></tr>';
		echo '</tbody></table>';
		echo '<input type="submit" value="Potvrdit" name="potvrdit">';
		echo '</form>';
		if ( $vse_zadano ) {
			$rezervace_data = mysql_query( "
				SELECT iis_h_stul.ID AS stul_id, iis_h_rezervace.ID, lokace, pocet_zidli, jmeno, kontakt,
				DATE_FORMAT(odkdy, '%d. %m. %Y %H:%i') AS odkdy, DATE_FORMAT(dokdy, '%H:%i') as dokdy,
				jmeno, kontakt
				FROM iis_h_stul, iis_h_rezervace
				WHERE iis_h_rezervace.stul = iis_h_stul.ID
					AND iis_h_rezervace.odkdy >= '$od 00:00:00'
					AND iis_h_rezervace.dokdy <= '$do 23:59:59'
				order by odkdy
			");

			if ( $rezervace_data ) {
				echo '
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
									Jméno
								</td>
								<td>
									Kontakt
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
							echo "<td>".$row[ "jmeno" ]."</td>";
							echo "<td>".$row[ "kontakt" ]."</td>";
							echo "<td>".$row[ "lokace" ]."</td>";
							echo "<td>".$row[ "pocet_zidli" ]."</td>";
							echo "<td>".$row[ "odkdy" ]." - ".$row[ "dokdy" ]."</td>";
						echo "</tr>";
					}
				} else {
					echo '<tr><td colspan="7" class ="no_data_in_table">Aktuálně němáte žádné rezervace.</td></tr>';
				}

				echo '
						</tbody>
					</table>
					<input type="hidden" name="storno_odkdy" value="'.$od.'" />
					<input type="hidden" name="storno_dokdy" value="'.$do.'" />
					<input type="hidden" name="potvrdit" value="1" />
				';
				if ( mysql_num_rows( $rezervace_data ) > 0 ) {
					echo '<input type="submit" name="submit_rezervace_zrusit" value="Stornovat" />';
				}
				echo '</form>';
			}
			else {
				echo '<p class="error">Zadal/a jste neplatná data.</p>';
			}
		}
	}
?>