<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->helper('url');

		$this->load->view('welcome_message');
	}

	public function res()
	{
		$this->output->enable_profiler(TRUE);

		$kol = $this->db->get('kolom')->result();

		$field = "NO_KK,";

		foreach ($kol as $value) {
			$field .= $value->field . ',';
		}

		$sel = rtrim($field,',');

		$this->db->select($sel);
		// $this->db->like('NAMA_LGKP', '');
		// $this->db->where('NO_KK', '3576012810070018');
		$this->db->where_in('STAT_KWN', '3,4');
		$this->db->where('JENIS_KLMIN ', 2);
		$this->db->where('TGL_LHR >', '1990-01-10 00:00:00');
		// $this->db->like('alamat', 'sinoman');
		$this->db->where('TGL_LHR <', '1997-01-10 00:00:00');
		$this->db->order_by('NAMA_LGKP', 'ASC');
		$res =$this->db->get('vv_biodata');
		echo "<pre>";
		print_r($res->result());
	}

	public function ress()
	{
		$this->output->enable_profiler(TRUE);
		$kk= $this->input->get_post('kk');
		$kol = $this->db->get('kolom')->result();

		$field = "NO_KK,";

		foreach ($kol as $value) {
			$field .= $value->field . ',';
		}

		
$sel = rtrim($field,',');
		$this->db->select($sel);
		// $this->db->like('NAMA_LGKP', '');
		$this->db->where('NO_KK', $kk);
		// $this->db->where('STAT_KWN ', 3);
		// $this->db->where('JENIS_KLMIN ', 2);
		// $this->db->where('TGL_LHR >', '1987-01-10 00:00:00');
		// // $this->db->like('alamat', 'sinoman');
		// $this->db->where('TGL_LHR <', '1997-01-10 00:00:00');
		$this->db->order_by('TGL_LHR', 'ASC');
		$res =$this->db->get('vv_biodata');
		echo "<pre>";
		print_r($res->result());
	}
}
