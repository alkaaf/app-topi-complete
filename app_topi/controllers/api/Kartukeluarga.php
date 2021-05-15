<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Kartukeluarga extends REST_Controller {

	protected $db2;

	 protected $biodata = ["NIK","FLAG_STATUS"];

    protected $datakk = ["NO_KK", "COUNT_KK"];


	
	function __construct()
    {
        parent::__construct();
        $this->db2 = $this->load->database('api', TRUE);

    }

	public function index_get()
	{
		$kk = $this->get('kk');
		// $this->input->get('name')

		if (empty($kk)) {
            $this->response(array(
                "status"=>"Error",
                "message"=>"Anda tidak memasukkan Nomor KK, format = path?nik={nik}",
                ), 412);
        }

        $data = $this->data_keluarga($kk);
        $this->response($data);
        

	}

	public function biodata($nik)
    {
        if (strlen($nik) != 16) {
            return ['error' => 'Format NIK tidak Sesuai'];
        }
        $this->db2->select("
            BIODATA_WNI.NIK,
            BIODATA_WNI.NO_KTP,
            BIODATA_WNI.TMPT_SBL,
            BIODATA_WNI.NO_PASPOR,
            to_char(BIODATA_WNI.TGL_AKH_PASPOR,'yyyy-mm-dd') as TGL_AKH_PASPOR,
            BIODATA_WNI.NAMA_LGKP,
            BIODATA_WNI.JENIS_KLMIN,
            BIODATA_WNI.TMPT_LHR,
            to_char(BIODATA_WNI.TGL_LHR,'yyyy-mm-dd') as TGL_LHR,
            BIODATA_WNI.AKTA_LHR,
            BIODATA_WNI.NO_AKTA_LHR,
            BIODATA_WNI.GOL_DRH,
            BIODATA_WNI.AGAMA,
            BIODATA_WNI.STAT_KWN,
            BIODATA_WNI.AKTA_KWN,
            BIODATA_WNI.NO_AKTA_KWN,
            to_char(BIODATA_WNI.TGL_KWN,'yyyy-mm-dd') as TGL_KWN,
            BIODATA_WNI.AKTA_CRAI,
            BIODATA_WNI.NO_AKTA_CRAI,
            to_char(BIODATA_WNI.TGL_CRAI,'yyyy-mm-dd') as TGL_CRAI,
            BIODATA_WNI.STAT_HBKEL,
            BIODATA_WNI.KLAIN_FSK,
            BIODATA_WNI.PNYDNG_CCT,
            BIODATA_WNI.PDDK_AKH,
            BIODATA_WNI.JENIS_PKRJN,
            BIODATA_WNI.NIK_IBU,
            BIODATA_WNI.NAMA_LGKP_IBU,
            BIODATA_WNI.NIK_AYAH,
            BIODATA_WNI.NAMA_LGKP_AYAH,
            BIODATA_WNI.NAMA_KET_RT,
            BIODATA_WNI.NAMA_KET_RW,
            BIODATA_WNI.NAMA_PET_REG,
            BIODATA_WNI.NO_KK,
            BIODATA_WNI.JENIS_BNTU,
            BIODATA_WNI.NO_PROP,
            BIODATA_WNI.NO_KAB,
            BIODATA_WNI.NO_KEC,
            BIODATA_WNI.NO_KEL,
            BIODATA_WNI.ALS_NUMPANG,
            BIODATA_WNI.KET_AGAMA,
            BIODATA_WNI.KEBANGSAAN,
            BIODATA_WNI.GELAR,
            BIODATA_WNI.KET_PKRJN,
            BIODATA_WNI.GLR_AGAMA,
            BIODATA_WNI.GLR_AKADEMIS
        ");
        $this->db2->where('NIK', $nik);
        $data = $this->db2->get('BIODATA_WNI', 1)->row_array();

        if ($data == null) {
            return false;
        }

        // return $data;
        return (object) array_intersect_key($data, array_flip($this->biodata));
    }

    public function data_keluarga($nomor_kk)
    {
        if (strlen($nomor_kk) != 16) {
            return ['error' => 'Format Nomor KK tidak Sesuai'];
        }

    
        $this->db2->where('NO_KK', $nomor_kk);
        $data = $this->db2->get('DATA_KELUARGA', 1)->row_array();
        
        if ($data==null) {
            return ['error' => 'Format Nomor KK tidak Sesuai'];
        }

        $anggota_keluarga = $this->_anggota_keluarga($nomor_kk);

        $header = (object) array_intersect_key($data, array_flip($this->datakk));

        // replace data jumlah anggota keluarga
        // di database ternyata ngga sama dengan realita
        $header->COUNT_KK = count($anggota_keluarga);

        $header->detail = $anggota_keluarga;

        return $header;
    }

    protected function _anggota_keluarga($nomor_kk)
    {
        $this->db2->select("
            BIODATA_WNI.NIK,
            BIODATA_WNI.NO_KTP,
            BIODATA_WNI.TMPT_SBL,
            BIODATA_WNI.NO_PASPOR,
            to_char(BIODATA_WNI.TGL_AKH_PASPOR,'yyyy-mm-dd') as TGL_AKH_PASPOR,
            BIODATA_WNI.NAMA_LGKP,
            BIODATA_WNI.JENIS_KLMIN,
            BIODATA_WNI.TMPT_LHR,
            to_char(BIODATA_WNI.TGL_LHR,'yyyy-mm-dd') as TGL_LHR,
            BIODATA_WNI.AKTA_LHR,
            BIODATA_WNI.NO_AKTA_LHR,
            BIODATA_WNI.GOL_DRH,
            BIODATA_WNI.AGAMA,
            BIODATA_WNI.STAT_KWN,
            BIODATA_WNI.AKTA_KWN,
            BIODATA_WNI.NO_AKTA_KWN,
            to_char(BIODATA_WNI.TGL_KWN,'yyyy-mm-dd') as TGL_KWN,
            BIODATA_WNI.AKTA_CRAI,
            BIODATA_WNI.NO_AKTA_CRAI,
            to_char(BIODATA_WNI.TGL_CRAI,'yyyy-mm-dd') as TGL_CRAI,
            BIODATA_WNI.STAT_HBKEL,
            BIODATA_WNI.KLAIN_FSK,
            BIODATA_WNI.PNYDNG_CCT,
            BIODATA_WNI.PDDK_AKH,
            BIODATA_WNI.JENIS_PKRJN,
            BIODATA_WNI.NIK_IBU,
            BIODATA_WNI.NAMA_LGKP_IBU,
            BIODATA_WNI.NIK_AYAH,
            BIODATA_WNI.NAMA_LGKP_AYAH,
            BIODATA_WNI.NAMA_KET_RT,
            BIODATA_WNI.NAMA_KET_RW,
            BIODATA_WNI.NAMA_PET_REG,
            BIODATA_WNI.NO_KK,
            BIODATA_WNI.JENIS_BNTU,
            BIODATA_WNI.NO_PROP,
            BIODATA_WNI.NO_KAB,
            BIODATA_WNI.NO_KEC,
            BIODATA_WNI.NO_KEL,
            BIODATA_WNI.FLAG_STATUS,

        ");
        $this->db2->where('NO_KK', $nomor_kk);
        $this->db2->where('FLAG_STATUS', '0');
        $this->db2->order_by('STAT_HBKEL', 'asc');
        $this->db2->order_by('TGL_LHR', 'asc');
        $temp = $this->db2->get('BIODATA_WNI')->result_array();

        $data = array_map(function($x) {
            return (object) array_intersect_key($x, array_flip($this->biodata));
        }, $temp);

        // return $data;
        return $data;
    }

}

/* End of file Kartukeluarga.php */
/* Location: .//C/xampp/htdocs/app_topi/controllers/api/Kartukeluarga.php */