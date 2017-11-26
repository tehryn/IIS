<?php
	echo '
		<div id="header" >
			<div id="header_box">
				<div id="logo">
					<h1><a><span>Sunny Night</span></a></h1>
				</div>
					<h2> '.$h2_nadpis.' </h2>
			</div>
			<div id="menu_wrapper">
				<a href="./zamestnanci.php">Správa zaměstnanců</a>
				<span class="sep"> | </span>
				<a href="./uzivatele.php">Správa uživatelů</a>
				<span class="sep"> | </span>
				<a href="./jidlo.php">Správa pokrmů</a>
				<span class="sep"> | </span>
				<a href="./rezervace.php">Správa rezervací</a>
				<span class="sep"> | </span>
				<a href="./index.php">'.($_SESSION['user'] != ''?'Odhlášení':'Přihlášení').'</a>
			</div>
			<div class="clear"></div>
		</div>
	';
?>
