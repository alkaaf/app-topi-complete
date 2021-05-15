<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Biodata extends REST_Controller {

	protected $db2;

	
	Protected $mapping_pendidikan = array(
		'1' => array('id'=>"1",'descripsi' => "SD"),
		'2' => array('id'=>"1",'descripsi' => "SD"),
		'3' => array('id'=>"1",'descripsi' => "SD"),
		'4' => array('id'=>"2",'descripsi' => "SMP"),
		'5' => array('id'=>"3",'descripsi' => "SMA"),
		'6' => array('id'=>"4",'descripsi' => "S1"),
		'7' => array('id'=>"4",'descripsi' => "S1"),
		'8' => array('id'=>"4",'descripsi' => "S1"),
		'9' => array('id'=>"5",'descripsi' => "S2"),
		'10' => array('id'=>"6",'descripsi' => "S3")
	);

	protected $mapping_pekerjaan = array(
		'5'		=> array('id'=>'1', 'descripsi' => 'PNS'),
		'6'		=> array('id'=>'2', 'descripsi' => 'TNI'),
		'7'		=> array('id'=>'3', 'descripsi' => 'Porli'),
		'15'	=> array('id'=>'4', 'descripsi' => 'Swasta'),
		'84'	=> array('id'=>'5', 'descripsi' => 'Wirausaha'),
		'24'	=> array('id'=>'5', 'descripsi' => 'Wirausaha'),
		'25'	=> array('id'=>'5', 'descripsi' => 'Wirausaha'),
		'26'	=> array('id'=>'5', 'descripsi' => 'Wirausaha'),
		'27'	=> array('id'=>'5', 'descripsi' => 'Wirausaha'),
		'28'	=> array('id'=>'5', 'descripsi' => 'Wirausaha'),
		'29'	=> array('id'=>'5', 'descripsi' => 'Wirausaha'),
		'30'	=> array('id'=>'5', 'descripsi' => 'Wirausaha'),
		'31'	=> array('id'=>'5', 'descripsi' => 'Wirausaha'),
		'32'	=> array('id'=>'5', 'descripsi' => 'Wirausaha'),
		'33'	=> array('id'=>'5', 'descripsi' => 'Wirausaha'),
		'34'	=> array('id'=>'5', 'descripsi' => 'Wirausaha'),
		'39'	=> array('id'=>'5', 'descripsi' => 'Wirausaha'),
		'88'	=> array('id'=>'5', 'descripsi' => 'Wirausaha'),
	);
	
	function __construct()
    {
        parent::__construct();
        $this->db2 = $this->load->database('api', TRUE);

    }
	
	public function kuisioner_get()
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
            BIODATA_WNI.NAMA_LGKP,
            BIODATA_WNI.TGL_LHR,
            DATA_KELUARGA.ALAMAT,
            PDDKN_MASTER.DESCRIP as NAMA_PENDIDIKAN,
            BIODATA_WNI.PDDK_AKH AS ID_PENDIDIKAN,
            BIODATA_WNI.JENIS_PKRJN,
            BIODATA_WNI.JENIS_KLMIN,
            PKRJN_MASTER.DESCRIP AS NAMA_PEKERJAAN
        ");

        $this->db2->join('DATA_KELUARGA', 'BIODATA_WNI.NO_KK = DATA_KELUARGA.NO_KK');
        $this->db2->join('PDDKN_MASTER', 'BIODATA_WNI.PDDK_AKH = PDDKN_MASTER.NO','left');
        $this->db2->join('PKRJN_MASTER', 'BIODATA_WNI.JENIS_PKRJN = PKRJN_MASTER.NO','left');

        $this->db2->where('NIK', $nik);
     
        $data = $this->db2->get('BIODATA_WNI', 1)->row_array();

       
       	
        if ($data == null) {

        	
            $result = array('message'=> 'nik yang dicari tidak ada dalam database', 'status' => false);
            $this->response($result);
        }
        $data['NAMA_PENDIDIKAN'] = $this->mapping_pendidikan[$data['ID_PENDIDIKAN']] ['descripsi'];
    	$data['ID_PENDIDIKAN'] = $this->mapping_pendidikan[$data['ID_PENDIDIKAN']] ['id'];
    	$data['NAMA_PEKERJAAN'] = (isset($this->mapping_pekerjaan[$data['JENIS_PKRJN']])) ? $this->mapping_pekerjaan[$data['JENIS_PKRJN']]['descripsi'] : $data['NAMA_PEKERJAAN'];
    	$data['JENIS_PKRJN'] = (isset($this->mapping_pekerjaan[$data['JENIS_PKRJN']])) ? $this->mapping_pekerjaan[$data['JENIS_PKRJN']]['id'] : '6';
      	$result = array('data'=>$data, 'status' => true);
        $this->response($result);
       
    }


}

/* End of file Biodata.php */
/* Location: .//C/xampp/htdocs/app_topi/controllers/api/Biodata.php */