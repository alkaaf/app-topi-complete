<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('model_logs_activity');
	} 

	function index(){
		$this->load->view('view_logs_activity',$data);
	}

	function get_data_user()
	{
		
        $list = $this->model_logs_activity->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->nama;
            $row[] = $field->username;
            $row[] = $field->uri;
            $row[] = $field->params;
            $row[] = $field->api_key;
            $row[] = $field->ip_address;
            $row[] = $field->terakhir_akses;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->model_logs_activity->count_all(),
            "recordsFiltered" => $this->model_logs_activity->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
	}

}
