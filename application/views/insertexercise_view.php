	<h2><?=$pagetitle?></h2>
	
	<!-- Lisaa liikunta tietokantaan formi -->  

	<form id="insert-form" action='<?=base_url().index_page().'/diary/exerciseinsert'?>' method="POST">
	<table class="borderless">
	<tr>
	<td>
	<label for="name">Nimi</label>
	</td>
	<td>
	<input name="name" type="text" value="<?=$data['name']?>">
	</td>
	</tr>
	<tr>
	<td>
	<label for="energytake">Energiakulutus (kcal/60 min)</label>
	</td>
	<td>
	<input name="energytake" type="text" value="<?=$data['energytake']?>">
	</td>
	</tr>
	<tr>
	<td>
	</td>
	<td>
	<input type="submit" value="Lis&auml;&auml; tietokantaan">
	</td>
	</tr>	
	</table>
	</form>
	<?php
	if ($errormessage != "")
	{
	?>
	<!-- Virhe --> 
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
	Voit lis&auml;t&auml; liikuntoja tietokantaan sy&ouml;tt&auml;m&auml;ll&auml; tiedot oheisiin kenttiin. My&ouml;hemmin voit halutessasi lis&auml;t&auml; liikuntoja erilaisiin ryhmiin haun helpottamiseksi.
	</p>
