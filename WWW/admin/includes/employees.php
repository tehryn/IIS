<?php
	$pravo = $_SESSION['user']['pravo'];
	// kontrola prav
	if ( $pravo == 'spravce' || $pravo == 'vedouci' ) {
		echo '<div class="zamestnanci">';
		// mazani zamestnance
		if ( isset( $_POST['zrusit'] ) && isset( $_POST['propustit_zamestnance'] ) ) {
			$uvazky = trim( $_POST['zrusit'] );
			foreach( $_POST['propustit_zamestnance'] as $zrusit_id ) {
				mysql_query( "
					DELETE FROM iis_h_zamestnanec
					WHERE ID = ".$zrusit_id
				);
		 	}
		}
		// editace zamestnance
		if ( isset( $_GET['editace'] ) ) {
			echo '<h3>Editace zaměstnance</h3>';
			$id = trim( $_GET['editace'] );
			// kontrola vstupnich parametru
			if ( isset( $_POST['potvrdit'] ) && isset( $_POST['uzivatel'] ) ) {
				$uzivatel = trim( $_POST['uzivatel'] );
				$cislo_uctu = isset( $_POST['cislo_uctu'] ) && trim( $_POST['cislo_uctu'] ) != "" ? trim( $_POST['cislo_uctu'] ) : "NULL";
				$plat = isset( $_POST['plat'] ) ? trim( $_POST['plat'] ) : "";
				$pracovni_pozice = isset( $_POST['pracovni_pozice'] ) ? trim( $_POST['pracovni_pozice'] ) : "";
				$rodne_cislo = isset( $_POST['rodne_cislo'] ) ? trim( $_POST['rodne_cislo'] ) : "";
				$opravneni = isset( $_POST['opravneni'] ) ? trim( $_POST['opravneni'] ) : "";
				// aktualizace databaze
				if ( $pracovni_pozice != "" && $plat != "" && $rodne_cislo != "" ) {
					$ok = mysql_query( "
						UPDATE iis_h_zamestnanec
						SET
							plat            = $plat,
							pracovni_pozice = '$pracovni_pozice',
							cislo_uctu      = $cislo_uctu,
							rodne_cislo     = $rodne_cislo
						WHERE ID = $id
					");
					// kontrola updatu
					if ( !$ok ) {
						echo '<p class="error">Zadal jste neplatné údaje.</p>';
					}
					else {
						if ( $_SESSION['user']['pravo'] == 'spravce' ) {
							mysql_query("
								UPDATE iis_h_uzivatele
								SET pravo = '$opravneni'
								WHERE id = $uzivatel
							");
						}
						echo '<p class="ok">Data byla aktualizována.</p>';
					}
				}
				// nezadani uplnych dat
				else {
					if ( $pracovni_pozice == "" ) {
						echo '<p class="error">Pracovní pozice nebyla specifikována.</p>';
					}
					if ( $rodne_cislo == "" ) {
						echo '<p class="error">Rodné číslo nebylo zadáno.</p>';
					}
					if ( $plat == "" ) {
						echo '
							<p class="error">
								Plat nebyl zadán. Pokud zaměstnanec nepobírá žádný plat,
								zadejte alespoň 0.
							</p>
						';
					}
				}
			}
			// zobrazeni dat zamestnance
			$data_zamestnance = mysql_query("
				SELECT iis_h_zamestnanec.ID, jmeno, prijmeni, email, rodne_cislo, pracovni_pozice,
					cislo_uctu, plat, mesto, ulice, cislo_popisne, psc, pravo, uzivatel
				FROM iis_h_zamestnanec, iis_h_uzivatele
				WHERE uzivatel = iis_h_uzivatele.ID AND iis_h_zamestnanec.ID = $id
			");
			// evidovana prava
			$data_prava = mysql_query("
				SELECT DISTINCT pravo
				FROM iis_h_uzivatele
				ORDER BY pravo
			");
			// pole s pravy
			$prava_array = array();
			if ( $data_prava ) {
				while ( $row = mysql_fetch_assoc( $data_prava ) ) {
					array_push( $prava_array, $row['pravo'] );
				}
			}
			// pokud zamestnanec existuje, vypisu tabulku s udaji o nem
			if ( $data_zamestnance && mysql_num_rows( $data_zamestnance ) > 0 ) {
				$row = mysql_fetch_assoc( $data_zamestnance );
				$adresa = "";
				if ( $row['ulice'] && $row['cislo_popisne'] && $row['psc'] && $row['mesto'] ) {
					$adresa = $row['ulice'].' '.$row['cislo_popisne'].', '.$row['psc'].' '.$row['mesto'];
				}
				else {
					$adresa .= '<table class="neohranicena_tabulka"><tbody>';
					$adresa .= '<tr><td>Ulice:</td><td>'.($row['ulice'] ? $row['ulice'] : 'neuvedeno').'</td></tr>';
					$adresa .= '<tr><td>Číslo popisné:</td><td>'.($row['cislo_popisne'] ? $row['cislo_popisne'] : 'neuvedeno').'</td></tr>';
					$adresa .= '<tr><td>PSČ:</td><td>'.($row['psc'] ? $row['psc'] : 'neuvedeno').'</td></tr>';
					$adresa .= '<tr><td>Město:</td><td>'.($row['mesto'] ? $row['mesto'] : 'neuvedeno').'</td></tr>';
					$adresa .= '</tbody></table>';
				}
				echo '<form method="post"><table class="neohranicena_tabulka"><tbody>';
				echo '<tr><td>Jméno:</td><td>'.$row['jmeno'].'</td></tr>';
				echo '<tr><td>Příjmení:</td><td>'.$row['prijmeni'].'</td></tr>';
				echo '<tr><td>Email:</td><td>'.$row['email'].'</td></tr>';
				echo '<tr><td class="adresa">Adresa:</td><td>'.$adresa.'</td></tr>';
				echo '<tr><td>Rodné číslo:</td><td><input class="required" max="9999999999" min="1000000000" type="number" name="rodne_cislo" value="'.$row['rodne_cislo'].'"></td></tr>';
				echo '<tr><td>Číslo účtu:</td><td><input type="number" name="cislo_uctu" value="'.$row['cislo_uctu'].'"></td></tr>';
				echo '<tr><td>Plat:</td><td><input class="required" type="number" name="plat" min="0" value="'.$row['plat'].'"></td></tr>';
				echo '<tr><td>Pracovní pozice:</td><td><input class="required" maxlength="127" type="text" name="pracovni_pozice" value="'.$row['pracovni_pozice'].'"></td></tr>';
				if ( $_SESSION['user']['pravo'] == 'spravce' ) {
					$prava_text = implode( ', ', $prava_array );
					echo '<tr><td>Oprávnění:</td><td><input class="required" maxlength="127" type="text" name="opravneni" value="'.$row['pravo'].'"></td></tr>';
					echo '<tr><td>Již založená práva:</td><td>'.$prava_text.'</td></tr>';
				}
				echo '</tbody></table>';
				echo '<input type="submit" name="potvrdit" value="Potvrdit změny">';
				echo '<input type="hidden" name="editace" value="'.$id.'">';
				echo '<input type="hidden" name="uzivatel" value="'.$row['uzivatel'].'">';
				echo '</form>';
			}
			// zamestnanec neexistuje
			else {
				echo '<p class="error">Nekorektní použití aplikace, zadaný zaměstnanec neexistuje!</p>';
			}
		}
		// prehled vsech zamestnancu
		else {
			echo '
				<h3>Aktuální zaměstnanci</h3>
				<p>
					Tato tabulka obsahuje seznam všech zaměstnanců, kteří v této restauraci
					pracují. Mělo by se jednat o přehled kdo na jaké pozici pracuje a kolik vydělává.
				</p>
				<form method="post"><table class="ohranicena_tabulka">
					<thead>
						<tr>
							<td>Ozn.</td>
							<td>Jméno</td>
							<td>Příjmení</td>
							<td>Pracovní pozice</td>
							<td>Rodné číslo</td>
							<td>Email</td>
							<td>Číslo účtu</td>
							<td>Plat</td>
							<td>Opravneni</td>
							<td>Editovat</td>
						</tr>
					</thead>
					<tbody>
			';
			$data_zamestnancu = mysql_query("
				SELECT iis_h_zamestnanec.ID, jmeno, prijmeni, email, rodne_cislo, pracovni_pozice,
					cislo_uctu, plat, mesto, ulice, cislo_popisne, psc, pravo, uzivatel
				FROM iis_h_zamestnanec, iis_h_uzivatele
				WHERE uzivatel = iis_h_uzivatele.ID
				ORDER BY jmeno
			");
			// dulezita data, ktera evidujeme v databazi
			if ( $data_zamestnancu && mysql_num_rows( $data_zamestnancu ) > 0 ) {
				while( $row = mysql_fetch_assoc( $data_zamestnancu ) ) {
					$name = "rezervace_check";
					$disabled = ($_SESSION['user']['ID'] == $row['uzivatel'] || $row['pravo'] == 'spravce') ? "disabled":"";
					$odkaz = explode('?', $_SERVER['REQUEST_URI'], 2);
					$odkaz = $odkaz[0];
					echo "<tr>";
					echo '<td><label><input type="checkbox" name="propustit_zamestnance[]" value="'.$row[ "ID" ].'" '.$disabled.'></label></td>';
					echo "<td>".$row[ "jmeno" ]."</td>";
					echo "<td>".$row[ "prijmeni" ]."</td>";
					echo "<td>".$row[ "pracovni_pozice" ]."</td>";
					echo "<td>".$row[ "rodne_cislo" ]."</td>";
					echo "<td>".$row[ "email" ]."</td>";
					echo "<td>".$row[ "cislo_uctu" ]."</td>";
					echo "<td>".$row[ "plat" ]." Kč/měsíc</td>";
					echo "<td>".$row[ "pravo" ]."</td>";
					echo "<td class=\"center\"><a href=\"$odkaz?editace=".$row['ID']."\"><img src=\"../pictures/editace.png\"></a></td>";
					echo "</tr>";
				}
			} else {
				echo '<tr><td colspan="10" class="no_data_in_table">Nemáte žádné zaměstnance</td></tr>';
			}
			echo '</tbody></table>';
			echo '<input type="submit" name="smazat" value="Zrušit pracovní úvazek"></form>';
		}

		echo '</div>';
	}
	// uzivatel nema pravo - poslu ho na index, ten si s nim poradi
	else {
		header('Location: index.php');
	}
?>

