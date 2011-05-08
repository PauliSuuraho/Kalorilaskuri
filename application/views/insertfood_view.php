	<h2><?=$pagetitle?></h2>
	
	<!-- Lisaa ruoka tietokantaan formi -->  

	<form id="insert-form" action='<?=base_url().index_page().'/diary/foodinsert'?>' method="POST">
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
	<label for="energy">Energia (kcal/100g)</label>
	</td>
	<td>
	<input name="energy" type="text" value="<?=$data['energy']?>">
	</td>
	</tr>
	<tr>
	<td>
	<label for="protein">Proteiini (g/100g)</label>
	</td>
	<td>
	<input name="protein" type="text" value="<?=$data['protein']?>">
	</td>
	</tr>
	<tr>
	<td>
	<label for="fat">Rasva (g/100g)</label>
	</td>
	<td>
	<input name="fat" type="text" value="<?=$data['fat']?>">
	</td>
	</tr>
	<tr>
	<td>
	<label for="carbohydrate">Hiilihydraatti (g/100g)</label>
	</td>
	<td>
	<input name="carbohydrate" type="text" value="<?=$data['carbohydrate']?>">
	</td>
	</tr>
	<tr>
	<td>
	<label for="fiber">Kuitu (g/100g)</label>
	</td>
	<td>
	<input name="fiber" type="text" value="<?=$data['fiber']?>">
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
	Voit lis&auml;t&auml; ruokia tietokantaan sy&ouml;tt&auml;m&auml;ll&auml; tiedot oheisiin kenttiin. My&ouml;hemmin voit halutessasi lis&auml;t&auml; ruokia erilaisiin ryhmiin haun helpottamiseksi.
	</p>
