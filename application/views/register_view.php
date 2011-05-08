	<h2><?=$pagetitle?></h2>
	
	<!-- Rekisterointilomake -->  
	<div id="register">
	<form id="register-form" action='<?=base_url().index_page().'/diary/register'?>' method="POST">
	<label for="username">K&auml;ytt&auml;j&auml;tunnus:</label>
	<input name="username" tabindex="3"" type="text">
	<label for="password">Salasana:</label>
	<input name="password" tabindex="4"" type="password">
	<input type="submit" value="Rekister&ouml;idy">
	</form>
	</div>
	
	<?php
	if ($errormessage != "")
	{
	?>
	<!-- Mahdollinen virhe --> 
	<h5>Virhe</h5>
	<p>
		<?=$errormessage?>
	</p>
	<?php
	}
	?>
	<!-- Ohje --> 
	<h5>Superpikaohje</h5>
	<p>
		Voit rekister&ouml;id&auml; itsellesi k&auml;ytt&auml;j&auml;tunnuksen, ja siten pit&auml;&auml; p&auml;iv&auml;kirjaa useamman p&auml;iv&auml;n ajan. Lis&auml;ksi saat mahdollisuuden lis&auml;t&auml; ruokia j&auml;rjestelm&auml;&auml;n.
	</p>