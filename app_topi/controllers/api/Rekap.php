<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Rekap extends REST_Controller {

    protected $db2;

    function __construct()
    {
        parent::__construct();
        $this->db2 = $this->load->database('api', TRUE);

    }

    public function kk_get()
    {
        $pekerjaan = $this->db->query("SELECT 'JUMLAH KELUARGA' AS JUMLAH_KELUARGA,
            SUM(IF(data_keluarga.KECAMATAN = 'KRANGGAN',1,0)) AS KRANGGAN,
            SUM(IF(data_keluarga.KECAMATAN = 'MAGERSARI',1,0)) AS MAGERSARI,
            SUM(IF(data_keluarga.KECAMATAN = 'PRAJURIT KULON',1,0)) AS PRAJURIT_KULON
            FROM
            data_keluarga
            WHERE data_keluarga.KECAMATAN IN ('KRANGGAN','MAGERSARI','PRAJURIT KULON')");

            $data = $pekerjaan->result_array();
            $this->set_response($data);
    
    }
    public function agama_get()
    {
        $pekerjaan = $this->db->query("SELECT data_keluarga.AGAMA AS AGAMA,
            SUM(IF(data_keluarga.KECAMATAN = 'KRANGGAN',1,0)) AS KRANGGAN,
            SUM(IF(data_keluarga.KECAMATAN = 'MAGERSARI',1,0)) AS MAGERSARI,
            SUM(IF(data_keluarga.KECAMATAN = 'PRAJURIT KULON',1,0)) AS PRAJURIT_KULON
            FROM
            data_keluarga
            WHERE data_keluarga.KECAMATAN IN ('KRANGGAN','MAGERSARI','PRAJURIT KULON')
            GROUP BY data_keluarga.AGAMA");

            $data = $pekerjaan->result_array();
            $this->set_response($data);
    
    }

    public function sex_get()
    {
        $pekerjaan = $this->db->query("SELECT
                data_keluarga.JENIS_KLMIN AS JENIS_KELAMIN,
                SUM(IF(data_keluarga.KECAMATAN = 'KRANGGAN',1,0)) AS KRANGGAN,
                SUM(IF(data_keluarga.KECAMATAN = 'MAGERSARI',1,0)) AS MAGERSARI,
                SUM(IF(data_keluarga.KECAMATAN = 'PRAJURIT KULON',1,0)) AS PRAJURIT_KULON
                FROM
                data_keluarga
                WHERE data_keluarga.KECAMATAN IN ('KRANGGAN','MAGERSARI','PRAJURIT KULON')
                GROUP BY data_keluarga.JENIS_KLMIN");

            $data = $pekerjaan->result_array();
            $this->set_response($data);
    
    }



    public function darah_get()
    {
        $pekerjaan = $this->db->query("SELECT
                data_keluarga.GOLONGAN_DARAH AS GOLONGAN_DARAH,
                SUM(IF(data_keluarga.KECAMATAN = 'KRANGGAN',1,0)) AS KRANGGAN,
                SUM(IF(data_keluarga.KECAMATAN = 'MAGERSARI',1,0)) AS MAGERSARI,
                SUM(IF(data_keluarga.KECAMATAN = 'PRAJURIT KULON',1,0)) AS PRAJURIT_KULON
                FROM
                data_keluarga
                WHERE data_keluarga.KECAMATAN IN ('KRANGGAN','MAGERSARI','PRAJURIT KULON') AND data_keluarga.GOLONGAN_DARAH !=''
                GROUP BY data_keluarga.GOLONGAN_DARAH");

            $data = $pekerjaan->result_array();
            $this->set_response($data);
    
    }


    public function pendidikan_get()
    {
        $pekerjaan = $this->db->query("SELECT
            data_keluarga.PENDIDIKAN_AKHIR AS PENDIDIKAN_AKHIR,
            SUM(IF(data_keluarga.KECAMATAN = 'KRANGGAN',1,0)) AS KRANGGAN,
            SUM(IF(data_keluarga.KECAMATAN = 'MAGERSARI',1,0)) AS MAGERSARI,
            SUM(IF(data_keluarga.KECAMATAN = 'PRAJURIT KULON',1,0)) AS PRAJURIT_KULON
            FROM
            data_keluarga
            WHERE data_keluarga.KECAMATAN IN ('KRANGGAN','MAGERSARI','PRAJURIT KULON')
            GROUP BY data_keluarga.PENDIDIKAN_AKHIR");

            $data = $pekerjaan->result_array();
            $this->set_response($data);
    
    }


    public function kawin_get()
    {
        $pekerjaan = $this->db->query("SELECT
            data_keluarga.STATUS_KAWIN AS STATUS_KAWIN,
            SUM(IF(data_keluarga.KECAMATAN = 'KRANGGAN',1,0)) AS KRANGGAN,
            SUM(IF(data_keluarga.KECAMATAN = 'MAGERSARI',1,0)) AS MAGERSARI,
            SUM(IF(data_keluarga.KECAMATAN = 'PRAJURIT KULON',1,0)) AS PRAJURIT_KULON
            FROM
            data_keluarga
            WHERE data_keluarga.KECAMATAN IN ('KRANGGAN','MAGERSARI','PRAJURIT KULON')
            GROUP BY data_keluarga.STATUS_KAWIN");

            $data = $pekerjaan->result_array();
            $this->set_response($data);
    
    }

    public function kerja_get()
    {
        $pekerjaan = $this->db->query("SELECT data_keluarga.JENIS_PEKERJAAN AS JENIS_PEKERJAAN,
            SUM(IF(data_keluarga.KECAMATAN = 'KRANGGAN',1,0)) AS KRANGGAN,
            SUM(IF(data_keluarga.KECAMATAN = 'MAGERSARI',1,0)) AS MAGERSARI,
            SUM(IF(data_keluarga.KECAMATAN = 'PRAJURIT KULON',1,0)) AS PRAJURIT_KULON
            FROM
            data_keluarga
            WHERE data_keluarga.KECAMATAN IN ('KRANGGAN','MAGERSARI','PRAJURIT KULON')
            GROUP BY data_keluarga.JENIS_PEKERJAAN");

            $data = $pekerjaan->result_array();
            $this->set_response($data);
    
    }

    public function nik_get($nik='')
    {
        
        
        $dt = $this->db2->query("SELECT NAMA_LENGKAP,KELURAHAN,STATUS_KAWIN FROM SIAKOFF.API_KELUARGA_IMPORT WHERE NIK=" . $nik);

        $hs = $dt->row_array();

        if (count($hs) > 0) {
            $this->set_response($hs);
        }        

        else {
            $this->set_response(array("Status"=>"Error, Data Tidak Ada"));
        }
    }



}
