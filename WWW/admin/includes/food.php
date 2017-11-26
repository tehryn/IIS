<?php
	$pravo = $_SESSION['user']['pravo'];
	if (!( $pravo == 'spravce' || $pravo == 'vedouci' || $pravo == 'masterchef' || $pravo == 'chef' || $pravo == 'cisnik')) {
		header('Location: index.php');
	}
	else {
		echo '<div class="sprava_jidla">';
		if ( isset( $_POST['odebrat_potraviny'] ) ) {
			foreach( $_POST['potraviny_odstranit'] as $zrusit_id ) {
				mysql_query("
					DELETE FROM iis_h_potravina
					WHERE ID not IN (
						SELECT DISTINCT potravina
						FROM iis_s_potravina_surovina
					) OR ID = $zrusit_id
				");
				mysql_query("
					DELETE FROM iis_s_potravina_surovina
					WHERE potravina not IN (
						SELECT ID FROM iis_h_potravina
					) OR potravina = $zrusit_id
				");
			}
		}
		if ( ($pravo == 'masterchef' || $pravo == 'spravce') && isset( $_GET['potravina'] ) ) {
			$id = trim( $_GET['potravina'] );
			if ( isset( $_POST['potvrdit'] ) ) {
				$jmeno = isset( $_POST['jmeno'] ) ? trim( $_POST['jmeno'] ) : '';
				$druh = isset( $_POST['druh'] ) ? trim( $_POST['druh'] ) : "";
				$poradi = isset( $_POST['poradi'] ) ? trim( $_POST['poradi'] ) : "";
				$talir = isset( $_POST['talir'] ) ? trim( $_POST['talir'] ) : "";
				$sklenice = isset( $_POST['sklenice'] ) ? trim( $_POST['sklenice'] ) : "";
				$cena = isset( $_POST['cena'] ) ? trim( $_POST['cena'] ) : "";
				$doba_pripravy = isset( $_POST['doba_pripravy'] ) ? trim( $_POST['doba_pripravy'] ) : "";
				$popis_pripravy = isset( $_POST['popis_pripravy'] ) ? trim( $_POST['popis_pripravy'] ) : "";
				$popis = isset( $_POST['popis'] ) ? trim( $_POST['popis'] ) : "";

				if ( $jmeno != "" && $druh != "" && $poradi != "" && $cena != "" && $popis != "" ) {
					$ok = mysql_query("
						UPDATE iis_h_potravina
						SET
							jmeno = '$jmeno',
							druh = '$druh',
							poradi = $poradi,
							cena = $cena,
							doba_pripravy = $doba_pripravy,
							popis = '$popis',
							talir = ".($talir != ""?"'".$talir."'":"NULL").",
							sklenice = ".($sklenice != ""?"'".$sklenice."'":"NULL").",
							popis_pripravy = ".($popis_pripravy != ""?"'".$popis_pripravy."'":"NULL")."
						WHERE ID = $id
					");
					if ( $ok ) {
						echo '<p class="ok">Data byla aktualizována</p>';
					}
					else {
						echo '<p class="error">Zadal/a jste neplatné údaje.</p>';
					}
				}
				else {
					echo '<p class="error">Některé povinné položky nebyly vyplněny!</p>';
				}
			}
			$selected_jidla = mysql_query("
				SELECT  iis_h_potravina.ID, jmeno, druh, doba_pripravy, CAST(cena AS DECIMAL(11,2)) as cena,
				prodano_kusu, poradi, talir, sklenice, popis, popis_pripravy
				FROM iis_h_potravina, iis_s_potravina_surovina
				WHERE ID = $id AND iis_h_potravina.ID = iis_s_potravina_surovina.ID
			");

			echo '<h3>Editace pokrmu</h3>';
			if ( $selected_jidla && mysql_num_rows( $selected_jidla ) > 0 ) {
				$row = mysql_fetch_assoc( $selected_jidla );
				echo '<form method="post"><table class="ohranicena_tabulka"><tbody>';
				echo '<tr><td>Název:</td><td><input class="required" type="text" name="jmeno" value="'.$row['jmeno'].'"></td></tr>';
				echo '<tr><td>Druh:</td><td><input class="required" type="text" name="druh" value="'.$row['druh'].'"></td></tr>';
				echo '<tr><td>Pořadí:</td><td><input class="required" type="number" name="poradi" value="'.$row['poradi'].'"></td></tr>';
				echo '<tr><td>Druh talíře:</td><td><input type="text" name="talir" value="'.$row['talir'].'"></td></tr>';
				echo '<tr><td>Druh sklenice:</td><td><input type="text" name="sklenice" value="'.$row['sklenice'].'"></td></tr>';
				echo '<tr><td>Cena:</td><td><input class="required" type="number" name="cena" value="'.$row['cena'].'"> Kč</td></tr>';
				echo '<tr><td>Doba přípravy :</td><td><input type="text" name="doba_pripravy" value="'.$row['doba_pripravy'].'"> minut</td></tr>';
				echo '<tr><td class="top_align">Popis přípravy:</td><td><textarea rows="13" cols="80" name="popis_pripravy" >'.$row['popis_pripravy'].'</textarea></td></tr>';
				echo '<tr><td class="top_align">Popis pokrmu:</td><td><textarea class="required" rows="4" cols="80" name="popis" >'.$row['popis'].'</textarea></td></tr>';
				echo '</tbody></table>';
				echo '<input type="submit" name="potvrdit" value="Potvrdit změny">';
				echo '<input type="hidden" name="pokrm" value="'.$id.'">';
				echo '</form>';
			}
			else {
				echo '<p class="error">Zadaná položka neexistuje!</p>';
			}
		}
		else {
			echo '<h3>Položky menu</h3>';
			$selected_jidla = mysql_query("
				SELECT  ID, jmeno, druh, doba_pripravy, CAST(cena AS DECIMAL(11,2)) as cena, prodano_kusu, poradi
				FROM iis_h_potravina
				ORDER BY poradi, cena, jmeno
			");
			echo '<form method="post"><table class="ohranicena_tabulka">';
			echo '
					<thead>
						<tr>
							<td>Ozn.</td>
							<td>Název</td>
							<td>Druh</td>
							<td>Doba přípravy</td>
							<td>Cena</td>
							<td>Prodáno kusů</td>
							'.(($pravo == 'masterchef' || $pravo == 'spravce')?"<td>Editovat</td>":"").'
						</tr>
					</thead>
					<tbody>
			';
			if ( $selected_jidla && mysql_num_rows( $selected_jidla ) > 0 ) {
				$odkaz = explode('?', $_SERVER['REQUEST_URI'], 2);
				$odkaz = $odkaz[0];
				while ( $row = mysql_fetch_assoc( $selected_jidla ) ) {
					$time = "";
					if ( $row['doba_pripravy'] != '' ) {
						$hours   = floor($row['doba_pripravy'] / 60);
						$minutes = ($row['doba_pripravy'] % 60);
						$time = $hours.' hodin a '.$minutes.' minut';
					}
					else {
						$time = "neuvedeno";
					}
					echo '<tr>';
					echo '<td><input type="checkbox" name="potraviny_odstranit[]" value="'.$row[ "ID" ].'"></td>';
					echo '<td>'.$row['jmeno'].'</td>';
					echo '<td>'.$row['druh'].'</td>';
					echo '<td>'.$time.'</td>';
					echo '<td>'.$row['cena'].'</td>';
					echo '<td>'.$row['prodano_kusu'].'</td>';
					if ( $pravo == 'masterchef' || $pravo == 'spravce' ) {
						echo "<td class=\"center\"><a href=\"$odkaz?potravina=".$row['ID']."\"><img src=\"../pictures/editace.png\"></a></td>";
					}
					echo '</tr>';
				}

			}
			echo '</tbody></table>';
			echo '<input type="submit" name="odebrat_potraviny" value="Odstranit jídlo">';
			echo '</div>';
		}
	}
?>