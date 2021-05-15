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
class Sikda extends REST_Controller {

    protected $db2;
    
    function __construct()
    {
        parent::__construct();
        $this->db2 = $this->load->database('api', TRUE);

    }

    public function profil_get($profil='')
    {
        
        $dt = $this->db2->query("SELECT
            SIAKOFF.API_KELUARGA_IMPORT.NAMA_LENGKAP,
            SIAKOFF.API_KELUARGA_IMPORT.NIK,
            SIAKOFF.API_KELUARGA_IMPORT.TEMPAT_LAHIR,
            SIAKOFF.API_KELUARGA_IMPORT.TANGGAL_LAHIR,
            SIAKOFF.API_KELUARGA_IMPORT.JENIS_KLMIN as JENIS_KELAMIN,
            SIAKOFF.API_KELUARGA_IMPORT.GOLONGAN_DARAH,
            SIAKOFF.API_KELUARGA_IMPORT.ALAMAT,
            SIAKOFF.API_KELUARGA_IMPORT.NO_RT,
            SIAKOFF.API_KELUARGA_IMPORT.NO_RW,
            SIAKOFF.API_KELUARGA_IMPORT.KODE_POS,
            SIAKOFF.API_KELUARGA_IMPORT.TELP as NOMOR_TELEPON,
            SIAKOFF.API_KELUARGA_IMPORT.KELURAHAN,
            SIAKOFF.API_KELUARGA_IMPORT.KECAMATAN,
            SIAKOFF.API_KELUARGA_IMPORT.STATUS_KAWIN as STATUS_PERNIKAHAN,
            SIAKOFF.API_KELUARGA_IMPORT.STATUS_HUBUNGAN as STATUS_HUBUNGAN_KELUARGA,
            SIAKOFF.API_KELUARGA_IMPORT.NO_KK as NOMOR_KARTU_KELUARGA,
            SIAKOFF.API_KELUARGA_IMPORT.NAMA_KEPALA_KELUARGA,
            SIAKOFF.API_KELUARGA_IMPORT.NAMA_LENGKAP_AYAH,
            SIAKOFF.API_KELUARGA_IMPORT.NAMA_LENGKAP_IBU,
            SIAKOFF.API_KELUARGA_IMPORT.AGAMA,
            SIAKOFF.API_KELUARGA_IMPORT.PENDIDIKAN_AKHIR,
            SIAKOFF.API_KELUARGA_IMPORT.JENIS_PEKERJAAN
            FROM
            SIAKOFF.API_KELUARGA_IMPORT WHERE NIK='$profil'");

        $hs = $dt->row_array();

        if (count($hs) > 0) {
            $this->set_response(array('status'=>'OK', 'data'=>$hs));
        }        

        else {
            $this->set_response(array("Status"=>"Error"));
        }
    }



}
