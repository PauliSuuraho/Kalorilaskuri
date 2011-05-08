	<h2><?=$pagetitle?></h2>
	
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
				<th scope="col" id="name">Ruoka</th>  
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
		<?php foreach ($food->result() as $food): ?>
			<tr>  
				<td class="foodselector"><input type="checkbox" checked="true" name="add[]" value="<?=$food->id?>"></td>  
				<td><?=$food->name?></td>  
				<td><?=$food->energy?> kcal</td>  
				<td><?=$food->protein?> g</td>  
				<td><?=$food->carbohydrate?> g</td>  
				<td><?=$food->fat?> g</td>  
				<td><?=$food->fiber?> g</td>  
			</tr>  
			<? endforeach; ?>
			</tbody>  

	</table>  
	
	<label for="amount">Annos (g):</label>
	<input name="amount" type="text">
	<label for="time">Kello:</label>
	<input name="time" type="text">
	<input type="submit" value="Lis&auml;&auml; p&auml;iv&auml;kirjaan">
	</form>
	<!-- Ryhmat -->
	<h5>Ryhm&auml;t</h5>
	<ul>
		<?php foreach ($groups->result() as $group): ?>
			<li>  
				<?=anchor('diary/foods/?search-group='.$group->id,$group->name)?>
			</li>  
		<? endforeach; ?>
	</ul>
	
	<?php
	if ($this->user_model->islogged())
	{
	?>
	<form>
	<label for="addgroup">Lis&auml;&auml; ryhm&auml;&auml;n</label>
	<select name="addgroup">
		<?php foreach ($allgroups->result() as $group): ?>
				<option value="<?=$group->id?>"><?=$group->name?></option> 
		<? endforeach; ?>
		<option value="0" selected>(Valitse ryhm&auml;:)</option>
	</select>	
	<input type="submit" value="Lis&auml;&auml;">
	</form>
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
