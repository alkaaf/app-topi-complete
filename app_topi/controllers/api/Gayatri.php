<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Gayatri extends REST_Controller {
 protected $mapping_hub = array(
            1 => 'KEPALA KELUARGA',
            2 =>  'SUAMI',
            3 => 'ISTRI',
            4 => 'ANAK',
            5 =>  'MENANTU',
            6 =>  'CUCU',
            7 =>  'ORANGTUA',
            8 =>  'MERTUA',
            9 =>   'FAMILI LAIN',
            10 => 'PEMBANTU',
            11 => 'LAINNYA'
    );
	function __construct()
    {
        parent::__construct();
        $this->db2 = $this->load->database('api', TRUE);

    }

	public function biodata_get()
    {
        header('Content-Type: application/json');
    	$nik = $this->get('nik');

    	$nik = preg_replace('/[^0-9]/', '', $nik);
        if (strlen($nik) != 16) {
        	$result = array('message'=> 'nik tidak sesuai format', 'status' => false);
            $this->response($result);
        }
        $this->db2->select("
            BIODATA_WNI.NIK,
			DATA_KELUARGA.NO_KK,
			BIODATA_WNI.NAMA_LGKP,
			TO_CHAR(BIODATA_WNI.TGL_LHR, 'DD-MM-YYYY') as TGL_LHR,
            BIODATA_WNI.TMPT_LHR,
			DATA_KELUARGA.ALAMAT,
			DATA_KELUARGA.DUSUN,
			DATA_KELUARGA.NO_RT,
			DATA_KELUARGA.NO_RW,
			DATA_KELUARGA.NAMA_KEP,
			AGAMA_MASTER.DESCRIP as AGAMA,
			PDDKN_MASTER.DESCRIP AS PENDIDIKAN,
			PKRJN_MASTER.DESCRIP AS PEKERJAAN,
			BIODATA_WNI.JENIS_KLMIN,
			SETUP_KEL.NAMA_KEL,
            BIODATA_WNI.STAT_HBKEL
        ");

        $this->db2->join('DATA_KELUARGA', 'BIODATA_WNI.NO_KK = DATA_KELUARGA.NO_KK');
        $this->db2->join('PDDKN_MASTER', 'BIODATA_WNI.PDDK_AKH = PDDKN_MASTER.NO','left');
        $this->db2->join('PKRJN_MASTER', 'BIODATA_WNI.JENIS_PKRJN = PKRJN_MASTER.NO','left');
        $this->db2->join('AGAMA_MASTER', 'BIODATA_WNI.AGAMA = AGAMA_MASTER.NO','left');
        $this->db2->join('SETUP_KEL', 'DATA_KELUARGA.NO_KAB = SETUP_KEL.NO_KAB AND DATA_KELUARGA.NO_PROP = SETUP_KEL.NO_PROP AND DATA_KELUARGA.NO_KEC = SETUP_KEL.NO_KEC AND DATA_KELUARGA.NO_KEL = SETUP_KEL.NO_KEL');

        $this->db2->where('NIK', $nik);
     
        $data = $this->db2->get('BIODATA_WNI', 1)->row_array();
        $data['STAT_HBKEL'] = $this->mapping_hub[$data['STAT_HBKEL']];
       
       	
        if ($data == null) {

        	$result = array('message'=> 'nik yang dicari tidak ada dalam database', 'status' => false);
            $this->response($result);
        }
        
        $result = array('data'=>$data, 'status' => true);
        $this->response($result);
    }

}

/* End of file Gayatri.php */
/* Location: .//C/xampp/htdocs/app_topi/controllers/api/Gayatri.php */