	<h2><?=$pagetitle?></h2>
	
	<!-- Ruokalista -->  
    <div id="search">
	<form id="search-form">
	<select name="search-group">
		<option value="0" selected>N&auml;yt&auml; kaikki</option>
		<?php foreach ($allgroups->result() as $group): ?> 
				<option value="<?=$group->id?>"<?php if ($group->id == $searchgroupid){ echo " selected";}?>><?=$group->name?></option> 
		<? endforeach; ?>
	</select>
	<label for="search-food">Etsi ruokia:</label>
	<input name="search-food" tabindex="3"" type="text">
	<input type="submit" value="Etsi">
	</form>
	</div>

	<form id="diary-form" action='<?=base_url().index_page().'/diary/foodadd'?>' method="POST">
	  
	<table id="diary">  

	<!-- Colgroup -->  
		<colgroup>  
			<col />  
			<col />  
			<col class="column_kcal" />  
			<col class="column_protein" />  
			<col class="column_carbohydrate" />  
			<col class="column_fat" />  
			<col class="column_fiber" />  
		</colgroup>   

	<!-- Table header -->  

		<thead>  
			<tr>  
				<th scope="col" id="selector">
				</th>  
				<th scope="col" id="name">Ruokalista</th>  
				<th scope="col" id="kcal">kCal/100g</th>  
				<th scope="col" id="protein" class="valuegrams">PR</th>  
				<th scope="col" id="carbohydrate" class="valuegrams">HH</th>  
				<th scope="col" id="fat" class="valuegrams">RA</th>  
				<th scope="col" id="fiber" class="valuegrams">KU</th>  
			</tr>  
		</thead>  

	<!-- Table footer -->  

		<tfoot>  
			<tr>  
			</tr>  
		</tfoot>  

	<!-- Table body -->  

		<tbody>  
		<?php foreach ($foods->result() as $food): ?>
			<tr>  
				<td class="foodselector"><input type="checkbox" name="add[]" value="<?=$food->id?>"></td>  
				<td><?=anchor('diary/fooddetail/'.$food->id,$food->name)?></td>  
				<td><?=$food->energy?> kcal</td>  
				<td><?=$food->protein?> g</td>  
				<td><?=$food->carbohydrate?> g</td>  
				<td><?=$food->fat?> g</td>  
				<td><?=$food->fiber?> g</td>  
			</tr>  
			<? endforeach; ?>
			</tbody>  

	</table>  
    <div id="search">
		N&auml;ytet&auml;&auml;n 10 tulosta.
	</div>
	
	<label for="amount">Annos (g):</label>
	<input name="amount" type="text">
	<label for="time">Kello:</label>
	<input name="time" type="text">
	<input type="submit" value="Lis&auml;&auml; p&auml;iv&auml;kirjaan">
	</form>
	
	<?php
	if ($this->user_model->islogged())
	{
	?>
	<h6><?=anchor('diary/foodinsert','Lis&auml;&auml; ruoka')?></h6>
	<?php
	}
	?>
	<!-- Ohje --> 
	<h5>Superpikaohje</h5>
	<p>
	Ruokasivulla voit lis&auml;t&auml; ruokia p&auml;iv&auml;kirjaan. Valitse listasta yksi tai useampi ruoka ja m&auml;&auml;r&auml;&auml; annos lis&auml;t&auml;ksesi ne p&auml;iv&auml;kirjaasi. Voit my&ouml;s antaa kellonajan (esim. 11:30) sy&ouml;miselle.
	</p>
	<p>
	Jos haluamaasi ruoka-ainetta ei l&ouml;ydy listasta, kokeile hakutoimintoa, tai jos rekister&ouml;idyt k&auml;ytt&auml;j&auml;ksi, niin voit lis&auml;t&auml; aineen itse.
	</p>
