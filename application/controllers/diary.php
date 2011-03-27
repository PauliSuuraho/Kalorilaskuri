<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Diary extends CI_Controller {

	function Diary() {
		parent::__construct();
		
		$this->load->helper('url');
	}

	function index() {
		//var_dump($_POST);
		$headerdata['title'] = 'Kalorilaskuri';
		$headerdata['pagetitle'] = 'P&auml;iv&auml;kirja';
		$this->load->view('header_view',$headerdata);
		
		// Start of page
		$data['foods'] = $this->db->get('fooddiaryitem');
		$this->load->view('diary_view',$data);
		// End of page
		
		$this->load->view('footer_view');
	}
	
	function foods() {
		$headerdata['title'] = 'Ruokasivu - Kalorilaskuri';
		$headerdata['pagetitle'] = 'Ruokasivu';
		$this->load->view('header_view',$headerdata);
		
		// Start of page
		$searchword = '';
		if (isset($_GET['search-food'])) {
			$searchword = $_GET['search-food'];
		}
		if ($searchword == '') {
			$data['foods'] = $this->db->query("SELECT * FROM Food ORDER BY name LIMIT 40; ");
		}
		else
		{
			$data['foods'] = $this->db->query("SELECT * FROM Food WHERE lower(name) LIKE lower('%$searchword%') ORDER BY name LIMIT 40; ");
		}
		$this->load->view('foods_view',$data);
		// End of page
		
		$this->load->view('footer_view');
	}
}

?>