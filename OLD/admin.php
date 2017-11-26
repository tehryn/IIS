<?php
if( $_SESSION['user'] ){
	echo '<a class="login_logout" href="/logout.php">Odhlásit se</a>';
} else {
	echo '
	<span class="login_users">Tato stránka je přístupná pouze přihlášeným uživatelům.<\span>
	<h3>Přihlásit se:</h3>
	<form method="post">
		<table>
		<tr>
		<td>Email: </td>
			<td>
				<input type="text" name="login_email" value="" />
			</td>
		</tr>
		<tr>
		<td>Heslo: </td>
			<td><input type="password" name="login_password" /></td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="login_submit" name="login_submit" value="Přihlásit se" />
			</td>
		</tr>
		</table>
	</form>
	<h3>Registrace:</h3>
	<form>
		<table>
			<tbody>
				<tr>
					<td>
						Jméno:
					</td>
					<td>
						<input type="text" name="prihlaseni_jmeno" />
					</td>
				</tr>
				<tr>
					<td>
						Příjmení:
					</td>
					<td>
						<input type="text" name="prihlaseni_prijmeni" />
					</td>
				</tr>
				<tr>
					<td>
						Heslo:
					</td>
					<td>
						<input type="password" name="prihlaseni_heslo" />
					</td>
				</tr>
				<tr>
					<td>
						Email:
					</td>
					<td>
						<input type="text" name="prihlaseni_email" />
					</td>
				</tr>
				<tr>
					<td>
						Mesto:
					</td>
					<td>
						<input type="text" name="prihlaseni_mesto" />
					</td>
				</tr>
				<tr>
					<td>
						Ulice:
					</td>
					<td>
						<input type="text" name="prihlaseni_ulice" />
					</td>
				</tr>
				<tr>
					<td>
						Číslo popisné:
					</td>
					<td>
						<input type="text" name="prihlaseni_cislo_popisne" />
					</td>
				</tr>
				<tr>
					<td>
						PSČ:
					</td>
					<td>
						<input type="number" name="prihlaseni_psc" min="10000" max="99999" />
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="login_submit" name="prihlaseni_submit" value="Registrovat" />
					</td>
				</tr>
			</tbody>
		</table>
	</form>';
}
?>