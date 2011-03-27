	<h2><?=$pagetitle?></h2>
	
	<!-- P&auml;iv&auml;kirja -->  

	<h5>Sy&ouml;miset ja liikunnat t&auml;n&auml;&auml;n</h5>
	
	<?php
	if ($foods->num_rows() > 0) {
	?>	
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
				<input type="checkbox" name="food01" value="1">
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
			<?php foreach ($foods->result() as $food): ?>
			<tr>  
				<td class="foodselector"><input type="checkbox" name="food01" value="1"></td>  
				<td><?=$food->name?></td>  
				<td><?=$food->energy?> kcal</td>  
				<td><?=$food->protein?> g</td>  
				<td><?=$food->carbohydrate?> g</td>  
				<td><?=$food->fat?> g</td>  
				<td><?=$food->fiber?> g</td>  
			</tr>  
			<?php endforeach; ?>
			</tbody>  

	</table>
	<?php
	}
	else // Ei loytynyt tuloksia
	{
		?>
		<p>
		Sinulla ei ole merkint&ouml;j&auml; t&auml;lt&auml; p&auml;iv&auml;lt&auml;. Lis&auml;&auml; niit&auml; ruokasivulta tai liikuntasivulta.
		</p>
		<?php
	}
	?>
	
	<!-- Ohje --> 
	<h5>Superpikaohje</h5>
	<p>
	T&auml;ll&auml; sivulla voit tarkastella p&auml;iv&auml;n aikana sy&ouml;mi&auml;si ruokia ja liikkumiasi liikuntoja.
	</p>
	<p>
	Voit lis&auml;t&auml; ruokia Ruokasivulta, ja liikuntoja Liikuntasivulta.
	</p>
