<?php
	// kontrola opravneni
	$pravo = $_SESSION['user']['pravo'];
	if ( $pravo == 'vedouci' || $pravo == 'spravce' ) {
		echo '<div class="uzivatele">';
		if ( isset( $_POST['smazat'] ) && isset( $_POST['smazat_uzivatele'] ) ) {
			foreach( $_POST['smazat_uzivatele'] as $zrusit_id ) {
				mysql_query( "
					DELETE FROM iis_h_uzivatele
					WHERE ID = ".$zrusit_id
				);
		 	}
		}
		// tvorba pracovniho uvazku
		if ( isset( $_GET['zamestnanec'] ) ) {
			echo '<h3>Registrovaní uživatelé</h3>';
			$id = trim( $_GET['zamestnanec'] );

			if ( isset( $_POST['potvrdit'] ) ) {
				$cislo_uctu = isset( $_POST['cislo_uctu'] ) && trim( $_POST['cislo_uctu'] ) != "" ? trim( $_POST['cislo_uctu'] ) : "NULL";
				$plat = isset( $_POST['plat'] ) ? trim( $_POST['plat'] ) : "";
				$pracovni_pozice = isset( $_POST['pracovni_pozice'] ) ? trim( $_POST['pracovni_pozice'] ) : "";
				$rodne_cislo = isset( $_POST['rodne_cislo'] ) ? trim( $_POST['rodne_cislo'] ) : "";

				if ( $pracovni_pozice != "" && $plat != "" ) {
					$ok = mysql_query( "
						INSERT INTO iis_h_zamestnanec(plat, pracovni_pozice, cislo_uctu, rodne_cislo, uzivatel)
						values ($plat, '$pracovni_pozice', $cislo_uctu, $rodne_cislo, $id)
					");
					if ( !$ok ) {
						echo '<p class="error">Zadal/a jste neplatné údaje.</p>';
					}
					else {
						echo '<p class="ok">Zaměstnanec byl úspešně přidán.</p>';
					}
				}
				else {
					if ( $pracovni_pozice == "" ) {
						echo '<p class="error">Pracovní pozice nebyla specifikována.</p>';
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
			// informace o uzivateli + inputy potrebne pro navazani pracovniho uvazku
			$data_zamestnance = mysql_query("
				SELECT ID, jmeno, prijmeni, email, mesto, ulice, cislo_popisne, psc, pravo
				FROM iis_h_uzivatele
				WHERE ID = $id
			");
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
				echo '<tr><td>Rodné číslo:</td><td><input class="required" max="9999999999" min="1000000000" type="number" name="rodne_cislo" ></td></tr>';
				echo '<tr><td>Číslo účtu:</td><td><input type="number" name="cislo_uctu" ></td></tr>';
				echo '<tr><td>Plat:</td><td><input class="required" type="number" name="plat" min="0" ></td></tr>';
				echo '<tr><td>Pracovní pozice:</td><td><input class="required" type="text" maxlength="127" name="pracovni_pozice" ></td></tr>';
				echo '</tbody></table>';
				echo '<input type="submit" name="potvrdit" value="Potvrdit změny">';
				echo '<input type="hidden" name="zamestnanec" value="'.$id.'">';
				echo '</form>';
			}
			else {
				echo '<p class="error">Nekorektní použití aplikace, zadaný uživatel neexistuje!</p>';
			}
		}
		// prehled vsech registrovanych
		else {
			echo '
				<h3>Registrovaní uživatelé</h3>
				<p>
					Zde se nachází tabulka obsahující všechny zaregistované uživatele, mezi které patří i zaměstnanci restaurace.
				</p>
				<form method="post"><table class="ohranicena_tabulka">
					<thead>
						<tr>
							<td>Ozn.</td>
							<td>Jméno</td>
							<td>Příjmení</td>
							<td>Email</td>
							<td>Jmenovat zaměstnancem</td>
						</tr>
					</thead>
					<tbody>
			';
			$data_zamestnancu = mysql_query("
				SELECT ID, jmeno, prijmeni, email, mesto, ulice, cislo_popisne, psc, pravo
				FROM iis_h_uzivatele
			");

			if ( $data_zamestnancu && mysql_num_rows( $data_zamestnancu ) > 0 ) {
				while( $row = mysql_fetch_assoc( $data_zamestnancu ) ) {
					$name = "rezervace_check";
					$disabled = ($_SESSION['user']['ID'] == $row['ID'] || $row['pravo'] == 'spravce') ? "disabled":"";
					$odkaz = explode('?', $_SERVER['REQUEST_URI'], 2);
					$odkaz = $odkaz[0];
					echo "<tr>";
					echo '<td><label><input type="checkbox" name="smazat_uzivatele[]" value="'.$row[ "ID" ].'" '.$disabled.'></label></td>';
					echo "<td>".$row[ "jmeno" ]."</td>";
					echo "<td>".$row[ "prijmeni" ]."</td>";
					echo "<td>".$row[ "email" ]."</td>";
					echo "<td class=\"center\"><a href=\"$odkaz?zamestnanec=".$row['ID']."\"><img src=\"../pictures/editace.png\"></a></td>";
					echo "</tr>";
				}
			} else {
				echo '<tr><td colspan="7" class="no_data_in_table">Nemáte žádné registrované uživatele</td></tr>';
			}
			echo '</tbody></table>';
			echo '<input type="submit" name="smazat" value="Odstranit uzivatele"></form>';
		}

		echo '</div>';
	}
	else {
		header('Location: index.php');
	}
?>
