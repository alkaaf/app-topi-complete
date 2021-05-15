<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . '/libraries/REST_Controller.php';

class Agregat extends REST_Controller {
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

public function agg_umur_all_get()
    {
        header('Content-Type: application/json');
       
       	$query = $this->db3->query ("
       								SELECT 
										NAMA_KEL,
  case 
    when USIA = 0 then '0'
    when USIA = 1 then '1'
    when USIA = 2 then '2'
	when USIA = 3 then '3'
	when USIA = 4 then '4'
	when USIA = 5 then '5'
	when USIA = 6 then '6'
    when USIA = 7 then '7'
    when USIA = 8 then '8'
    when USIA = 9 then '9'
	when USIA = 10 then '10'
    when USIA = 11 then '11'
    when USIA = 12 then '12'
	when USIA = 13 then '13'
	when USIA = 14 then '14'
	when USIA = 15 then '15'
	when USIA = 16 then '16'
    when USIA = 17 then '17'
    when USIA = 18 then '18'
    when USIA = 19 then '19'
	when USIA = 20 then '20'
    when USIA = 21 then '21'
    when USIA = 22 then '22'
	when USIA = 23 then '23'
	when USIA = 24 then '24'
	when USIA = 25 then '25'
	when USIA = 26 then '26'
    when USIA = 27 then '27'
    when USIA = 28 then '28'
    when USIA = 29 then '29'
	when USIA = 30 then '30'
    when USIA = 31 then '31'
    when USIA = 32 then '32'
	when USIA = 33 then '33'
	when USIA = 34 then '34'
	when USIA = 35 then '35'
	when USIA = 36 then '36'
    when USIA = 37 then '37'
    when USIA = 38 then '38'
    when USIA = 39 then '39'
	when USIA = 40 then '40'
    when USIA = 41 then '41'
    when USIA = 42 then '42'
	when USIA = 43 then '43'
	when USIA = 44 then '44'
	when USIA = 45 then '45'
	when USIA = 46 then '46'
    when USIA = 47 then '47'
    when USIA = 48 then '48'
    when USIA = 49 then '49'
	when USIA = 50 then '50'
    when USIA = 51 then '51'
    when USIA = 52 then '52'
	when USIA = 53 then '53'
	when USIA = 54 then '54'
	when USIA = 55 then '55'
	when USIA = 56 then '56'
    when USIA = 57 then '57'
    when USIA = 58 then '58'
    when USIA = 59 then '59'
	when USIA = 60 then '60'
    when USIA = 61 then '61'
    when USIA = 62 then '62'
	when USIA = 63 then '63'
	when USIA = 64 then '64'
	when USIA = 65 then '65'
	when USIA = 66 then '66'
    when USIA = 67 then '67'
    when USIA = 68 then '68'
    when USIA = 69 then '69'
	when USIA = 70 then '70'
    when USIA = 71 then '71'
    when USIA = 72 then '72'
	when USIA = 73 then '73'
	when USIA = 74 then '74'
	when USIA = 75 then '75'
    else '75 Keatas'
  end as umur,
 SUM(IF(JENIS_KELAMIN = 'Laki-laki', 1,0)) as L,
 SUM(IF(JENIS_KELAMIN = 'Perempuan', 1,0)) as P,
 count(1) Jumlah

  
from tm_warga
group by NAMA_KEL, umur 
order by NAMA_KEL, cast(umur as unsigned)
										"); 
      
        $data = $query->result();
        //var_dump($data);
         $this->response($data);
    }    


public function agg_agama_jk_kel_get()
    {
        header('Content-Type: application/json');
       
       	$query = $this->db3->query ("
       								SELECT 
										NAMA_KEL,
  case 
    when AGAMA = 1 then 'ISLAM'
		when AGAMA = 2 then 'KRISTEN'
		when AGAMA = 3 then 'KATHOLIK'
		when AGAMA = 4 then 'HINDU'
		when AGAMA = 5 then 'BUDHA'
	  else 'KONGHUCHU'
  end as agama,
 SUM(IF(JENIS_KELAMIN = 'Laki-laki', 1,0)) as L,
 SUM(IF(JENIS_KELAMIN = 'Perempuan', 1,0)) as P,
 count(1) Jumlah

  
from tm_warga
group by 
tm_warga.NAMA_KEL,
tm_warga.AGAMA


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