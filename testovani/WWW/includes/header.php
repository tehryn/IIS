<?php
	$registrace = ( $_SESSION[ 'user' ] == "" ) ?
		'<span class="sep"> | </span><a href="./registrace.php">Registrace</a>'
		:
		''
	;
	echo '
		<div id="header" >
			<div id="header_box">
				<div id="logo">
					<h1><a href="index.php"><span>Sunny Night</span></a></h1>
				</div>
					<h2> '.$h2_nadpis.' </h2>
					'
					;
					include 'includes/login.php';

	echo '
			</div><div id="menu_wrapper">
				<a href="./index.php">Domů</a>
				<span class="sep"> | </span>
				<a href="./menu.php">Menu</a>
				<span class="sep"> | </span>
				<a href="./rezervace.php">Rezervace</a>
				<span class="sep"> | </span>
				<a href="./kontakt.php">Kontakt</a>
				'.$registrace.'
			</div>
			<div class="clear"></div>
		</div>
	';
?>
