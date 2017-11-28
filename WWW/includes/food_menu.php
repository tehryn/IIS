<?php
	echo '<div class="food_menu">';
	$selected_jidla = mysql_query("
		SELECT  iis_h_potravina.ID, iis_h_potravina.jmeno AS jmeno_potraviny,
				iis_h_surovina.jmeno AS jmeno_suroviny, druh, CAST(cena AS DECIMAL(11,2)) as cena,
				url_obrazku, popis, poradi, alergeny
		FROM iis_h_potravina, iis_h_surovina, iis_s_potravina_surovina
		WHERE
			iis_h_surovina.ID  = iis_s_potravina_surovina.surovina AND
			iis_h_potravina.ID = iis_s_potravina_surovina.potravina
		ORDER BY poradi, cena, iis_h_potravina.jmeno
	");
	$jidla_data = array();
	while( $row = mysql_fetch_assoc( $selected_jidla ) ) {
		array_push( $jidla_data, $row );
	}
	if ( $selected_jidla && mysql_num_rows( $selected_jidla ) > 0 ) {
		$druh = "";
		$suroviny  = array();
		$alergeny  = array();
		$i = 0;
		$next_row = "";
		$prev_id = 0;
		foreach ( $jidla_data as $row) {
			if ( $next_row == "" || $prev_id != $row['ID']  ) {
				$suroviny  = array();
				$alergeny  = array();
				foreach ( $jidla_data as $next_row) {
					if ( $next_row['ID'] == $row['ID'] ) {
						array_push( $suroviny, $next_row['jmeno_suroviny'] );
						if ( $next_row['alergeny'] != NULL ) {
							$alergeny_v_radku = explode( ',', $next_row['jmeno_suroviny'] );
							foreach( $alergeny_v_radku as $alergen ) {
								if ( !in_array( $alergen, $alergeny ) ) {
									array_push( $alergeny, $alergen );
								}
							}
						}
					}
				}
				if ( $druh != $row['druh'] ) {
					if ( $druh != "" ) {
						echo '</tbody></table>';
					}
					$druh = $row['druh'];
					echo '<h3>'.$druh.'</h3>';
					echo '<table class="neohranicena_tabulka"><tbody>';
				}
				sort( $alergeny );
				sort( $suroviny );
				$suroviny = implode( ', ', $suroviny );
				$alergeny = implode( ', ', $alergeny );
				echo '<tr>';
				echo '<td> <span class="jmeno_potraviny">'.$row['jmeno_potraviny'].'</span>';
				echo '<span class="popis_potraviny">'.$row['popis'].'</span>';
				echo '<span class="suroviny_potraviny">Pokrm obsahuje následující igredience: '.$suroviny.'</span>';
				echo '<span class="alergeny_potraviny">Alergeny obsažené v pokrmu: '.$alergeny.'</span>';
				echo '</td>';
				echo '<td><span class="cena_potraviny">'.$row['cena'].' Kč</span></td>';
				echo '</tr>';
				$prev_id = $row['ID'];
			}
		}
		if ( $druh != "" ) {
			echo '</tbody></table>';
		}
	}
	echo '</div>';
?>