<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . '/libraries/REST_Controller.php';

// class Get NIK Rest ApI
class Dispendik extends REST_Controller {

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
        $this->db3 = $this->load->database('siakmojo', TRUE);
    }

    
	public function input_nik_get()
    {
        header('Content-Type: application/json');
        $nik = $this->get('nik');

        $nik = preg_replace('/[^0-9]/', '', $nik);
        if (strlen($nik) != 16) {
            $result = array('message'=> 'nik tidak sesuai format', 'status' => false);
            $this->response($result);
        }
        $this->db2->select("
            
            BIODATA_WNI.NAMA_LGKP,
            DATA_KELUARGA.NO_KK,
            BIODATA_WNI.NIK,
            BIODATA_WNI.TMPT_LHR,
            TO_CHAR(BIODATA_WNI.TGL_LHR, 'DD-MM-YYYY') as TGL_LHR,
            BIODATA_WNI.JENIS_KLMIN,
            DATA_KELUARGA.ALAMAT,  
            DATA_KELUARGA.NO_RT,
            DATA_KELUARGA.NO_RW,
            SETUP_KEL.NAMA_KEL,
            SETUP_KEC.NAMA_KEC,
            
                        
        ");

        $this->db2->join('DATA_KELUARGA', 'BIODATA_WNI.NO_KK = DATA_KELUARGA.NO_KK');
        $this->db2->join('PDDKN_MASTER', 'BIODATA_WNI.PDDK_AKH = PDDKN_MASTER.NO','left');
        $this->db2->join('PKRJN_MASTER', 'BIODATA_WNI.JENIS_PKRJN = PKRJN_MASTER.NO','left');
        $this->db2->join('SETUP_KEL', 'DATA_KELUARGA.NO_PROP = SETUP_KEL.NO_PROP AND DATA_KELUARGA.NO_KAB = SETUP_KEL.NO_KAB AND DATA_KELUARGA.NO_KEC = SETUP_KEL.NO_KEC AND DATA_KELUARGA.NO_KEL = SETUP_KEL.NO_KEL');
        $this->db2->join('SETUP_KEC', 'SETUP_KEL.NO_KEC = SETUP_KEC.NO_KEC AND SETUP_KEL.NO_KAB = SETUP_KEC.NO_KAB AND SETUP_KEL.NO_PROP = SETUP_KEC.NO_PROP');
        $this->db2->join('AGAMA_MASTER', 'BIODATA_WNI.AGAMA = AGAMA_MASTER.NO');
       
        $this->db2->where('NIK', $nik);
     
        $data = $this->db2->get('BIODATA_WNI')->row_array();
        //$data['KAWIN'] = $this->mapping_kawin[$data['STAT_KWN']];
        if ($data === null) {
            $this->db2->select("
                BIODATA_WNA.NIK,
                CONCAT(BIODATA_WNA.NAMA_PERTMA, CONCAT(' ', BIODATA_WNA.NAMA_KLRGA ))as NAMA_LGKP,
                TO_CHAR(BIODATA_WNA.TGL_LHR, 'DD-MM-YYYY') as TGL_LHR,
                DATA_KELUARGA.ALAMAT,
                BIODATA_WNA.JENIS_KLMIN,
                DATA_KELUARGA.NO_KK,
                BIODATA_WNA.TMPT_LHR,
                DATA_KELUARGA.NO_RT,
                DATA_KELUARGA.NO_RW,
                SETUP_KEL.NAMA_KEL,
                SETUP_KEC.NAMA_KEC,
                DATA_KELUARGA.KODE_POS
                
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
                //$data['KAWIN'] = $this->mapping_kawin[$data['STAT_KWN']];
                $data['JENIS_KLMIN'] = $this->maapping_jns_kelamin[$data['JENIS_KLMIN']];
                $data['STAT_HBKEL'] = $this->mapping_hub[$data['STAT_HBKEL']];
            }
        }
                 else{
            //$data['KWRNGRN'] = 'WNI';
            //$data['STAT_KWN'] = $this->mapping_kawin[$data['STAT_KWN']];
            $data['JENIS_KLMIN'] = $this->maapping_jns_kelamin[$data['JENIS_KLMIN']];
            //$data['STAT_HBKEL'] = $this->mapping_hub[$data['STAT_HBKEL']];
            
        }
       if ($data == null) {
            $result = array('message'=> 'nik yang dicari tidak ada dalam database', 'status' => false);
            $this->response($result);
        }

        $result = array('data'=>$data, 'status' => true);
        $this->response($result);
        
    }  


    // Fungsi Agregat Wilayah
    public function agg_wilayah_get()
    {
        header('Content-Type: application/json');
       
       	$query = $this->db3->query("SELECT 
									NAMA_KEC, NAMA_KEL, DUSUN, RW, RT, COUNT(*) as JUMLAH
									FROM tm_warga 
									GROUP BY NAMA_KEC, NAMA_KEL, DUSUN, RW, RT
								 "); 
      
        $data = $query->result();
        //var_dump($data);
         $this->response($data);
    }
    // Fungsi Agregat Range Usia 
	public function usia_pddk_get()
    {
        header('Content-Type: application/json');
       
       	$query = $this->db3->query (" SELECT
       		                        NAMA_KEC,
									SUM(IF(USIA between 2 and 4,1,0)) 'PAUD',
									SUM(IF(USIA between 5 and 6,1,0)) 'TK',
									SUM(IF(USIA between 7 and 12,1,0)) 'SD',
									SUM(IF(USIA between 13 and 15,1,0)) 'SMP',
									(SUM(IF(USIA between 2 and 4,1,0)) +
									SUM(IF(USIA between 5 and 6,1,0))+
									SUM(IF(USIA between 7 and 12,1,0)) +
									SUM(IF(USIA between 13 and 15,1,0)))  AS Jumlah
									FROM tm_warga GROUP BY NAMA_KEC
       		                      "); 
      
        $data = $query->result();
        //var_dump($data);
         $this->response($data);
    }
    public function agg_usia_get()
    {
        header('Content-Type: application/json');
       
       	$query = $this->db3->query (" SELECT
       		                        NAMA_KEL,
									SUM(IF(USIA between 0 and 4,1,0)) '0-4',
									SUM(IF(USIA between 5 and 9,1,0)) '5-9',
									SUM(IF(USIA between 10 and 14,1,0)) '10-14',
									SUM(IF(USIA between 15 and 19,1,0)) '15-19',
									SUM(IF(USIA between 20 and 24,1,0)) '20-24',
									SUM(IF(USIA between 25 and 29,1,0)) '25-29',
									SUM(IF(USIA between 30 and 34,1,0)) '30-34',
									SUM(IF(USIA between 35 and 39,1,0)) '35-39',
									SUM(IF(USIA between 40 and 44,1,0)) '40-44',
									SUM(IF(USIA between 45 and 49,1,0)) '45-49',
									SUM(IF(USIA between 50 and 54,1,0)) '50-54',
									SUM(IF(USIA between 55 and 59,1,0)) '55-59',
									SUM(IF(USIA between 60 and 64,1,0)) '60-64',
									SUM(IF(USIA between 65 and 69,1,0)) '65-69',
									SUM(IF(USIA between 70 and 74,1,0)) '70-74',
									SUM(IF(USIA >= 75,1,0)) '75 Keatas',
									count(1) Jumlah
									FROM tm_warga GROUP BY NAMA_KEL
       		                      "); 
      
        $data = $query->result();
        //var_dump($data);
         $this->response($data);
    }
    // Fungsi Agregat Jenis Kelamin 
    // Fungsi Agregat Jenis Kelamin 
	public function agg_jenkel_get()
    {
        header('Content-Type: application/json');
       
       	$query = $this->db3->query (" SELECT
       		                         NAMA_KEL,
									 SUM(IF(JENIS_KELAMIN = 'Laki-laki', 1,0)) as L,
									 SUM(IF(JENIS_KELAMIN = 'Perempuan', 1,0)) as P,
									 count(1) Jumlah
									 
									from tm_warga
									group by NAMA_KEL
       		                      "); 
      
        $data = $query->result();
        $this->response($data);
    }
    // Fungsi Agregat Status Pernikahan 
	public function agg_status_get()
    {
        header('Content-Type: application/json');
       
       	$query = $this->db3->query (" SELECT
       		                         NAMA_KEL,
									SUM(IF(STATUS = 'Belum Kawin',1,0)) 'Belum Kawin',
									SUM(IF(STATUS = 'Kawin',1,0)) 'Kawin', 
									SUM(IF(STATUS = 'Cerai Hidup',1,0)) 'Cerai Hidup',
									SUM(IF(STATUS = 'Cerai Mati',1,0)) 'Cerai Mati', 
									count(1) Jumlah

									FROM tm_warga GROUP BY NAMA_KEL
       		                      "); 
      
        $data = $query->result();
        $this->response($data);
    }
    // Fungsi Agregat Agama 
	public function agg_agama_get()
    {
        header('Content-Type: application/json');
       
       	$query = $this->db3->query (" SELECT
       		                         NAMA_KEL,
									SUM(IF(AGAMA = '1',1,0)) 'Islam',
									SUM(IF(AGAMA = '2',1,0)) 'Kristen',
									SUM(IF(AGAMA = '3',1,0)) 'Katholik',
									SUM(IF(AGAMA = '4',1,0)) 'Hindu',
									SUM(IF(AGAMA = '5',1,0)) 'Budha',
									SUM(IF(AGAMA = '6',1,0)) 'Konghucu',
									SUM(IF(AGAMA = '7',1,0)) 'Aliran Kepercayaan',
									count(1) Jumlah

									FROM tm_warga GROUP BY NAMA_KEL
       		                      "); 
      
        $data = $query->result();
        $this->response($data);
    }
    // Fungsi Agregat Pekerjaan 
	public function agg_pekerjaan_get()
    {
        header('Content-Type: application/json');
       
       	$query = $this->db3->query (" SELECT
       		                         NAMA_KEL,
									SUM(IF(JENIS_PKJRN = '1' ,1,0)) 'TIDAK BEKERJA',
									SUM(IF((JENIS_PKJRN = '9') or (JENIS_PKJRN = '10') or (JENIS_PKJRN = '11') or (JENIS_PKJRN = '20') or (JENIS_PKJRN = '21') or (JENIS_PKJRN = '22'),1,0)) 'PERTANIAN/ PETERNAKAN/ PERIKANAN',
									SUM(IF((JENIS_PKJRN = '8') or (JENIS_PKJRN = '84'),1,0)) 'PERDAGANGAN',
									SUM(IF(JENIS_PKJRN = '12' ,1,0)) 'INDUSTRI',
									SUM(IF((JENIS_PKJRN = '64') or (JENIS_PKJRN = '65') or (JENIS_PKJRN = '72') or (JENIS_PKJRN = '73') or (JENIS_PKJRN = '74') or (JENIS_PKJRN = '75') or (JENIS_PKJRN = '76'),1,0)) 'JASA KEMASYARAKATAN',
									SUM(IF((JENIS_PKJRN = '13') or (JENIS_PKJRN = '69'),1,0)) 'KONSTRUKSI',
									SUM(IF((JENIS_PKJRN = '4') or (JENIS_PKJRN = '5') or (JENIS_PKJRN = '6') or (JENIS_PKJRN = '7') or (JENIS_PKJRN = '16') or (JENIS_PKJRN = '17') or (JENIS_PKJRN = '48') or (JENIS_PKJRN = '49') or (JENIS_PKJRN = '50') or (JENIS_PKJRN = '51') or (JENIS_PKJRN = '52') or (JENIS_PKJRN = '53') or (JENIS_PKJRN = '54') or (JENIS_PKJRN = '55') or (JENIS_PKJRN = '56') or (JENIS_PKJRN = '57') or (JENIS_PKJRN = '58') or (JENIS_PKJRN = '59') or (JENIS_PKJRN = '60') or (JENIS_PKJRN = '61') or (JENIS_PKJRN = '62') or (JENIS_PKJRN = '63'),1,0)) 'PEMERINTAHAN',
									SUM(IF(JENIS_PKJRN = '3' ,1,0)) 'PELAJAR/ MAHASISWA',
									SUM(IF(JENIS_PKJRN = '15' ,1,0)) 'SWASTA',
									SUM(IF(JENIS_PKJRN = '88' ,1,0)) 'WIRASWASTA',

									SUM(IF((JENIS_PKJRN = '2') or (JENIS_PKJRN = '14') or (JENIS_PKJRN = '18') or (JENIS_PKJRN = '19') or (JENIS_PKJRN = '23') or (JENIS_PKJRN = '24') or (JENIS_PKJRN = '25') or (JENIS_PKJRN = '26') or (JENIS_PKJRN = '27') or (JENIS_PKJRN = '28') or (JENIS_PKJRN = '29') or (JENIS_PKJRN = '30') or (JENIS_PKJRN = '31') or (JENIS_PKJRN = '32') or (JENIS_PKJRN = '33') or (JENIS_PKJRN = '34') or (JENIS_PKJRN = '35') or (JENIS_PKJRN = '36') or (JENIS_PKJRN = '37') or (JENIS_PKJRN = '38') or (JENIS_PKJRN = '39') or (JENIS_PKJRN = '40') or (JENIS_PKJRN = '41') or (JENIS_PKJRN = '42') or (JENIS_PKJRN = '43') or (JENIS_PKJRN = '44') or (JENIS_PKJRN = '45') or (JENIS_PKJRN = '46') or (JENIS_PKJRN = '47') or (JENIS_PKJRN = '66') or (JENIS_PKJRN = '67') or (JENIS_PKJRN = '68') or (JENIS_PKJRN = '70') or (JENIS_PKJRN = '71') or (JENIS_PKJRN = '77') or (JENIS_PKJRN = '78') or (JENIS_PKJRN = '79') or (JENIS_PKJRN = '80') or (JENIS_PKJRN = '81') or (JENIS_PKJRN = '82') or (JENIS_PKJRN = '83') or (JENIS_PKJRN = '85') or (JENIS_PKJRN = '86') or (JENIS_PKJRN = '87') or (JENIS_PKJRN = '89'),1,0)) 'LAINNYA',
									count(1) Jumlah

									FROM tm_warga GROUP BY NAMA_KEL
       		                      "); 
      
        $data = $query->result();
        $this->response($data);
    }
   // Fungsi Agregat Pendidikan 
	public function agg_pendidikan_get()
    {
        header('Content-Type: application/json');
       
       	$query = $this->db3->query (" SELECT
       		                         NAMA_KEL,
									SUM(IF(PDDK_AKH = '1',1,0)) 'Tidak/Belum Sekolah',
									SUM(IF(PDDK_AKH = '2',1,0)) 'Belum Tamat SD/Sederajat',
									SUM(IF(PDDK_AKH = '3',1,0)) 'Tamat SD/Sederajat',
									SUM(IF(PDDK_AKH = '4',1,0)) 'SLTP/Sederajat',
									SUM(IF(PDDK_AKH = '5',1,0)) 'SLTA/Sederajat',
									SUM(IF(PDDK_AKH = '6',1,0)) 'Diploma I/II',
									SUM(IF(PDDK_AKH = '7',1,0)) 'Akademi/Diploma III/S. Muda',
									SUM(IF(PDDK_AKH = '8',1,0)) 'Diploma IV/Strata I',
									SUM(IF(PDDK_AKH = '9',1,0)) 'Strata II',
									SUM(IF(PDDK_AKH = '10',1,0)) 'Strata III',
									count(1) Jumlah

									FROM tm_warga GROUP BY NAMA_KEL
       		                      "); 
      
        $data = $query->result();
        $this->response($data);
    }
   // Fungsi Agregat Kata Nikah 
	public function agg_aktanikah_get()
    {
        header('Content-Type: application/json');
       
       	$query = $this->db3->query (" SELECT
       		                         NAMA_KEL,
									SUM(IF(AKTA_KAWIN = 'Ada',1,0)) 'Punya',
									SUM(IF(AKTA_KAWIN is NULL,1,0)) 'Tidak',
									count(1) Jumlah

									FROM tm_warga GROUP BY NAMA_KEL
       		                      "); 
      
        $data = $query->result();
        $this->response($data);
    }
   // Fungsi Agregat KK 
	public function agg_kk_get()
    {
        header('Content-Type: application/json');
       
       	$query = $this->db3->query (" SELECT
       		                          NAMA_KEL,
									 SUM(IF(SHDK = 'Kepala Keluarga', 1,0)) as 'Jumlah KK'
									 
									from tm_warga
									group by NAMA_KEL
       		                      "); 
      
        $data = $query->result();
        $this->response($data);
    } 
   // Fungsi Agregat SHDK
	public function agg_shdk_get()
    {
        header('Content-Type: application/json');
       
       	$query = $this->db3->query (" SELECT
       		                          NAMA_KEL,
									SUM(IF(SHDRT = '1',1,0)) 'Kepala Keluarga',
									SUM(IF(SHDRT = '2',1,0)) 'Suami',
									SUM(IF(SHDRT = '3',1,0)) 'Istri',
									SUM(IF(SHDRT = '4',1,0)) 'Anak',
									SUM(IF(SHDRT = '5',1,0)) 'Menantu',
									SUM(IF(SHDRT = '6',1,0)) 'Cucu',
									SUM(IF(SHDRT = '7',1,0)) 'Orang Tua',
									SUM(IF(SHDRT = '8',1,0)) 'Mertua',
									SUM(IF(SHDRT = '9',1,0)) 'Famili Lain',
									SUM(IF(SHDRT = '10',1,0)) 'Pembantu',
									SUM(IF(SHDRT = '11',1,0)) 'Lainnya',
									count(1) Jumlah

									FROM tm_warga GROUP BY NAMA_KEL
       		                      "); 
      
        $data = $query->result();
        $this->response($data);
    } 
   // Fungsi Agregat Akta LAhir
	public function agg_aktalahir_get()
    {
        header('Content-Type: application/json');
       
       	$query = $this->db3->query (" SELECT
       		                        NAMA_KEL,
									SUM(IF(AKTA_LAHIR = 'Ada',1,0)) 'Punya',
									SUM(IF(AKTA_LAHIR is NULL,1,0)) 'Tidak',
									count(1) Jumlah

									FROM tm_warga GROUP BY NAMA_KEL
       		                      "); 
      
        $data = $query->result();
        $this->response($data);
    } 



}


class Agregat extends REST_Controller 
{
// class Agregat extends CI_Controller{
	protected $db2;

	  

    function __construct()
    {
        parent::__construct();
        $this->db2 = $this->load->database('api', TRUE);
        $this->db3 = $this->load->database('siakmojo', TRUE);
    }

    public function agg_coba_get()
    {
        header('Content-Type: application/json');
       
       	$query = $this->db3->query("SELECT 
										AGAMA1, 
										COUNT(AGAMA)
									FROM tm_warga GROUP BY AGAMA1 "); 
      
        $data = $query->result();
        //var_dump($data);
         $this->response($data);
    }
	public function agg_jenkel_get()
    {
        header('Content-Type: application/json');
       
       	$query = $this->db3->query ("SELECT 
       									NAMA_KEL,
  										SUM(IF(JENIS_KELAMIN = 'Perempuan',1,0)) P,
										SUM(IF(JENIS_KELAMIN = 'Laki-laki',1,0)) L 
									FROM tm_warga GROUP BY NAMA_KEL"); 
      
        $data = $query->result();
        //var_dump($data);
         $this->response($data);
    }
	public function agg_status_get()
    {
        header('Content-Type: application/json');
       
       	$query = $this->db3->query ("SELECT 
       									NAMA_KEL,
										SUM(IF(STATUS = 'Belum Kawin',1,0)) 'Belum Kawin',
										SUM(IF(STATUS = 'Kawin',1,0)) 'Kawin', 
										SUM(IF(STATUS = 'Cerai Hidup',1,0)) 'Cerai Hidup',
										SUM(IF(STATUS = 'Cerai Mati',1,0)) 'Cerai Mati' 
										FROM tm_warga GROUP BY NAMA_KEL"); 
      
        $data = $query->result();
        //var_dump($data);
         $this->response($data);
    }
	public function agg_pddk_get()
    {
        header('Content-Type: application/json');
       
       	$query = $this->db3->query ("
       								SELECT 
NAMA_KEL,
CASE 
	WHEN PDDK_AKH = '1' THEN 'Tidak/Belum Sekolah'
	WHEN PDDK_AKH = '2' THEN 'Belum Tamat SD/Sederajat'
	WHEN PDDK_AKH = '3' THEN 'Tamat SD/Sederajat'
	WHEN PDDK_AKH = '4' THEN 'SLTP/Sederajat'
	WHEN PDDK_AKH = '5' THEN 'SLTA/Sederajat'
	WHEN PDDK_AKH = '6' THEN 'Diploma I/II'
	WHEN PDDK_AKH = '7' THEN 'Akademi/Diploma III/S. Muda'
	WHEN PDDK_AKH = '8' THEN 'Diploma IV/Strata I'
	WHEN PDDK_AKH = '9' THEN 'Strata II'
	WHEN PDDK_AKH = '10' THEN 'Strata III'
END pendidikana,
 SUM(IF(JENIS_KELAMIN = 'Laki-laki', 1,0)) as L,
 SUM(IF(JENIS_KELAMIN = 'Perempuan', 1,0)) as P,
 count(1) Jumlah
FROM
	tm_warga
GROUP BY NAMA_KEL, pendidikana
										"); 
      
        $data = $query->result();
        //var_dump($data);
         $this->response($data);
    }

public function agg_wajib_get()
    {
        header('Content-Type: application/json');
       
       	$query = $this->db3->query ("
       								SELECT 
										NAMA_KEL, 
											CASE 
 											 	WHEN USIA < 17  then 'Wajib KIA'
 												WHEN USIA >= 17  then 'Wajib KTP'
											END umur,
 									SUM(IF(JENIS_KELAMIN = 'Laki-laki', 1,0)) as L,
 									SUM(IF(JENIS_KELAMIN = 'Perempuan', 1,0)) as P,
									count(1) Jumlah
									FROM
										tm_warga
									GROUP BY NAMA_KEL, umur

										"); 
      
        $data = $query->result();
        //var_dump($data);

         $this->response($data);
    }    
public function agg_range_get()
    {
        header('Content-Type: application/json');
       
       	$query = $this->db3->query ("
       								SELECT 
										NAMA_KEL,
  case 
    when USIA between 0 and 4 then '0-4'
    when USIA between 5 and 9 then '5-9'
    when USIA between 10 and 14 then '10-14'
		when USIA between 15 and 19 then '15-19'
		when USIA between 20 and 24 then '20-24'
		when USIA between 25 and 29 then '25-29'
		when USIA between 30 and 34 then '30-34'
		when USIA between 35 and 39 then '35-39'
		when USIA between 40 and 44 then '40-44'
		when USIA between 45 and 49 then '45-49'
		when USIA between 50 and 54 then '50-54'
		when USIA between 55 and 59 then '55-59'
		when USIA between 60 and 64 then '60-64'
		when USIA between 65 and 69 then '65-69'
		when USIA between 70 and 74 then '70-74'
    else '75 Keatas'
  end as umur,
 SUM(IF(JENIS_KELAMIN = 'Laki-laki', 1,0)) as L,
 SUM(IF(JENIS_KELAMIN = 'Perempuan', 1,0)) as P,
 count(1) Jumlah

  
from tm_warga
group by NAMA_KEL, umur 

										"); 
      
        $data = $query->result();
        //var_dump($data);
         $this->response($data);
    }    

	public function input_kel_get()
    {
        header('Content-Type: application/json');
        //$kel = $this->get('kel');

       
       	$query = $this->db2->query("SELECT
							SIAKOFF.T5_STT_AGAMA.BLN,
							SIAKOFF.T5_STT_AGAMA.ISLAM,
							SIAKOFF.T5_STT_AGAMA.KRISTEN,
							SIAKOFF.T5_STT_AGAMA.KATHOLIK,
							SIAKOFF.T5_STT_AGAMA.HINDU,
							SIAKOFF.T5_STT_AGAMA.BUDHA,
							SIAKOFF.T5_STT_AGAMA.KONGHUCU,
							SIAKOFF.T5_STT_AGAMA.KEPERCAYAAN,
							SIAKOFF.T5_STT_AGAMA.NO_PROP,
							SIAKOFF.T5_STT_AGAMA.NO_KAB,
							SIAKOFF.T5_STT_AGAMA.NO_KEC,
							SIAKOFF.T5_STT_AGAMA.NO_KEL
							FROM
							SIAKOFF.T5_STT_AGAMA
							WHERE
							T5_STT_AGAMA.BLN= TO_DATE('2015-05-01', 'yyyy-mm-dd')
							"); 
   //      $this->db2->select("
   //          T5_STT_AGAMA.BLN,
			// T5_STT_AGAMA.ISLAM,
			// T5_STT_AGAMA.KRISTEN,
			// T5_STT_AGAMA.KATHOLIK,
			// T5_STT_AGAMA.HINDU,
			// T5_STT_AGAMA.BUDHA,
			// T5_STT_AGAMA.KONGHUCU,
			// T5_STT_AGAMA.KEPERCAYAAN,
			// T5_STT_AGAMA.NO_PROP,
			// T5_STT_AGAMA.NO_KAB,
			// T5_STT_AGAMA.NO_KEC,
			// T5_STT_AGAMA.NO_KEL
			
   //      ");

   //      $this->db2->FROM ('T5_STT_AGAMA');
   //      //$this->db2->where('T5_STT_AGAMA.NO_KEL', $kel);
   //      // $this->db2->where ("DATE_FORMAT('T5_STT_AGAMA.BLN', '%Y-%m-%d') = '2015-05-01'");
   //      // T5_STT_AGAMA.BLN= TO_DATE('2015-05-01', 'yyyy-mm-dd')

   //      $this->db2->where('DATET5_STT_AGAMA.BLN', "TO_DATE('2015-05-01', 'yyyy-mm-dd')");
       
        $data = $query->result();
        //var_dump($data);
         $this->response($data);
                 
        
                
       
    }

    
public function input_agama_get()
    {
        header('Content-Type: application/json');
        $kel = $this->get('kel');

       
        
        $this->db2->select("
            T5_STT_AGAMA.BLN,
			T5_STT_AGAMA.ISLAM,
			T5_STT_AGAMA.KRISTEN,
			T5_STT_AGAMA.KATHOLIK,
			T5_STT_AGAMA.HINDU,
			T5_STT_AGAMA.BUDHA,
			T5_STT_AGAMA.KONGHUCU,
			T5_STT_AGAMA.KEPERCAYAAN,
			T5_STT_AGAMA.NO_PROP,
			T5_STT_AGAMA.NO_KAB,
			T5_STT_AGAMA.NO_KEC,
			T5_STT_AGAMA.NO_KEL
			
        ");

        $this->db2->FROM ('T5_STT_AGAMA');
        $this->db2->where('T5_STT_AGAMA.NO_KEL', $kel);
        //$this->db2->where (DATE_FORMAT('T5_STT_AGAMA.BLN', '%Y-%m-%d') = '2015-05-01';
        //$this->db2->where('T5_STT_AGAMA.BLN', "TO_DATE('2015-05-01', 'yyyy-mm-dd')");
       
        $data = $this->db2->get()->result();
        //var_dump($data);
        $this->response($data);
                 
        
                
       
    }
}

/* End of file covid19.php */
/* Location: .//C/xampp/htdocs/app_topi/controllers/api/covid19.php */