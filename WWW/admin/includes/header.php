<?php
	$pravo = "neregistrovan";
	if ( $_SESSION['user'] != '' ) {
		$pravo = $_SESSION['user']['pravo'];
	}
	echo '
		<div id="header" >
			<div id="header_box">
				<div id="logo">
					<h1><a href="index.php"><span>Sunny Night</span></a></h1>
				</div>
					<h2> '.$h2_nadpis.' </h2>
			</div>
	';
	echo '<div id="menu_wrapper">';
	if ( $pravo == 'vedouci' || $pravo == 'spravce' ) {
		echo '<a href="./uzivatele.php">Správa uživatelů</a><span class="sep"> | </span>';
		echo '<a href="./zamestnanci.php">Správa zaměstnanců</a><span class="sep"> | </span>';
	}
	if ( $pravo == 'spravce' || $pravo == 'vedouci' || $pravo == 'masterchef' || $pravo == 'chef' || $pravo == 'zamestnanec' ) {
		echo '<a href="./jidlo.php">Správa pokrmů</a><span class="sep"> | </span>';
	}
	if ($pravo == 'standardni_zamestnanec' || $pravo == 'spravce' || $pravo == 'vedouci') {
		echo '<a href="./rezervace.php">Správa rezervací</a><span class="sep"> | </span>';
	}
	echo '<a href="./index.php">'.($_SESSION['user'] != ''?'Odhlášení':'Přihlášení').'</a>
			</div>
			<div class="clear"></div>
		</div>
	';
?>
