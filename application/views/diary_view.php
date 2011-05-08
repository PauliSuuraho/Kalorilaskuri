	<h2><?=$pagetitle?></h2>
	
	<!-- P&auml;iv&auml;kirja -->  
	<?php
		$total_food_eaten = 0;
		$total_exercise = 0;
	?>
	<!-- Paivakirja -->  
	
	<h5>Sy&ouml;miset ja liikunnat t&auml;n&auml;&auml;n</h5>
	
	<?php
	if ($items->num_rows() > 0) {
	?>	
	<table id="diary">  

	<!-- Colgroup -->  
		<colgroup>  
			<col class="column_time" />  
			<col />  
			<col />  
			<col class="column_energy" />  
			<col class="column_details" />  
		</colgroup>   

	<!-- Table header -->  

		<thead>  
			<tr>  
				<th scope="col" id="time">Klo</th>  
				<th scope="col" id="name">P&auml;iv&auml;kirja</th>  
				<th scope="col" id="amount">M&auml;&auml;r&auml;</th>  
				<th scope="col" id="kcal">Energia</th>  
				<th scope="col" id="protein">Tietoa</th>  
			</tr>  
		</thead>  

	<!-- Table footer -->  

		<tfoot>  
			<tr>  
			</tr>  
		</tfoot>  

	<!-- Table body -->  
		<tbody>  
			<?php 
				foreach ($items->result() as $item): 
					if ($item->type == 1)
					{ 
					?>
						<tr class="foodrow">
						<td><?=$item->time?></td>  
						<td><?=$item->name?></td>  
						<td><?=$item->amount?> g</td>  
						<td><?=round(($item->energy) * ($item->amount) / 100)?> kcal</td>  
						<td>
						Pr: <?=(($item->protein) * ($item->amount) / 100)?>g, Hh: <?=(($item->carbohydrate) * ($item->amount) / 100)?>g, Ra: <?=(($item->fat) * ($item->amount) / 100)?>g, Ku: <?=(($item->fiber) * ($item->amount) / 100)?>g
						</td>  
					<?php
					$total_food_eaten = $total_food_eaten + (($item->energy) * ($item->amount) / 100);
					}
					else
					{ 
					?>
						<tr class="exerciserow">
						<td><?=$item->time?></td>  
						<td><?=$item->name?></td>  
						<td><?=$item->amount?> min</td>  
						<td><?=round(($item->energy) * ($item->amount) / 60)?> kcal</td>  
						<td>
						</td>  
					<?php
					$total_exercise = $total_exercise + (($item->energy) * ($item->amount) / 60);
					}
					?>
					</tr>  
					<?php 
					
				endforeach;
			?>
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
	
	<!-- Summary --> 
	<h5>Yhteenveto</h5>
	<table class="borderless">
	<tr><td>Peruskulutus:</td><td><?=$dailyconsumption?> kcal</td></tr>
	<tr><td>Sy&ouml;ty:</td><td><?=round($total_food_eaten)?> kcal</td></tr>
	<tr><td>Liikunta:</td><td><?=round($total_exercise)?> kcal</td></tr>
	<tr><td>J&auml;ljell&auml;:</td><td><?=round($dailyconsumption-$total_food_eaten+$total_exercise)?> kcal</td></tr>
	</table>

	<!-- Paivaus, pituus, paino --> 
	<h5>Tiedot</h5>
	<?php
	if ($this->user_model->islogged() == true)
	{
	?>
	<form>
	<table class="borderless">
		<tr>
			<td>P&auml;iv&auml;</td>
			<td>Kuukausi</td>
			<td>Vuosi</td>
			<td></td>
		</tr>
		<tr>
			<td><input name="day" type="text" value="<?=$day?>"></td>
			<td><input name="month" type="text" value="<?=$month?>"></td>
			<td><input name="year" type="text" value="<?=$year?>"></td>
			<td><input type="submit" value="Aseta"></td>
		</tr>
	</table>
	</form>
	<?php
	}
	?>
	<form>
	<table class="borderless">
		<tr>
			<td>Paino</td>
			<td>Pituus</td>
			<td>Peruskulutus</td>
			<td></td>
		</tr>
		<tr>
			<td><input name="weight" type="text" value="<?=$weight?>"> kg</td>
			<td><input name="height" type="text" value="<?=$height?>"> cm</td>
			<td><?=$dailyconsumption?> kcal/vrk</td>
			<td><input type="submit" value="Aseta"></td>
		</tr>
	</table>
	</form>

	<!-- Ohje --> 
	<h5>Superpikaohje</h5>
	<p>
	T&auml;ll&auml; sivulla voit tarkastella p&auml;iv&auml;n aikana sy&ouml;mi&auml;si ruokia ja suorittamiasi liikuntoja. Voit lis&auml;t&auml; ruokia Ruokasivulta, ja liikuntoja Liikuntasivulta.
	</p>