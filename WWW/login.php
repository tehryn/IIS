<?php
echo '
	<h3 class="login">Přihlášení:</h3>
	'.$error_str.'
	<form method="post">
	<table class="login">
		<tbody>
			<tr>
				<td>
					Email:
				</td>
				<td>
					<input class="required" type="text" name="prihlaseni_email" />
				</td>
			</tr>
			<tr>
				<td>
					Heslo:
				</td>
				<td>
					<input class="required" type="password" name="prihlaseni_heslo" />
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="submit" name="prihlaseni_submit" value="Přihlásit" />
				</td>
			</tr>
		</tbody>
	</table>
	</form>
';
}
else {
echo '
	<span>
		Jste přihlášen, přejete si
		<a href="./logout.php?">odhlásit se</a>?
	</span>
';
}