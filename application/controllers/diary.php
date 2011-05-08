<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Diary extends CI_Controller {

	function Diary() {
		parent::__construct();
		
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model('user_model');
	}
	
	function index() {
		//var_dump($_POST);
		
		$data = $this->user_model->diarydate();


		$sqldate = $data['year']."-".$data['month']."-".$data['day'];
		
		$headerdata['title'] = 'Kalorilaskuri';
		$headerdata['pagetitle'] = 'P&auml;iv&auml;kirja - ' . $data['day'].'.'.$data['month'].'.'.$data['year'];
		$headerdata['islogged'] = $this->user_model->islogged();
		
		$this->load->view('header_view',$headerdata);
		
		// Start of page
		$data['items'] = $this->db->query("SELECT 	id,
													(SELECT name FROM FOOD WHERE id = fooddiaryitem.foodid) as name,
													amount,
													userid,
													(SELECT energy FROM FOOD WHERE id = fooddiaryitem.foodid) as energy,
													(SELECT protein FROM FOOD WHERE id = fooddiaryitem.foodid) as protein,
													(SELECT fat FROM FOOD WHERE id = fooddiaryitem.foodid) as fat,
													(SELECT carbohydrate FROM FOOD WHERE id = fooddiaryitem.foodid) as carbohydrate,
													(SELECT fiber FROM FOOD WHERE id = fooddiaryitem.foodid) as fiber,
													1 as type,
													to_char(added, 'HH24:MI') as time,
													added FROM FOODDIARYITEM
													WHERE added >= DATE '$sqldate'
													AND added < DATE '$sqldate' + INTERVAL '1 DAY'
													AND userid = '".$this->user_model->userid()."'
													UNION ALL
													SELECT 
													ID,
													(SELECT name FROM exercise WHERE id = exercisediaryitem.exerciseid) as name,
													duration as amount,
													userid,
													(SELECT energytake FROM exercise WHERE id = exercisediaryitem.exerciseid) as energy,
													null,
													null,
													null,
													null,
													2 as type,
													to_char(added, 'HH24:MI') as time,
													added FROM exercisediaryitem
													WHERE added >= DATE '$sqldate'
													AND added < DATE '$sqldate' + INTERVAL '1 DAY'
													AND userid = '".$this->user_model->userid()."'
													ORDER BY ADDED;
													
													");
		$data = $this->user_model->userdata($data);
		$data['dailyconsumption'] = ($data['weight']*30);
		$this->load->view('diary_view',$data);
		// End of page
		
		$this->load->view('footer_view');
	}
	
	function login() {
		$headerdata['title'] = 'Kirjautuminen - Kalorilaskuri';
		$headerdata['pagetitle'] = 'Kirjautuminen';
		$headerdata['islogged'] = $this->user_model->islogged();
		$this->load->view('header_view',$headerdata);
		
		if ($this->user_model->login($this->input->post('username'),$this->input->post('password')) == true)
		{
			redirect('/diary/', 'refresh');
		}
		else
		{
			$errordata['pagetitle'] = 'Virhe sis&auml;&auml;nkirjautumisessa';
			$errordata['errormessage'] = "K&auml;ytt&auml;j&auml;tunnus ja salasanayhdistelm&auml; oli virheellinen!";
		
			$this->load->view('error_view',$errordata);
		}
		
		$this->load->view('footer_view');
	}
	
	function logout() {
		$headerdata['title'] = 'Uloskirjautuminen - Kalorilaskuri';
		$headerdata['pagetitle'] = 'Uloskirjautuminen';
		$headerdata['islogged'] = $this->user_model->islogged();
		$this->load->view('header_view',$headerdata);
		
		$this->user_model->logout();
		redirect('/diary/', 'refresh');
		$this->load->view('footer_view');
	}
	
	function register() {
		$headerdata['title'] = 'Rekister&ouml;inti - Kalorilaskuri';
		$headerdata['pagetitle'] = 'Rekister&ouml;inti';
		$headerdata['islogged'] = $this->user_model->islogged();

		$this->load->view('header_view',$headerdata);
		
		$headerdata['errormessage'] = "";
		
		$username = $this->input->post('username');
		
		if ($username != false)
		{
			// We have registration progress
			$done = $this->user_model->create($this->input->post('username'),$this->input->post('password'));
			if ($done == true)
			{
				redirect('/diary/', 'refresh');
			}
			else
			{
				$headerdata['errormessage'] = "Rekister&ouml;inti&auml; ei voitu suorittaa loppuun.";
			}
			
		}

		$this->load->view('register_view',$headerdata);
		
		$this->load->view('footer_view');
	}
	
	// -----------------------------------------------------------------------------------------------------------------
	// FOODS!
	//
	
	function foods() {
		$headerdata['title'] = 'Ruokasivu - Kalorilaskuri';
		$headerdata['pagetitle'] = 'Ruokasivu';
		$headerdata['islogged'] = $this->user_model->islogged();
		$this->load->view('header_view',$headerdata);
		
		$data['allgroups'] = $this->db->query("SELECT foodgroup.name,foodgroup.id
																			FROM FoodGroupLink
																			Inner JOIN FoodGroup on Foodgrouplink.foodgroupid = foodgroup.id
																			Inner JOIN Food on Foodgrouplink.foodid = food.id
																			GROUP BY foodgroup.name,foodgroup.id ORDER BY foodgroup.name;");

		// Start of page
		$searchword = '';
		$searchgroup = 0;
		if (isset($_GET['search-food'])) {
			$searchword = $this->input->get('search-food');
		}

		if (isset($_GET['search-group'])) {
			$searchgroup = $this->input->get('search-group');
		}

		if ($searchword == '') {
			if ($searchgroup == 0)
			{
				$data['foods'] = $this->db->query("SELECT Food.id, food.name, food.carbohydrate, food.protein,food.fat,food.energy,food.fiber FROM Food ORDER BY name LIMIT 10; ");
			}
			else
			{
				$data['foods'] = $this->db->query("SELECT Food.id, food.name, food.carbohydrate, food.protein,food.fat,food.energy,food.fiber FROM Food,FoodGroupLink
																WHERE (food.id = foodgrouplink.foodid AND
																foodgrouplink.foodgroupid = '$searchgroup')
																ORDER BY food.name LIMIT 10;");
			}
		}
		else
		{
			if ($searchgroup == 0)
			{
				$data['foods'] = $this->db->query("SELECT Food.id, food.name, food.carbohydrate, food.protein,food.fat,food.energy,food.fiber FROM Food
																WHERE (lower(food.name) LIKE lower('%$searchword%'))
																ORDER BY food.name LIMIT 10;");
			}
			else
			{
				$data['foods'] = $this->db->query("SELECT Food.id, food.name, food.carbohydrate, food.protein,food.fat,food.energy,food.fiber FROM Food,FoodGroupLink
																WHERE (food.id = foodgrouplink.foodid AND
																foodgrouplink.foodgroupid = '$searchgroup') AND
																(lower(food.name) LIKE lower('%$searchword%'))
																ORDER BY food.name LIMIT 10;");
			}
		}
		$data["searchgroupid"] = $searchgroup;
		$this->load->view('foods_view',$data);
		// End of page
		
		$this->load->view('footer_view');
	}
	
	function fooddetail() {
		$food_id = $this->uri->segment(3, 0);
		
		if (isset($_GET['addgroup']))
		{
			$addgroup_id = $_GET['addgroup'];
			// See if we already have the item in group
			$query = $this->db->query("SELECT foodgroup.id
																			FROM FoodGroupLink
																			Inner JOIN FoodGroup on Foodgrouplink.foodgroupid = foodgroup.id
																			Inner JOIN Food on Foodgrouplink.foodid = food.id
																			WHERE food.id = $food_id AND foodgroup.id = $addgroup_id
																			GROUP BY foodgroup.name,foodgroup.id ORDER BY foodgroup.name;");
			if ($query->num_rows() == 0)
			{
				$data = array(
					'foodid' => $food_id,
					'foodgroupid' => $addgroup_id
				);
				$this->db->insert('foodgrouplink', $data);
				
				// everything ok, redirect to food page
				redirect('/diary/fooddetail/'.$food_id, 'refresh');
				
			} 
			else
			{
				// We already have the item in the group!
			}
		}
		$data['food'] = $this->db->query("SELECT * FROM Food WHERE ID=$food_id LIMIT 1; ");

		$headerdata['title'] = 'Ruoka - Kalorilaskuri';
		$headerdata['pagetitle'] = $data['food']->row()->name;
		$headerdata['islogged'] = $this->user_model->islogged();
		$this->load->view('header_view',$headerdata);
		
		// Get all groups where the food is in
		$data['groups'] = $this->db->query("SELECT foodgroup.name,foodgroup.id
																			FROM FoodGroupLink
																			Inner JOIN FoodGroup on Foodgrouplink.foodgroupid = foodgroup.id
																			Inner JOIN Food on Foodgrouplink.foodid = food.id
																			WHERE food.id = $food_id
																			GROUP BY foodgroup.name,foodgroup.id ORDER BY foodgroup.name;");
		$data['allgroups'] = $this->db->query("SELECT foodgroup.name,foodgroup.id
																			FROM FoodGroupLink
																			Inner JOIN FoodGroup on Foodgrouplink.foodgroupid = foodgroup.id
																			Inner JOIN Food on Foodgrouplink.foodid = food.id
																			GROUP BY foodgroup.name,foodgroup.id ORDER BY foodgroup.name;");

		$this->load->view('fooddetail_view',$data);
		// End of page
		
		$this->load->view('footer_view');
	}
	
	function foodinsert() {
		function isgoodgramvalue($value) {
			if (is_numeric($value) == true)
			{
				if ($value < 0)
				{
					return false;
				}
				if ($value > 100)
				{
					return false;
				}
				
				return true;
			}
			return false;
		}

		$headerdata['title'] = 'Ruokasivu - Kalorilaskuri';
		$headerdata['pagetitle'] = 'Lis&auml;&auml; ruoka';
		$headerdata['islogged'] = $this->user_model->islogged();

		$this->load->view('header_view',$headerdata);
		$headerdata['errormessage'] = "";
		$data = array(
				'name' => '' ,
				'protein' => 0,
				'fat' => 0,
				'carbohydrate' => 0,
				'fiber' => 0,
				'energy' => 0
			);
		if ($this->user_model->islogged() == false)
		{
			$headerdata['errormessage'] = "Yritit avata sivua, johon sinulla ei ole oikeuksia! Kirjaudu ensin sis&auml;&auml;n!";
			$this->load->view('error_view',$headerdata);
		}
		else
		{
		if (isset($_POST['name']))
		{
			$data = array(
				'name' => $this->input->post('name') ,
				'protein' => $this->input->post('protein'),
				'fat' => $this->input->post('fat'),
				'carbohydrate' => $this->input->post('carbohydrate'),
				'fiber' => $this->input->post('fiber'),
				'energy' => $this->input->post('energy')
			);

			//print('<pre>');	print_r($data);	print('</pre>');
			if ($data['name'] == '')
			{
				$headerdata['errormessage'] = 'Nimi ei ole tarpeeksi kuvaava!';
			}
			else if (isgoodgramvalue($data['protein']) == false)
			{
				$headerdata['errormessage'] = 'Proteiinin arvo ei ole luku v&auml;lill&auml 0.0-100.0!';
			}
			else if (isgoodgramvalue($data['fat']) == false)
			{
				$headerdata['errormessage'] = 'Rasvan arvo ei ole luku v&auml;lill&auml 0.0-100.0!';
			}
			else if (isgoodgramvalue($data['carbohydrate']) == false)
			{
				$headerdata['errormessage'] = 'Hiilihydraatin arvo ei ole luku v&auml;lill&auml 0.0-100.0!';
			}
			else if (isgoodgramvalue($data['fiber']) == false)
			{
				$headerdata['errormessage'] = 'Kuidun arvo ei ole luku v&auml;lill&auml 0.0-100.0!';
			}
			else if ((is_numeric($data['energy']) == false) || (($data['energy']) < 0))
			{
				$headerdata['errormessage'] = 'Energian arvo pit&auml;&auml; olla luku, ja se ei saa olla pienempi kuin nolla.';
			}
			else
			{
				$this->db->insert('food', $data);
				
				// everything ok, redirect to food page
				redirect('/diary/foods', 'refresh');
			
			}
		}
		
		$headerdata['data'] = $data;
		$this->load->view('insertfood_view',$headerdata);
		}
		
		$this->load->view('footer_view');
		
	}
	
	function foodadd() {
		$headerdata['title'] = 'Ruoan li&auml;ys - Kalorilaskuri';
		$headerdata['pagetitle'] = 'P&auml;iv&auml;kirja';
		$headerdata['islogged'] = $this->user_model->islogged();
		$data = $this->user_model->diarydate();
		$day = $data['day'];
		$month = $data['month'];
		$year = $data['year'];
		
		if (isset($_POST['add']))
		{
		foreach ($_POST['add'] as $foodid)
		{
			// $foodid is the ID of the added food
			// $_POST['amount'] is the amount of food in grams
			// $_POST['time'] is the timestamp of entry. We need to parse it!

			
			$amount = 0;
			if (isset($_POST['amount']) == false)
			{
				$amount = 100;
			}
			else
			{
				$amount = $_POST['amount'];
				if ($amount == '')
				{
					$amount = 100;
				}
			}
			
			
			$hours = date('H');
			$minutes = date('i');
			$seconds = date('s');
			if ($_POST['time'] != '')
			{
				// Now We have time. It could be in format "11:02" or "11"
				
				$timearray = explode(':',$_POST['time']);

				if (is_int((int)$timearray[0]) == true)
					{
						// We have integer
						if (((int)$timearray[0] >= 0) && ((int)$timearray[0] < 24))
						{
							// We have hours
							$hours = $timearray[0];
							$minutes = 0;
						}
					}
				if (sizeof($timearray) >= 2)
				{
					if (is_int((int)$timearray[1]) == true)
					{
						// We have integer
						if (((int)$timearray[1] >= 0) && ((int)$timearray[1] < 60))
						{
							// We have minutes
							$minutes = $timearray[1];
						}
					}
				}
			}
			if (strlen($hours) < 2)
			{
				$hours = '0'.$hours;
			}
			if (strlen($minutes) < 2)
			{
				$minutes = '0'.$minutes;
			}
			$added = date ("Y-m-d H:i:s", strtotime($day."-".$month."-".$year." "."$hours:$minutes:$seconds"));
			
			$data = array(
				'amount' => "$amount" ,
				'userid' => $this->user_model->userid(),
				'foodid' => "$foodid",
				'added' => $added
			);

			//print('<pre>');	print_r($data);	print('</pre>');
			$this->db->insert('fooddiaryitem', $data);
		}
			
		}
		
		redirect('/diary/', 'refresh');
		
		//$this->load->view('footer_view');

	}
	
	
	// -----------------------------------------------------------------------------------------------------------------
	// EXERCISES!
	//
	
	function exercises() {
		$headerdata['title'] = 'Liikuntasivu - Kalorilaskuri';
		$headerdata['pagetitle'] = 'Liikuntasivu';
		$headerdata['islogged'] = $this->user_model->islogged();
		$this->load->view('header_view',$headerdata);
		
		$data['allgroups'] = $this->db->query("SELECT exercisegroup.name,exercisegroup.id
																			FROM exerciseGroupLink
																			Inner JOIN exercisegroup on exerciseGroupLink.exercisegroupid = exercisegroup.id
																			Inner JOIN exercise on exerciseGroupLink.exerciseid = exerciseid
																			GROUP BY exercisegroup.name,exercisegroup.id ORDER BY exercisegroup.name;");
		
		// Start of page
		$searchword = '';
		$searchgroup = 0;
		if (isset($_GET['search-exercise'])) {
			$searchword = $this->input->get('search-exercise');
		}

		if (isset($_GET['search-group'])) {
			$searchgroup = $this->input->get('search-group');
		}

		if ($searchword == '') {
			if ($searchgroup == 0)
			{
				$data['exercises'] = $this->db->query("SELECT Exercise.id, Exercise.name, Exercise.Energytake FROM Exercise ORDER BY Exercise LIMIT 10; ");
			}
			else
			{
				$data['exercises'] = $this->db->query("SELECT Exercise.id, Exercise.name, Exercise.Energytake FROM Exercise, ExerciseGroupLink
																WHERE (Exercise.id = ExerciseGroupLink.Exerciseid AND
																ExerciseGroupLink.Exercisegroupid = '$searchgroup')
																ORDER BY Exercise.name LIMIT 10;");
			}
		}
		else
		{
			if ($searchgroup == 0)
			{
				$data['exercises'] = $this->db->query("SELECT Exercise.id, Exercise.name, Exercise.Energytake FROM Exercise 
																WHERE (lower(exercise.name) LIKE lower('%$searchword%'))
																ORDER BY Exercise LIMIT 10; ");
			}
			else
			{
				$data['exercises'] = $this->db->query("SELECT Exercise.id, Exercise.name, Exercise.Energytake FROM Exercise,ExerciseGroupLink
																WHERE (Exercise.id = ExerciseGroupLink.Exerciseid AND
																ExerciseGroupLink.Exercisegroupid = '$searchgroup') AND
																(lower(Exercise.name) LIKE lower('%$searchword%'))
																ORDER BY Exercise.name LIMIT 10;");
			}
		}
		$data["searchgroupid"] = $searchgroup;
		$this->load->view('exercises_view',$data);
		// End of page
		
		$this->load->view('footer_view');
	}
	
	function exerciseadd() {
		$headerdata['title'] = 'Liikunnan li&auml;ys - Kalorilaskuri';
		$headerdata['pagetitle'] = 'P&auml;iv&auml;kirja';
		$headerdata['islogged'] = $this->user_model->islogged();
		$data = $this->user_model->diarydate();
		$day = $data['day'];
		$month = $data['month'];
		$year = $data['year'];
		
		if (isset($_POST['add']))
		{
		foreach ($_POST['add'] as $exerciseid)
		{
			// $exerciseid is the ID of the added exercise
			// $_POST['amount'] is the amount of exercise in grams
			// $_POST['time'] is the timestamp of entry. We need to parse it!

			
			$amount = 0;
			if (isset($_POST['amount']) == false)
			{
				$amount = 100;
			}
			else
			{
				$amount = $_POST['amount'];
				if ($amount == '')
				{
					$amount = 100;
				}
			}
			
			
			$hours = date('H');
			$minutes = date('i');
			$seconds = date('s');
			if ($_POST['time'] != '')
			{
				// Now We have time. It could be in format "11:02" or "11"
				
				$timearray = explode(':',$_POST['time']);

				if (is_int((int)$timearray[0]) == true)
					{
						// We have integer
						if (((int)$timearray[0] >= 0) && ((int)$timearray[0] < 24))
						{
							// We have hours
							$hours = $timearray[0];
							$minutes = 0;
						}
					}
				if (sizeof($timearray) >= 2)
				{
					if (is_int((int)$timearray[1]) == true)
					{
						// We have integer
						if (((int)$timearray[1] >= 0) && ((int)$timearray[1] < 60))
						{
							// We have minutes
							$minutes = $timearray[1];
						}
					}
				}
			}
			if (strlen($hours) < 2)
			{
				$hours = '0'.$hours;
			}
			if (strlen($minutes) < 2)
			{
				$minutes = '0'.$minutes;
			}
			$added = date ("Y-m-d H:i:s", strtotime($day."-".$month."-".$year." "."$hours:$minutes:$seconds"));
			
			$data = array(
				'duration' => "$amount" ,
				'userid' => $this->user_model->userid(),
				'exerciseid' => "$exerciseid",
				'added' => $added
			);

			//print('<pre>');	print_r($data);	print('</pre>');
			$this->db->insert('exercisediaryitem', $data);
		}
			
		}
		
		redirect('/diary/', 'refresh');
		
		//$this->load->view('footer_view');

	}
	
	function exercisedetail() {
		$Exercise_id = $this->uri->segment(3, 0);
		
		if (isset($_GET['addgroup']))
		{
			$addgroup_id = $_GET['addgroup'];
			// See if we already have the item in group
			$query = $this->db->query("SELECT Exercisegroup.id
																			FROM ExerciseGroupLink
																			Inner JOIN ExerciseGroup on Exercisegrouplink.Exercisegroupid = Exercisegroup.id
																			Inner JOIN Exercise on Exercisegrouplink.Exerciseid = Exercise.id
																			WHERE Exercise.id = $Exercise_id AND Exercisegroup.id = $addgroup_id
																			GROUP BY Exercisegroup.name,Exercisegroup.id ORDER BY Exercisegroup.name;");
			if ($query->num_rows() == 0)
			{
				$data = array(
					'exerciseid' => $Exercise_id,
					'exercisegroupid' => $addgroup_id
				);
				$this->db->insert('exercisegrouplink', $data);
				
				// everything ok, redirect to Exercise page
				redirect('/diary/Exercisedetail/'.$Exercise_id, 'refresh');
				
			} 
			else
			{
				// We already have the item in the group!
			}
		}
		$data['exercises'] = $this->db->query("SELECT * FROM Exercise WHERE ID=$Exercise_id LIMIT 1; ");

		$headerdata['title'] = 'Ruoka - Kalorilaskuri';
		$headerdata['pagetitle'] = $data['exercises']->row()->name;
		$headerdata['islogged'] = $this->user_model->islogged();
		$this->load->view('header_view',$headerdata);
		
		// Get all groups where the Exercise is in
		$data['groups'] = $this->db->query("SELECT Exercisegroup.name,Exercisegroup.id
																			FROM ExerciseGroupLink
																			Inner JOIN ExerciseGroup on Exercisegrouplink.Exercisegroupid = Exercisegroup.id
																			Inner JOIN Exercise on Exercisegrouplink.Exerciseid = Exercise.id
																			WHERE Exercise.id = $Exercise_id
																			GROUP BY Exercisegroup.name,Exercisegroup.id ORDER BY Exercisegroup.name;");
		$data['allgroups'] = $this->db->query("SELECT Exercisegroup.name,Exercisegroup.id
																			FROM ExerciseGroupLink
																			Inner JOIN ExerciseGroup on Exercisegrouplink.Exercisegroupid = Exercisegroup.id
																			Inner JOIN Exercise on Exercisegrouplink.Exerciseid = Exercise.id
																			GROUP BY Exercisegroup.name,Exercisegroup.id ORDER BY Exercisegroup.name;");

		$this->load->view('exercisedetail_view',$data);
		// End of page
		
		$this->load->view('footer_view');
	}
	
	function exerciseinsert() {
		function isgoodminutevalue($value) {
			if (is_numeric($value) == true)
			{
				if ($value < 0)
				{
					return false;
				}
				if ($value > 999999)
				{
					return false;
				}
				
				return true;
			}
			return false;
		}

		$headerdata['title'] = 'Liikuntasivu - Kalorilaskuri';
		$headerdata['pagetitle'] = 'Lis&auml;&auml; liikunta';
		$headerdata['islogged'] = $this->user_model->islogged();

		$this->load->view('header_view',$headerdata);
		$headerdata['errormessage'] = "";
		$data = array(
				'name' => '' ,
				'energytake' => 0,
			);
		if ($this->user_model->islogged() == false)
		{
			$headerdata['errormessage'] = "Yritit avata sivua, johon sinulla ei ole oikeuksia! Kirjaudu ensin sis&auml;&auml;n!";
			$this->load->view('error_view',$headerdata);
		}
		else
		{
		if (isset($_POST['name']))
		{
			$data = array(
				'name' => $this->input->post('name') ,
				'energytake' => $this->input->post('energytake')
			);

			//print('<pre>');	print_r($data);	print('</pre>');
			if ($data['name'] == '')
			{
				$headerdata['errormessage'] = 'Nimi ei ole tarpeeksi kuvaava!';
			}
			else if (isgoodminutevalue($data['energytake']) == false)
			{
				$headerdata['errormessage'] = 'Energiakulutuksen arvo ei ole luku v&auml;lill&auml 0.0-99999.0!';
			}
			else
			{
				$this->db->insert('exercise', $data);
				
				// everything ok, redirect to exercise page
				redirect('/diary/exercises', 'refresh');
			
			}
		}
		
		$headerdata['data'] = $data;
		$this->load->view('insertexercise_view',$headerdata);
		}
		
		$this->load->view('footer_view');
		
	}
	
	// -----------------------------------------------------------------------------------------------------------------
	// STATS!
	//
	
	function stats() {
		$headerdata['title'] = 'Tilastot - Kalorilaskuri';
		$headerdata['pagetitle'] = 'Tilastosivu';
		$headerdata['islogged'] = $this->user_model->islogged();
		$this->load->view('header_view',$headerdata);
		
		$arvorow = $this->db->query("SELECT SUM(amount) as arvo
													FROM FOODDIARYITEM
													WHERE userid = '".$this->user_model->userid()."'
													");
		$data['total_food_eaten'] = $arvorow->row()->arvo;

		$arvorow = $this->db->query("SELECT SUM(duration) as arvo
													FROM EXERCISEDIARYITEM
													WHERE userid = '".$this->user_model->userid()."'
													");
		$data['total_exercise_taken'] = $arvorow->row()->arvo;
		
		$this->load->view('stats_view',$data);
		// End of page
		
		$this->load->view('footer_view');
	}


}

?>