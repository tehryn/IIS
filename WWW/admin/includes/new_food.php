<?php
	$pravo = $_SESSION['user']['pravo'];
	if ( $pravo == 'masterchef' || $pravo == 'spravce' ) {
		if ( isset( $_POST['ulozit'] ) ) {
			$jmeno = ( isset( $_POST['jmeno'] )? trim( $_POST['jmeno'] ) : "" );
			$druh = ( isset( $_POST['druh'] )? trim( $_POST['druh'] ) : "" );
			$doba_pripravy = ( isset( $_POST['doba_pripravy'] )? trim( $_POST['doba_pripravy'] ) : "" );
			$popis_pripravy = ( isset( $_POST['popis_pripravy'] )? trim( $_POST['popis_pripravy'] ) : "" );
			$cena = ( isset( $_POST['cena'] )? trim( $_POST['cena'] ) : "" );
			$sklenice = ( isset( $_POST['sklenice'] )? trim( $_POST['sklenice'] ) : "" );
			$talir = ( isset( $_POST['talir'] )? trim( $_POST['talir'] ) : "" );
			$popis = ( isset( $_POST['popis'] )? trim( $_POST['popis'] ) : "" );
			$poradi = ( isset( $_POST['poradi'] )? trim( $_POST['poradi'] ) : "" );
			$url = "./pictures";
			$prodano_kusu = 0;

			if ( $popis_pripravy != '' ) {
				$popis_pripravy = "'$popis_pripravy'";
			}
			else {
				$popis_pripravy = 'NULL';
			}

			if ( $talir != '' ) {
				$talir = "'$talir'";
			}
			else {
				$talir = 'NULL';
			}

			if ( $sklenice != '' ) {
				$sklenice = "'$sklenice'";
			}
			else {
				$sklenice = 'NULL';
			}

			if ( $doba_pripravy == '' ) {
				$doba_pripravy = 'NULL';
			}

			if ( $jmeno != '' && $druh != '' && $poradi != '' && $cena != '' && $popis != '' && !$_SESSION['refresh']) {
				$ok = mysql_query( "
					INSERT INTO iis_h_potravina
					( jmeno, druh, doba_pripravy, popis_pripravy, cena, talir, prodano_kusu, sklenice, url_obrazku, popis, poradi)
					values
					( '$jmeno', '$druh', $doba_pripravy, $popis_pripravy, $cena, $talir, $prodano_kusu, $sklenice, '$url', '$popis', $poradi )
				");
				if ( $ok ) {
					$inserted_id = mysql_insert_id();
					$suroviny_select = mysql_query("
						SELECT ID, jmeno
						FROM iis_h_surovina
					");
					$ok_message = $ok;
					while ( $suroviny_select && $row = mysql_fetch_assoc( $suroviny_select ) ) {
						if ( isset( $_POST[$row['ID']] ) ) {
							$surovina = $row['ID'];
							$mnozstvi = trim( $_POST[$row['ID']] );
							if ( $mnozstvi > 0 ) {
								$ok = mysql_query("
								INSERT INTO iis_s_potravina_surovina(potravina, surovina, mnozstvi)
								values( $inserted_id, $surovina, $mnozstvi )
								");
							}

							if ( $ok ) { }
							else {
								mysql_query("
									DELETE FROM iis_h_potravina
									WHERE ID = $inserted_id
								");
								mysql_query("
									DELETE FROM iis_s_potravina_surovina
									WHERE potravina = $inserted_id
								");
								$ok_message = false;
								break;
							}
						}
					}
					if ( $ok_message ) {
						header("Refresh:0");
					}
					else {
						echo '<p class="error">Zadaná data jsou ve špatném formátu</p>';
					}
				}
			}
			else {
				echo '<p class="error">Zadaná data jsou ve špatném formátu</p>';
			}
		}

		echo '<h3>Nová surovina</h3>';
		if ( isset( $_POST['pridat_surovinu'] ) && !$_SESSION['refresh'] ) {
			$jmeno = ( isset( $_POST['jmeno'] ) ? trim( $_POST['jmeno'] ) : "" );
			$alergen = ( isset( $_POST['alergen'] ) ? trim( $_POST['alergen'] ) : "" );
			if ( $alergen != "" ) {
				$alergen = "'$alergen'";
			}
			else {
				$alergen = 'NULL';
			}
			$ok = mysql_query("
				INSERT INTO iis_h_surovina(jmeno, alergeny)
				values('$jmeno', $alergen)
			");
			if ( $ok ) {
				echo '<p class="ok">Surovina byla vytvořena</p>';
			}
			else {
				echo '<p class="error">Data nebyla zadána ve správném formátu</p>';
			}
		}
		echo '<form><table class="neohranicena_tabulka"><tbody>';
		echo '<tr><td>Název:</td><td><input class="required" type="text" name="jmeno"></td></tr>';
		echo '<tr><td>Alergeny:</td><td><input type="text" name="alergen"></td></tr>';
		echo '</tbody></table>';
		echo '<input type="submit" name="pridat_surovinu" value="Přidat novou surovinu"></form>';
		echo '<h3>Nový pokrm</h3>';
		echo '<h4>Informace o pokrmu</h4>';
		echo '<form method="post"><table><tbody>';
		echo '<tr><td>Název:</td><td><input class="required" type="text" name="jmeno"></td></tr>';
		echo '<tr><td>Druh:</td><td><input class="required" type="text" name="druh"></td></tr>';
		echo '<tr><td>Pořadí:</td><td><input class="required" type="number" name="poradi"></td></tr>';
		echo '<tr><td>Druh talíře:</td><td><input type="text" name="talir"></td></tr>';
		echo '<tr><td>Druh sklenice:</td><td><input type="text" name="sklenice"></td></tr>';
		echo '<tr><td>Cena:</td><td><input class="required" type="number" min="0" name="cena"> Kč</td></tr>';
		echo '<tr><td>Doba přípravy :</td><td><input type="number" min="0" name="doba_pripravy"> minut</td></tr>';
		echo '<tr><td class="top_align">Popis přípravy:</td><td><textarea rows="13" cols="80" name="popis_pripravy" ></textarea></td></tr>';
		echo '<tr><td class="top_align">Popis pokrmu:</td><td><textarea class="required" rows="4" cols="80" name="popis" ></textarea></td></tr>';
		echo '</tbody></table>';
		$suroviny_select = mysql_query("
			SELECT ID, jmeno
			FROM iis_h_surovina
		");
		echo '<h4>Suroviny obsažené v pokrmu</h4>';
		echo '<table class="neohranicena_tabulka"><tbody>';
		while ( $suroviny_select && $row = mysql_fetch_assoc( $suroviny_select ) ) {
			echo '<tr>';
			echo '<td>'.$row['jmeno'].'</td>';
			echo '<td><input type="number" min="0" name="'.$row['ID'].'" value="0">gramů';
			echo '</tr>';
		}
		echo '</tbody></table>';
		echo '<input type="submit" name="ulozit" value="Přidat pokrm">';
		echo '</form>';
	}
?>