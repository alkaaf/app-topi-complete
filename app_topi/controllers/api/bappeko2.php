<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . '/libraries/REST_Controller.php';

class Bappeko extends REST_Controller {

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

    public function aggr_kk_get()
    {   
        header('Content-Type: application/json');
        
        $this->db2->select("*");
        
        $data = $this->db2->get('API_JUMLAH_KK')->result();

        if ($data == null) {
            $result = array('message'=> 'Data sedang kosong', 'status' => false);
            $this->response($result);
        }

        $result = array('data'=>$data, 'status' => true);
        $this->response($result);

    }

    public function aggr_usia_get()
    {   
        header('Content-Type: application/json');

        $data = $this->db2->query("
                SELECT
                    UPPER(GETNAMAKAB(NO_KAB, NO_PROP)) kota,
                    UPPER(GETNAMAKEC(NO_KEC, NO_KAB, NO_PROP)) kec,
                    UPPER(GETNAMAKEL(NO_KEL, NO_KEC, NO_KAB, NO_PROP)) kel, NO_KEL,
                  STRUKTUR_UMUR, LAKI_LAKI, PEREMPUAN, ADA_AKTA, TIDAK_ADA_AKTA
                FROM API_STRUKTUR_UMUR 
                WHERE GETNAMAKEL(NO_KEL, NO_KEC, NO_KAB, NO_PROP) IS NOT NULL
                ORDER BY NO_KEC
            ")->result();
        
        if ($data == null) {
            $result = array('message'=> 'Data sedang kosong', 'status' => false);
            $this->response($result);
        }

        $result = array('data'=>$data, 'status' => true);
        $this->response($result);

    }

	
}

/* End of file covid19.php */
/* Location: .//C/xampp/htdocs/app_topi/controllers/api/covid19.php */