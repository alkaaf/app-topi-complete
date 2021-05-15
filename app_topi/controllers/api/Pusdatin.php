<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
class Pusdatin extends REST_Controller {

	protected $db2;

	Protected $mapping_kawin = array(
		'1' => 	'BELUM KAWIN',
		'2'	=>	'KAWIN',
		'3'	=>	'CERAI HIDUP',
		'4'	=>	'CERAI MATI'

	);

    protected $maapping_jns_kelamin = array(
            '1' => 'Laki-Laki',
            '2' => 'Perempuan'
    );

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

	public function by_nik_get()
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
            TO_CHAR(BIODATA_WNI.TGL_LHR, 'DD-MM-YYYY') as TGL_LHR,
            DATA_KELUARGA.ALAMAT,
            PDDKN_MASTER.DESCRIP AS NAMA_PENDIDIKAN,
            BIODATA_WNI.JENIS_KLMIN,
            PKRJN_MASTER.DESCRIP AS NAMA_PEKERJAAN,
            DATA_KELUARGA.NO_KK,
            BIODATA_WNI.TMPT_LHR,
            DATA_KELUARGA.NO_RT,
            DATA_KELUARGA.NO_RW,
            BIODATA_WNI.STAT_KWN,
            BIODATA_WNI.NAMA_LGKP_IBU,
            BIODATA_WNI.NAMA_LGKP_AYAH,
            BIODATA_WNI.STAT_HBKEL
        ");

        $this->db2->join('DATA_KELUARGA', 'BIODATA_WNI.NO_KK = DATA_KELUARGA.NO_KK');
        $this->db2->join('PDDKN_MASTER', 'BIODATA_WNI.PDDK_AKH = PDDKN_MASTER.NO','left');
        $this->db2->join('PKRJN_MASTER', 'BIODATA_WNI.JENIS_PKRJN = PKRJN_MASTER.NO','left');
        $this->db2->join('SETUP_KEL', 'DATA_KELUARGA.NO_PROP = SETUP_KEL.NO_PROP AND DATA_KELUARGA.NO_KAB = SETUP_KEL.NO_KAB AND DATA_KELUARGA.NO_KEC = SETUP_KEL.NO_KEC AND DATA_KELUARGA.NO_KEL = SETUP_KEL.NO_KEL');
        $this->db2->join('SETUP_KEC', 'SETUP_KEL.NO_KEC = SETUP_KEC.NO_KEC AND SETUP_KEL.NO_KAB = SETUP_KEC.NO_KAB AND SETUP_KEL.NO_PROP = SETUP_KEC.NO_PROP');
        $this->db2->join('AGAMA_MASTER', 'BIODATA_WNI.AGAMA = AGAMA_MASTER.NO');

        $this->db2->where('NIK', $nik);
     
        $data = $this->db2->get('BIODATA_WNI', 1)->row_array();
        if ($data === null) {
            $this->db2->select("
                BIODATA_WNA.NIK,
                CONCAT(BIODATA_WNA.NAMA_PERTMA, CONCAT(' ', BIODATA_WNA.NAMA_KLRGA ))as NAMA_LGKP,
                TO_CHAR(BIODATA_WNA.TGL_LHR, 'DD-MM-YYYY') as TGL_LHR,
                DATA_KELUARGA.ALAMAT,
                PDDKN_MASTER.DESCRIP AS NAMA_PENDIDIKAN,
                BIODATA_WNA.JENIS_KLMIN,
                PKRJN_MASTER.DESCRIP AS NAMA_PEKERJAAN,
                DATA_KELUARGA.NO_KK,
                BIODATA_WNA.TMPT_LHR,
                DATA_KELUARGA.NO_RT,
			    DATA_KELUARGA.NO_RW,
                BIODATA_WNA.STAT_KWN,
                BIODATA_WNA.NAMA_LGKP_IBU,
                BIODATA_WNA.NAMA_LGKP_AYAH,
                BIODATA_WNA.STAT_HBKEL
            ");

            $this->db2->join('DATA_KELUARGA', 'BIODATA_WNA.NO_KK = DATA_KELUARGA.NO_KK');
            $this->db2->join('PDDKN_MASTER', 'BIODATA_WNA.PDDK_AKH = PDDKN_MASTER.NO','left');
            $this->db2->join('PKRJN_MASTER', 'BIODATA_WNA.JENIS_PKRJN = PKRJN_MASTER.NO','left');
            $this->db2->join('SETUP_KEL', 'DATA_KELUARGA.NO_PROP = SETUP_KEL.NO_PROP AND DATA_KELUARGA.NO_KAB = SETUP_KEL.NO_KAB AND DATA_KELUARGA.NO_KEC = SETUP_KEL.NO_KEC AND DATA_KELUARGA.NO_KEL = SETUP_KEL.NO_KEL');
            $this->db2->join('SETUP_KEC', 'SETUP_KEL.NO_KEC = SETUP_KEC.NO_KEC AND SETUP_KEL.NO_KAB = SETUP_KEC.NO_KAB AND SETUP_KEL.NO_PROP = SETUP_KEC.NO_PROP');
            $this->db2->join('AGAMA_MASTER', 'BIODATA_WNA.AGAMA = AGAMA_MASTER.NO');

            $this->db2->where('NIK', $nik);
         
            $data = $this->db2->get('BIODATA_WNA', 1)->row_array();
            if ($data !== null) {
                $data['KAWIN'] = $this->mapping_kawin[$data['STAT_KWN']];
                $data['JENIS_KLMIN'] = $this->maapping_jns_kelamin[$data['JENIS_KLMIN']];
                $data['STAT_HBKEL'] = $this->mapping_hub[$data['STAT_HBKEL']];
            }
        }else{
            
            $data['KAWIN'] = $this->mapping_kawin[$data['STAT_KWN']];
            $data['JENIS_KLMIN'] = $this->maapping_jns_kelamin[$data['JENIS_KLMIN']];
            $data['STAT_HBKEL'] = $this->mapping_hub[$data['STAT_HBKEL']];
            
        }
       if ($data == null) {
            $result = array('message'=> 'nik yang dicari tidak ada dalam database', 'status' => false);
            $this->response($result);
        }

        $result = array('data'=>$data, 'status' => true);
        $this->response($result);
        
    }

}

/* End of file Pusdatin.php */
/* Location: .//C/xampp/htdocs/app_topi/controllers/api/Pusdatin.php */