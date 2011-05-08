	<h2><?=$pagetitle?></h2>
	<form id="diary-form" action='<?=base_url().index_page().'/diary/exerciseadd'?>' method="POST">
	  
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
				<th scope="col" id="selector"></th>  
				<th scope="col" id="name">Liikuntalista</th>  
				<th scope="col" id="kcal">kCal/60 min</th>  
			</tr>  
		</thead>  

	<!-- Table footer -->  

		<tfoot>  
			<tr>  
			</tr>  
		</tfoot>  

	<!-- Table body -->  

		<tbody>  
		<?php foreach ($exercises->result() as $exercise): ?>
			<tr>  
				<td class="exerciseselector"><input type="checkbox" checked="true" name="add[]" value="<?=$exercise->id?>"></td>  
				<td><?=$exercise->name?></td>  
				<td><?=$exercise->energytake?> kcal</td>  
		<? endforeach; ?>
		</tbody>  
	</table>  
	
	<label for="amount">Aika (min):</label>
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
				<?=anchor('diary/exercises/?search-group='.$group->id,$group->name)?>
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
	Liikuntasivulla voit lis&auml;t&auml; liikuntoja p&auml;iv&auml;kirjaan. Valitse listasta yksi tai useampi liikunta ja m&auml;&auml;r&auml;&auml; liikkumasi aika lis&auml;t&auml;ksesi ne p&auml;iv&auml;kirjaasi. Voit my&ouml;s antaa kellonajan (esim. 11:30) liikunnalle.
	</p>
	<p>
	Jos haluamaasi liikuntaa ei l&ouml;ydy listasta, kokeile hakutoimintoa, tai jos rekister&ouml;idyt k&auml;ytt&auml;j&auml;ksi, niin voit lis&auml;t&auml; uuden liikunnan itse.
	</p>
