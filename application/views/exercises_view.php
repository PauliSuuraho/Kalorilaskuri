	<h2><?=$pagetitle?></h2>
	
	<!-- Liikuntalista -->  
    <div id="search">
	<form id="search-form">
	<select name="search-group">
		<option value="0" selected>N&auml;yt&auml; kaikki</option>
		<?php foreach ($allgroups->result() as $group): ?> 
				<option value="<?=$group->id?>"<?php if ($group->id == $searchgroupid){ echo " selected";}?>><?=$group->name?></option> 
		<? endforeach; ?>
	</select>
	<label for="search-exercise">Etsi liikuntoja:</label>
	<input name="search-exercise" tabindex="3"" type="text">
	<input type="submit" value="Etsi">
	</form>
	</div>

	<form id="diary-form" action='<?=base_url().index_page().'/diary/exerciseadd'?>' method="POST">
	  
	<table id="diary">  

	<!-- Colgroup -->  
		<colgroup>  
			<col />  
			<col />  
			<col class="column_kcal" />  
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
				<td class="exerciseselector"><input type="checkbox" name="add[]" value="<?=$exercise->id?>"></td>  
				<td><?=anchor('diary/exercisedetail/'.$exercise->id,$exercise->name)?></td>  
				<td><?=$exercise->energytake?> kcal</td>  
		<? endforeach; ?>
		</tbody>  

	</table>  
    <div id="search">
		N&auml;ytet&auml;&auml;n 10 tulosta.
	</div>
	
	<label for="amount">Aika (min):</label>
	<input name="amount" type="text">
	<label for="time">Kello:</label>
	<input name="time" type="text">
	<input type="submit" value="Lis&auml;&auml; p&auml;iv&auml;kirjaan">
	</form>
	
	<?php
	if ($this->user_model->islogged())
	{
	?>
	<h6><?=anchor('diary/exerciseinsert','Lis&auml;&auml; liikunta')?></h6>
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
