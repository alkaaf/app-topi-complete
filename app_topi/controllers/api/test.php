<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class test extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->db2 = $this->load->database('api', TRUE);

    }

    public function kk()
    {   
       
        $this->db2->select("*");
        // $this->db2->where('TANGGAL_IMPORT', $tgl);
        $data = $this->db2->get('API_JUMLAH_KK')->result();

        echo "<pre>";
        var_dump($data);
        echo "</pre>";
        
       
    }

}
