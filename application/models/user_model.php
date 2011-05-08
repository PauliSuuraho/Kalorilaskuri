<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {

	var $user_table = 'users';
	var $title   = '';
	var $content = '';
	var $date    = '';

	function __construct() {
		// Call the Model constructor
		parent::__construct();
	}

	function create($user = '', $password = '', $auto_login = true) {

		//Make sure account info was sent
		if($user == '' OR $password == '') {
			return false;
		}

		//Check against user table
		$this->db->where('name', $user); 
		$query = $this->db->get($this->user_table);

		if ($query->num_rows() > 0) {
			//username already exists
			return false;

		} else {
			//Encrypt password
			$password = md5($password);

			//Insert account into the database
			$data = array(
				'name' => $user,
				'password' => $password
			);
			$this->db->set($data); 
			if(!$this->db->insert($this->user_table)) {
			//There was a problem!
			return false;                        
			}
			$user_id = $this->db->insert_id();

			//Automatically login to created account
			if($auto_login) {        
			//Destroy old session
			$this->session->sess_destroy();

			//Create a fresh, brand new session
			$this->session->sess_create();

			//Set session data
			$this->session->set_userdata(array('id' => $user_id,'name' => $user));

			//Set logged_in to true
			$this->session->set_userdata(array('logged_in' => true));            

			}

			//Login was successful            
			return true;
		}
	}
		
	function login($user = '', $password = '') {

		//Make sure login info was sent
		if($user == '' OR $password == '') {
			return false;
		}

		//Check if already logged in
		if($this->session->userdata('username') == $user) {
			//User is already logged in.
			return false;
		}
		
		//Check against user table
		$this->db->where('name', $user); 
		$query = $this->db->get($this->user_table);
		
		if ($query->num_rows() > 0) {
			$row = $query->row_array(); 
			
			//Check against password
			if(md5($password) != $row['password']) {
				return false;
			}
			
			//Destroy old session
			$this->session->sess_destroy();
			
			//Create a fresh, brand new session
			$this->session->sess_create();
			
			//Remove the password field
			unset($row['password']);
			
			//Set session data
			$this->session->set_userdata($row);
			
			//Set logged_in to true
			$this->session->set_userdata(array('logged_in' => true));            
			
			//Login was successful            
			return true;
		} else {
			//No database result found
			return false;
		}    
	}
	
	function islogged() {
		return $this->session->userdata('logged_in');
	}
	
	function userid() {
		if ($this->islogged() == true)
		{
			return $this->session->userdata('id');
		}
		
		// We don't have userid for the user. So let's create a new user, to who we add the data

		// Check against user table
		$user = $this->session->userdata('session_id');
		$this->db->where('name', $user); 
		$query = $this->db->get($this->user_table);

		if ($query->num_rows() > 0) {
			// Username already exists, so we use that!
			$row = $query->row_array(); 
			$user_id = $row['id'];

		} else {
			//Encrypt password
			$password = md5($this->session->userdata('ip_address')); // We use IP as Password! :P

			//Insert account into the database
			$data = array(
				'name' => $user,
				'password' => $password
			);
			$this->db->set($data); 
			if(!$this->db->insert($this->user_table)) {
			//There was a problem!
			return false;                        
			}
			$user_id = $this->db->insert_id();
		}
			//Creating user session was successful            
			return $user_id;
	}
	
	function username() {
		if ($this->islogged() == true)
		{
			return $this->session->userdata('name');
		}
		return "";
	}
	
	function diarydate() {
		$data['day'] = date('d');
		$data['month'] = date('m');
		$data['year'] = date('Y');
		if ($this->islogged() == true)
		{
			$data['day'] = $this->input->get('day');
			$data['month'] = $this->input->get('month');
			$data['year'] = $this->input->get('year');
			
			if ($data['day'] == false)
			{
				if ($this->session->userdata('diaryday') == false)
				{
					$data['day'] = date('d');
				}
				else
				{
					$data['day'] = $this->session->userdata('diaryday');
				}
			}
			if ($data['month'] == false)
			{
				if ($this->session->userdata('diarymonth') == false)
				{
					$data['month'] = date('m');
				}
				else
				{
					$data['month'] = $this->session->userdata('diarymonth');
				}
			}
			if ($data['year'] == false)
			{
				if ($this->session->userdata('diaryyear') == false)
				{
					$data['year'] = date('Y');
				}
				else
				{
					$data['year'] = $this->session->userdata('diaryyear');
				}
			}
			
			$currentdate = array(
					   'diaryday'	=> $data['day'],
					   'diarymonth'	=> $data['month'],
					   'diaryyear'	=> $data['year']
				   );

			$this->session->set_userdata($currentdate);
		}
		return $data;
	}
	
	function userdata($data) {
		
		$sqldate = $data['year']."-".$data['month']."-".$data['day'];
		// Let's try to get from submit form data
		$data['weight'] = $this->input->get('weight');
		$data['height'] = $this->input->get('height');
		
		// Let's get weight
		if (is_numeric($data['weight']) == false)
		{
			// We don't know the user weight
			if ($this->islogged() == false)
			{
				// User is not logged, so data is stored in cookie
				if ($this->session->userdata('weight') == false)
				{
					// We are not set in the cookie, so let's set it to defaults
					$currentdata = array(
							   'weight' => 80
						   );
					$this->session->set_userdata($currentdata);
				}
				// Get the data from cookie
				$data['weight'] = $this->session->userdata('weight');
			}
			else
			{
				// We are logged, so let's get it from the database
				$query = $this->db->query("SELECT userdata.value
																				FROM userdata
																				WHERE userdata.userid=".$this->userid()." AND userdata.name = 'weight' AND
																				userdata.added >= DATE '$sqldate' AND userdata.added < DATE '$sqldate' + INTERVAL '1 DAY'
																				;");
				if ($query->num_rows() == 0)
				{
					// We don't have data from today!
					$dataadd = array(
						'userid' => $this->userid(),
						'name' => 'weight',
						'value' => 80
						);
					$this->db->insert('userdata', $dataadd);
					$data['weight'] = 80;
				} 
				else
				{
					// We already have the item in the group!
					$data['weight'] = $query->row()->value;
				}
			}
		}
		else
		{
			// Let's set new value
			if ($this->islogged() == false)
			{
				$currentdata = array(
						   'weight' => $data['weight']
					   );
				$this->session->set_userdata($currentdata);
			}
			else
			{
				// We are logged, so let's get it from the database
				$query = $this->db->query("SELECT userdata.id, userdata.value
																				FROM userdata
																				WHERE userdata.userid=".$this->userid()." AND userdata.name = 'weight' AND
																				userdata.added >= DATE '$sqldate' AND userdata.added < DATE '$sqldate' + INTERVAL '1 DAY'
																				;");
				if ($query->num_rows() == 0)
				{
					// We don't have data from today!
					$dataadd = array(
						'userid' => $this->userid(),
						'name' => 'weight',
						'value' => $data['weight']
						);
					$this->db->insert('userdata', $dataadd);
				} 
				else
				{
					// We already have the data, so let's update it!
					$dataupdate = array(
						'userid' => $this->userid(),
						'name' => 'weight',
						'value' => $data['weight']
						);
					$this->db->where('id',$query->row()->id);
					$this->db->update('userdata', $dataupdate);
				}
			}
		}
		
		// Let's get height
		if (is_numeric($data['height']) == false)
		{
			// We don't know the user weight
			if ($this->islogged() == false)
			{
				// User is not logged, so data is stored in cookie
				if ($this->session->userdata('height') == false)
				{
					// We are not set in the cookie, so let's set it to defaults
					$currentdata = array(
							   'height' => 180
						   );
					$this->session->set_userdata($currentdata);
				}
				// Get the data from cookie
				$data['height'] = $this->session->userdata('height');
			}
			else
			{
				// We are logged, so let's get it from the database
				$query = $this->db->query("SELECT userdata.id, userdata.value
																				FROM userdata
																				WHERE userdata.userid=".$this->userid()." AND userdata.name = 'height' AND
																				userdata.added >= DATE '$sqldate' AND userdata.added < DATE '$sqldate' + INTERVAL '1 DAY'
																				;");
				if ($query->num_rows() == 0)
				{
					// We don't have data from today!
					$dataadd = array(
						'userid' => $this->userid(),
						'name' => 'height',
						'value' => 180
						);
					$this->db->insert('userdata', $dataadd);
					$data['height'] = 180;
				} 
				else
				{
					// We already have the item in the group!
					$data['height'] = $query->row()->value;
				}
			}
		}
		else
		{
			// Let's set new value
			if ($this->islogged() == false)
			{
				$currentdata = array(
						   'height' => $data['height']
					   );
				$this->session->set_userdata($currentdata);
			}
			else
			{
				// We are logged, so let's get it from the database
				$query = $this->db->query("SELECT userdata.id, userdata.value
																				FROM userdata
																				WHERE userdata.userid=".$this->userid()." AND userdata.name = 'height' AND
																				userdata.added >= DATE '$sqldate' AND userdata.added < DATE '$sqldate' + INTERVAL '1 DAY'
																				;");
				if ($query->num_rows() == 0)
				{
					// We don't have data from today!
					$dataadd = array(
						'userid' => $this->userid(),
						'name' => 'height',
						'value' => $data['height']
						);
					$this->db->insert('userdata', $dataadd);
				} 
				else
				{
					// We already have the data, so let's update it!
					$dataupdate = array(
						'userid' => $this->userid(),
						'name' => 'height',
						'value' => $data['height']
						);
					$this->db->where('id',$query->row()->id);
					$this->db->update('userdata', $dataupdate);
				}
			}
		}
		return $data;
	}
	
	function logout() {
		//Destroy session
		$this->session->sess_destroy();
	}
}

?>