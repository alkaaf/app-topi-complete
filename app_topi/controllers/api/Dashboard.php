<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Dashboard extends REST_Controller {
    function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database('siakmojo', TRUE);
    }

    function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public function dt_penduduk_all_get()
    {
        header('Content-Type: application/json');
        $tanggal = $this->get('tanggal');
        if(!$this->validateDate($tanggal)){
            $result = array('status' => false,'tanggal' => $tanggal, 'message'=> 'tanggal tidak valid');
            $this->response($result);
        }

        $query = $this->db->query("SELECT * FROM dash_penduduk WHERE DATE(TGL_INSERT)='".$tanggal."'");

        $data = $query->result();
        if ($data == null) {
            $result = array('status' => false,'tanggal' => $tanggal, 'message'=> 'data tidak ditemukan');
            $this->response($result);
        }

        $result = array('status' => true,'message'=> '-','tanggal' => $tanggal, 'data'=>$data);
        $this->response($result);
    }

    public function dt_penduduk_kecamatan_get()
    {
        header('Content-Type: application/json');
        $tanggal = $this->get('tanggal');
        if(!$this->validateDate($tanggal)){
            $result = array('status' => false,'tanggal' => $tanggal, 'message'=> 'tanggal tidak valid');
            $this->response($result);
        }

        $query = $this->db->query("SELECT 
                                        dash_penduduk.NO_KEC,
                                        dash_penduduk.NAMA_KEC,
                                        SUM(dash_penduduk.KK) as KK,
                                        SUM(dash_penduduk.JK_L) as JK_L,
                                        SUM(dash_penduduk.JK_P) as JK_P,
                                        SUM(dash_penduduk.JK) as JK,
                                        SUM(dash_penduduk.KTP_L) as KTP_L,
                                        SUM(dash_penduduk.KTP_P) as KTP_P,
                                        SUM(dash_penduduk.KTP) as KTP,
                                        SUM(dash_penduduk.KIA_L) as KIA_L,
                                        SUM(dash_penduduk.KIA_P) as KIA_P,
                                        SUM(dash_penduduk.KIA) as KIA,
                                        SUM(dash_penduduk.AKTA_LAHIR) as AKTA_LAHIR,
                                        SUM(dash_penduduk.AKTA_LAHIR_BLM) as AKTA_LAHIR_BLM,
                                        SUM(dash_penduduk.AKTA_LAHIR_0) as AKTA_LAHIR_0,
                                        SUM(dash_penduduk.AKTA_LAHIR_0_BLM) as AKTA_LAHIR_0_BLM,
                                        SUM(dash_penduduk.BLM_KAWIN) as BLM_KAWIN,
                                        SUM(dash_penduduk.KAWIN) as KAWIN,
                                        SUM(dash_penduduk.CERAI_HIDUP) as CERAI_HIDUP,
                                        SUM(dash_penduduk.CERAI_MATI) as CERAI_MATI,
                                        SUM(dash_penduduk.ISLAM) as ISLAM,
                                        SUM(dash_penduduk.KRISTEN) as KRISTEN,
                                        SUM(dash_penduduk.KATHOLIK) as KATHOLIK,
                                        SUM(dash_penduduk.HINDU) as HINDU,
                                        SUM(dash_penduduk.BUDHA) as BUDHA,
                                        SUM(dash_penduduk.KONGHUCU) as KONGHUCU,
                                        SUM(dash_penduduk.KEPERCAYAAN) as KEPERCAYAAN,
                                        SUM(dash_penduduk.U018) as U018,
                                        SUM(dash_penduduk.U018_AKTA_LAHIR) as U018_AKTA_LAHIR,
                                        SUM(dash_penduduk.U018_AKTA_LAHIR_BLM) as U018_AKTA_LAHIR_BLM,
                                        round((SUM(dash_penduduk.U018_AKTA_LAHIR)/SUM(dash_penduduk.U018))*100, 2) as U018_PROSENTASE,
                                        SUM(dash_penduduk.PDDK_BLM) as PDDK_BLM,
                                        SUM(dash_penduduk.PDDK_BLM_SD) as PDDK_BLM_SD,
                                        SUM(dash_penduduk.PDDK_SD) as PDDK_SD,
                                        SUM(dash_penduduk.PDDK_SLTP) as PDDK_SLTP,
                                        SUM(dash_penduduk.PDDK_SLTA) as PDDK_SLTA,
                                        SUM(dash_penduduk.PDDK_DI_DII) as PDDK_DI_DII,
                                        SUM(dash_penduduk.PDDK_DIII) as PDDK_DIII,
                                        SUM(dash_penduduk.PDDK_DIV_SI) as PDDK_DIV_SI,
                                        SUM(dash_penduduk.PDDK_SII) as PDDK_SII,
                                        SUM(dash_penduduk.PDDK_SIII) as PDDK_SIII,
                                        SUM(dash_penduduk.PDDK) as PDDK,
                                        SUM(dash_penduduk.PKRJ_BLM) as PKRJ_BLM,
                                        SUM(dash_penduduk.PKRJ_TANI_TERNAK_NELAYAN) as PKRJ_TANI_TERNAK_NELAYAN,
                                        SUM(dash_penduduk.PKRJ_PERDAGANGAN) as PKRJ_PERDAGANGAN,
                                        SUM(dash_penduduk.PKRJ_INDUSTRI) as PKRJ_INDUSTRI,
                                        SUM(dash_penduduk.PKRJ_JS_KEMASY) as PKRJ_JS_KEMASY,
                                        SUM(dash_penduduk.PKRJ_KONSTRUKSI) as PKRJ_KONSTRUKSI,
                                        SUM(dash_penduduk.PKRJ_PEMERINTAH) as PKRJ_PEMERINTAH,
                                        SUM(dash_penduduk.PKRJ_PELAJAR_MHS) as PKRJ_PELAJAR_MHS,
                                        SUM(dash_penduduk.PKRJ_SWASTA) as PKRJ_SWASTA,
                                        SUM(dash_penduduk.PKRJ_WIRASWASTA) as PKRJ_WIRASWASTA,
                                        SUM(dash_penduduk.PKRJ_LAINNYA) as PKRJ_LAINNYA,
                                        SUM(dash_penduduk.PKRJ) as PKRJ
                                    FROM 
                                        dash_penduduk
                                    WHERE
                                        DATE(TGL_INSERT)='".$tanggal."'
                                    GROUP BY
                                        dash_penduduk.NO_KEC,
                                        dash_penduduk.NAMA_KEC");

        $data = $query->result();
        if ($data == null) {
            $result = array('status' => false,'tanggal' => $tanggal, 'message'=> 'data tidak ditemukan');
            $this->response($result);
        }

        $result = array('status' => true,'message'=> '-','tanggal' => $tanggal, 'data'=>$data);
        $this->response($result);
    }

    public function dt_penduduk_kelurahan_get()
    {
        header('Content-Type: application/json');
        $tanggal = $this->get('tanggal');
        if(!$this->validateDate($tanggal)){
            $result = array('status' => false,'tanggal' => $tanggal, 'message'=> 'tanggal tidak valid');
            $this->response($result);
        }

        $kecamatan = $this->get('kecamatan');
        $filterKec = (empty($kecamatan) ? "" : "NO_KEC='".$kecamatan."' AND");

        $query = $this->db->query("SELECT 
                                        dash_penduduk.NO_KEC,
                                        dash_penduduk.NAMA_KEC,
                                        dash_penduduk.NAMA_KEL,
                                        dash_penduduk.NAMA_KEL,
                                        SUM(dash_penduduk.KK) as KK,
                                        SUM(dash_penduduk.JK_L) as JK_L,
                                        SUM(dash_penduduk.JK_P) as JK_P,
                                        SUM(dash_penduduk.JK) as JK,
                                        SUM(dash_penduduk.KTP_L) as KTP_L,
                                        SUM(dash_penduduk.KTP_P) as KTP_P,
                                        SUM(dash_penduduk.KTP) as KTP,
                                        SUM(dash_penduduk.KIA_L) as KIA_L,
                                        SUM(dash_penduduk.KIA_P) as KIA_P,
                                        SUM(dash_penduduk.KIA) as KIA,
                                        SUM(dash_penduduk.AKTA_LAHIR) as AKTA_LAHIR,
                                        SUM(dash_penduduk.AKTA_LAHIR_BLM) as AKTA_LAHIR_BLM,
                                        SUM(dash_penduduk.AKTA_LAHIR_0) as AKTA_LAHIR_0,
                                        SUM(dash_penduduk.AKTA_LAHIR_0_BLM) as AKTA_LAHIR_0_BLM,
                                        SUM(dash_penduduk.BLM_KAWIN) as BLM_KAWIN,
                                        SUM(dash_penduduk.KAWIN) as KAWIN,
                                        SUM(dash_penduduk.CERAI_HIDUP) as CERAI_HIDUP,
                                        SUM(dash_penduduk.CERAI_MATI) as CERAI_MATI,
                                        SUM(dash_penduduk.ISLAM) as ISLAM,
                                        SUM(dash_penduduk.KRISTEN) as KRISTEN,
                                        SUM(dash_penduduk.KATHOLIK) as KATHOLIK,
                                        SUM(dash_penduduk.HINDU) as HINDU,
                                        SUM(dash_penduduk.BUDHA) as BUDHA,
                                        SUM(dash_penduduk.KONGHUCU) as KONGHUCU,
                                        SUM(dash_penduduk.KEPERCAYAAN) as KEPERCAYAAN,
                                        SUM(dash_penduduk.U018) as U018,
                                        SUM(dash_penduduk.U018_AKTA_LAHIR) as U018_AKTA_LAHIR,
                                        SUM(dash_penduduk.U018_AKTA_LAHIR_BLM) as U018_AKTA_LAHIR_BLM,
                                        round((SUM(dash_penduduk.U018_AKTA_LAHIR)/SUM(dash_penduduk.U018))*100, 2) as U018_PROSENTASE,
                                        SUM(dash_penduduk.PDDK_BLM) as PDDK_BLM,
                                        SUM(dash_penduduk.PDDK_BLM_SD) as PDDK_BLM_SD,
                                        SUM(dash_penduduk.PDDK_SD) as PDDK_SD,
                                        SUM(dash_penduduk.PDDK_SLTP) as PDDK_SLTP,
                                        SUM(dash_penduduk.PDDK_SLTA) as PDDK_SLTA,
                                        SUM(dash_penduduk.PDDK_DI_DII) as PDDK_DI_DII,
                                        SUM(dash_penduduk.PDDK_DIII) as PDDK_DIII,
                                        SUM(dash_penduduk.PDDK_DIV_SI) as PDDK_DIV_SI,
                                        SUM(dash_penduduk.PDDK_SII) as PDDK_SII,
                                        SUM(dash_penduduk.PDDK_SIII) as PDDK_SIII,
                                        SUM(dash_penduduk.PDDK) as PDDK,
                                        SUM(dash_penduduk.PKRJ_BLM) as PKRJ_BLM,
                                        SUM(dash_penduduk.PKRJ_TANI_TERNAK_NELAYAN) as PKRJ_TANI_TERNAK_NELAYAN,
                                        SUM(dash_penduduk.PKRJ_PERDAGANGAN) as PKRJ_PERDAGANGAN,
                                        SUM(dash_penduduk.PKRJ_INDUSTRI) as PKRJ_INDUSTRI,
                                        SUM(dash_penduduk.PKRJ_JS_KEMASY) as PKRJ_JS_KEMASY,
                                        SUM(dash_penduduk.PKRJ_KONSTRUKSI) as PKRJ_KONSTRUKSI,
                                        SUM(dash_penduduk.PKRJ_PEMERINTAH) as PKRJ_PEMERINTAH,
                                        SUM(dash_penduduk.PKRJ_PELAJAR_MHS) as PKRJ_PELAJAR_MHS,
                                        SUM(dash_penduduk.PKRJ_SWASTA) as PKRJ_SWASTA,
                                        SUM(dash_penduduk.PKRJ_WIRASWASTA) as PKRJ_WIRASWASTA,
                                        SUM(dash_penduduk.PKRJ_LAINNYA) as PKRJ_LAINNYA,
                                        SUM(dash_penduduk.PKRJ) as PKRJ
                                    FROM 
                                        dash_penduduk
                                    WHERE 
                                        ".$filterKec." 
                                        DATE(TGL_INSERT)='".$tanggal."'
                                    GROUP BY
                                        dash_penduduk.NO_KEC,
                                        dash_penduduk.NAMA_KEC,
                                        dash_penduduk.NO_KEL,
                                        dash_penduduk.NAMA_KEL");

        $data = $query->result();
        if ($data == null) {
            $result = array('status' => false,'tanggal' => $tanggal, 'message'=> 'data tidak ditemukan');
            $this->response($result);
        }

        $result = array('status' => true,'message'=> '-','tanggal' => $tanggal, 'data'=>$data);
        $this->response($result);
    }

    public function dt_penduduk_trend_get()
    {
        header('Content-Type: application/json');

        $kecamatan = $this->get('kecamatan');
        $filterKec = (empty($kecamatan) ? "" : "WHERE NO_KEC='".$kecamatan."'");

        $query = $this->db->query("SELECT * FROM
                                        (SELECT
                                            YEAR(dash_penduduk.TGL_INSERT) AS TAHUN,
                                            MONTH(dash_penduduk.TGL_INSERT) AS BULAN,
                                            SUM(dash_penduduk.KK) AS KK,
                                            SUM(dash_penduduk.JK_L) AS JK_L,
                                            SUM(dash_penduduk.JK_P) AS JK_P,
                                            SUM(dash_penduduk.JK) AS JK
                                        FROM
                                            dash_penduduk
                                            ".$filterKec."
                                        GROUP BY
                                            TAHUN, BULAN
                                        ORDER BY TAHUN DESC, BULAN DESC
                                        LIMIT 12) AS A
                                    ORDER BY 
                                        A.TAHUN ASC, A.BULAN ASC");

        $data = $query->result();
        if ($data == null) {
            $result = array('status' => false, 'message'=> 'data tidak ditemukan');
            $this->response($result);
        }

        $result = array('status' => true,'message'=> '-', 'data'=>$data);
        $this->response($result);
    }

    public function dt_penduduk_dash_get()
    {
        header('Content-Type: application/json');
        $bulan = $this->get('bulan');
        $tahun = $this->get('tahun');

        $kecamatan = $this->get('kecamatan');
        $filterKec = (empty($kecamatan) ? "" : "NO_KEC='".$kecamatan."' AND");

        $query = $this->db->query("SELECT 
                                        SUM(dash_penduduk.KK) as KK,
                                        SUM(dash_penduduk.JK_L) as JK_L,
                                        SUM(dash_penduduk.JK_P) as JK_P,
                                        SUM(dash_penduduk.JK) as JK,
                                        SUM(dash_penduduk.KTP_L) as KTP_L,
                                        SUM(dash_penduduk.KTP_P) as KTP_P,
                                        SUM(dash_penduduk.KTP) as KTP,
                                        SUM(dash_penduduk.KIA_L) as KIA_L,
                                        SUM(dash_penduduk.KIA_P) as KIA_P,
                                        SUM(dash_penduduk.KIA) as KIA,
                                        SUM(dash_penduduk.AKTA_LAHIR) as AKTA_LAHIR,
                                        SUM(dash_penduduk.AKTA_LAHIR_BLM) as AKTA_LAHIR_BLM,
                                        SUM(dash_penduduk.AKTA_LAHIR_0) as AKTA_LAHIR_0,
                                        SUM(dash_penduduk.AKTA_LAHIR_0_BLM) as AKTA_LAHIR_0_BLM,
                                        SUM(dash_penduduk.BLM_KAWIN) as BLM_KAWIN,
                                        SUM(dash_penduduk.KAWIN) as KAWIN,
                                        SUM(dash_penduduk.CERAI_HIDUP) as CERAI_HIDUP,
                                        SUM(dash_penduduk.CERAI_MATI) as CERAI_MATI,
                                        SUM(dash_penduduk.ISLAM) as ISLAM,
                                        SUM(dash_penduduk.KRISTEN) as KRISTEN,
                                        SUM(dash_penduduk.KATHOLIK) as KATHOLIK,
                                        SUM(dash_penduduk.HINDU) as HINDU,
                                        SUM(dash_penduduk.BUDHA) as BUDHA,
                                        SUM(dash_penduduk.KONGHUCU) as KONGHUCU,
                                        SUM(dash_penduduk.KEPERCAYAAN) as KEPERCAYAAN,
                                        SUM(dash_penduduk.U018) as U018,
                                        SUM(dash_penduduk.U018_AKTA_LAHIR) as U018_AKTA_LAHIR,
                                        SUM(dash_penduduk.U018_AKTA_LAHIR_BLM) as U018_AKTA_LAHIR_BLM,
                                        round((SUM(dash_penduduk.U018_AKTA_LAHIR)/SUM(dash_penduduk.U018))*100, 2) as U018_PROSENTASE,
                                        SUM(dash_penduduk.PDDK_BLM) as PDDK_BLM,
                                        SUM(dash_penduduk.PDDK_BLM_SD) as PDDK_BLM_SD,
                                        SUM(dash_penduduk.PDDK_SD) as PDDK_SD,
                                        SUM(dash_penduduk.PDDK_SLTP) as PDDK_SLTP,
                                        SUM(dash_penduduk.PDDK_SLTA) as PDDK_SLTA,
                                        SUM(dash_penduduk.PDDK_DI_DII) as PDDK_DI_DII,
                                        SUM(dash_penduduk.PDDK_DIII) as PDDK_DIII,
                                        SUM(dash_penduduk.PDDK_DIV_SI) as PDDK_DIV_SI,
                                        SUM(dash_penduduk.PDDK_SII) as PDDK_SII,
                                        SUM(dash_penduduk.PDDK_SIII) as PDDK_SIII,
                                        SUM(dash_penduduk.PDDK) as PDDK,
                                        SUM(dash_penduduk.PKRJ_BLM) as PKRJ_BLM,
                                        SUM(dash_penduduk.PKRJ_TANI_TERNAK_NELAYAN) as PKRJ_TANI_TERNAK_NELAYAN,
                                        SUM(dash_penduduk.PKRJ_PERDAGANGAN) as PKRJ_PERDAGANGAN,
                                        SUM(dash_penduduk.PKRJ_INDUSTRI) as PKRJ_INDUSTRI,
                                        SUM(dash_penduduk.PKRJ_JS_KEMASY) as PKRJ_JS_KEMASY,
                                        SUM(dash_penduduk.PKRJ_KONSTRUKSI) as PKRJ_KONSTRUKSI,
                                        SUM(dash_penduduk.PKRJ_PEMERINTAH) as PKRJ_PEMERINTAH,
                                        SUM(dash_penduduk.PKRJ_PELAJAR_MHS) as PKRJ_PELAJAR_MHS,
                                        SUM(dash_penduduk.PKRJ_SWASTA) as PKRJ_SWASTA,
                                        SUM(dash_penduduk.PKRJ_WIRASWASTA) as PKRJ_WIRASWASTA,
                                        SUM(dash_penduduk.PKRJ_LAINNYA) as PKRJ_LAINNYA,
                                        SUM(dash_penduduk.PKRJ) as PKRJ
                                    FROM 
                                        dash_penduduk
                                    WHERE
                                        ".$filterKec." 
                                        MONTH(dash_penduduk.TGL_INSERT)= ".$bulan." AND
                                        YEAR(dash_penduduk.TGL_INSERT)= ".$tahun."
                                        ");

        $data = $query->result();
        if ($data == null) {
            $result = array('status' => false,'tanggal' => $bulan." ".$tahun, 'message'=> 'data tidak ditemukan');
            $this->response($result);
        }

        $result = array('status' => true,'message'=> '-','tanggal' => $bulan." ".$tahun, 'data'=>$data);
        $this->response($result);
    }

    public function dt_usia_all_get()
    {
        header('Content-Type: application/json');
        $bulan = $this->get('bulan');
        $tahun = $this->get('tahun');

        $kecamatan = $this->get('kecamatan');
        $filterKec = (empty($kecamatan) ? "" : "NO_KEC='".$kecamatan."' AND");

        $query = $this->db->query("SELECT
                                        dash_usia.USIA,
                                        SUM(dash_usia.JK_L) as JK_L,
                                        SUM(dash_usia.JK_P) as JK_P,
                                        SUM(dash_usia.JK) as JK,
                                        SUM(dash_usia.BLM_KAWIN) as BLM_KAWIN,
                                        SUM(dash_usia.KAWIN) as KAWIN,
                                        SUM(dash_usia.CERAI_HIDUP) as CERAI_HIDUP,
                                        SUM(dash_usia.CERAI_MATI) as CERAI_MATI,
                                        SUM(dash_usia.PDDK_BLM) as PDDK_BLM,
                                        SUM(dash_usia.PDDK_BLM_SD_SMA) as PDDK_BLM_SD_SMA,
                                        SUM(dash_usia.PDDK_DI_SIII) as PDDK_DI_SIII,
                                        SUM(dash_usia.PKRJ_BLM) as PKRJ_BLM,
                                        SUM(dash_usia.PKRJ_PEMERINTAH) as PKRJ_PEMERINTAH,
                                        SUM(dash_usia.PKRJ_NON_PEMERINTAH) as PKRJ_NON_PEMERINTAH
                                    FROM
                                        dash_usia
                                    WHERE 
                                        ".$filterKec." 
                                        MONTH(TGL_INSERT)= ".$bulan." AND
                                        YEAR(TGL_INSERT)= ".$tahun."
                                    GROUP BY
                                        dash_usia.USIA");

        $data = $query->result();
        if ($data == null) {
            $result = array('status' => false,'tanggal' => $bulan." ".$tahun, 'message'=> 'data tidak ditemukan');
            $this->response($result);
        }

        $result = array('status' => true,'message'=> '-','tanggal' => $bulan." ".$tahun, 'data'=>$data);
        $this->response($result);
    }

    public function dt_usia_get()
    {
        header('Content-Type: application/json');
        $bulan = $this->get('bulan');
        $tahun = $this->get('tahun');

        $kecamatan = $this->get('kecamatan');
        $filterKec = (empty($kecamatan) ? "" : "WHERE NO_KEC='".$kecamatan."'");

        $query = $this->db->query("SELECT
                                        u.KELOMPOK,
                                        SUM(u.JK_L) AS JK_L,
                                        SUM(u.JK_P) AS JK_P,
                                        SUM(u.JK) AS JK,
                                        SUM(u.BLM_KAWIN) AS BLM_KAWIN,
                                        SUM(u.KAWIN) AS KAWIN,
                                        SUM(u.CERAI_HIDUP) AS CERAI_HIDUP,
                                        SUM(u.CERAI_MATI) AS CERAI_MATI,
                                        SUM(u.PDDK_BLM) AS PDDK_BLM,
                                        SUM(u.PDDK_BLM_SD_SMA) AS PDDK_BLM_SD_SMA,
                                        SUM(u.PDDK_DI_SIII) AS PDDK_DI_SIII,
                                        SUM(u.PKRJ_BLM) AS PKRJ_BLM,
                                        SUM(u.PKRJ_PEMERINTAH) AS PKRJ_PEMERINTAH,
                                        SUM(u.PKRJ_NON_PEMERINTAH) AS PKRJ_NON_PEMERINTAH
                                    FROM
                                        (
                                            SELECT
                                                TGL_INSERT,     
                                                CASE
                                                    WHEN USIA BETWEEN 0 AND 4 THEN '0-4'
                                                    WHEN USIA BETWEEN 5 AND 9 THEN '5-9'
                                                    WHEN USIA BETWEEN 10 AND 14 THEN '10-14'
                                                    WHEN USIA BETWEEN 15 AND 19 THEN '15-19'
                                                    WHEN USIA BETWEEN 20 AND 24 THEN '20-24'
                                                    WHEN USIA BETWEEN 25 AND 29 THEN '25-29'
                                                    WHEN USIA BETWEEN 30 AND 34 THEN '30-34'
                                                    WHEN USIA BETWEEN 35 AND 39 THEN '35-39'
                                                    WHEN USIA BETWEEN 40 AND 44 THEN '40-44'
                                                    WHEN USIA BETWEEN 45 AND 49 THEN '45-49'
                                                    WHEN USIA BETWEEN 50 AND 54 THEN '50-54'
                                                    WHEN USIA BETWEEN 55 AND 59 THEN '55-59'
                                                    WHEN USIA BETWEEN 60 AND 64 THEN '60-64'
                                                    WHEN USIA BETWEEN 65 AND 69 THEN '65-69'
                                                    WHEN USIA BETWEEN 70 AND 74 THEN '70-74'
                                                ELSE '75 >'
                                                END AS KELOMPOK,
                                                JK_L, JK_P, JK, 
                                                BLM_KAWIN, KAWIN, CERAI_HIDUP, CERAI_MATI,
                                                PDDK_BLM, PDDK_BLM_SD_SMA, PDDK_DI_SIII,
                                                PKRJ_BLM, PKRJ_PEMERINTAH, PKRJ_NON_PEMERINTAH
                                                FROM dash_usia
                                                ".$filterKec." 
                                        ) AS u
                                    WHERE 
                                        MONTH(u.TGL_INSERT)= ".$bulan." AND
                                        YEAR(u.TGL_INSERT)= ".$tahun."
                                    GROUP BY
                                        u.KELOMPOK
                                    ORDER BY
                                        CAST(u.KELOMPOK AS UNSIGNED)");

        $data = $query->result();
        if ($data == null) {
            $result = array('status' => false,'tanggal' => $bulan." ".$tahun, 'message'=> 'data tidak ditemukan');
            $this->response($result);
        }

        $result = array('status' => true,'message'=> '-','tanggal' => $bulan." ".$tahun, 'data'=>$data);
        $this->response($result);
    }
}