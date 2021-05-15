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
class Pengaduan extends REST_Controller {

    protected $db2;

    

    function __construct()
    {
        parent::__construct();
        $this->db2 = $this->load->database('api', TRUE);

    }

    public function cek_get()
    {
        $nik = $this->input->get('nik');
        // $no_kk = $this->input->get('no_kk');

        // if (empty($nik) || empty($no_kk)) {
        if (empty($nik)) {
            $this->response(array(
                "status"=>"Error",
                "message"=>"Anda tidak memasukkan nik, format = path?nik={nik}",
                ), 412);

        }

        
        $dt = $this->db2->query("SELECT
            SIAKOFF.API_KELUARGA_IMPORT.NIK,
            SIAKOFF.API_KELUARGA_IMPORT.NO_KK as NOMOR_KARTU_KELUARGA,
            SIAKOFF.API_KELUARGA_IMPORT.NAMA_LENGKAP,
            SIAKOFF.API_KELUARGA_IMPORT.JENIS_KLMIN as JENIS_KELAMIN,
            SIAKOFF.API_KELUARGA_IMPORT.GOLONGAN_DARAH,
            SIAKOFF.API_KELUARGA_IMPORT.ALAMAT,
            SIAKOFF.API_KELUARGA_IMPORT.NO_RT,
            SIAKOFF.API_KELUARGA_IMPORT.NO_RW,
            SIAKOFF.API_KELUARGA_IMPORT.KELURAHAN,
            SIAKOFF.API_KELUARGA_IMPORT.KECAMATAN,
            SIAKOFF.API_KELUARGA_IMPORT.STATUS_KAWIN as STATUS_PERNIKAHAN,
            SIAKOFF.API_KELUARGA_IMPORT.AGAMA,
            SIAKOFF.API_KELUARGA_IMPORT.PENDIDIKAN_AKHIR,
            SIAKOFF.API_KELUARGA_IMPORT.JENIS_PEKERJAAN
            FROM
            SIAKOFF.API_KELUARGA_IMPORT WHERE NIK='$nik'");

        $hs = $dt->row_array();

        if (count($hs) > 0) {
            $this->set_response(array('status'=>'OK', 'data'=>$hs));
        }        

        else {
            $this->set_response(array("status"=>"Error", "message"=> 'data tidak ada dalam database'));
        }
    }

    public function backupcek_get()
    {
        $nik = $this->input->get('nik');
        $no_kk = $this->input->get('no_kk');

        if (empty($nik) || empty($no_kk)) {
            $this->response(array(
                "status"=>"Error",
                "message"=>"Anda tidak memasukkan nik dan no kk, format = path?nik={nik}&no_kk{no_kk}",
                ), 412);

        }

        
        $dt = $this->db2->query("SELECT
            SIAKOFF.API_KELUARGA_IMPORT.NIK,
            SIAKOFF.API_KELUARGA_IMPORT.NO_KK as NOMOR_KARTU_KELUARGA,
            SIAKOFF.API_KELUARGA_IMPORT.NAMA_LENGKAP,
            SIAKOFF.API_KELUARGA_IMPORT.JENIS_KLMIN as JENIS_KELAMIN,
            SIAKOFF.API_KELUARGA_IMPORT.GOLONGAN_DARAH,
            SIAKOFF.API_KELUARGA_IMPORT.ALAMAT,
            SIAKOFF.API_KELUARGA_IMPORT.NO_RT,
            SIAKOFF.API_KELUARGA_IMPORT.NO_RW,
            SIAKOFF.API_KELUARGA_IMPORT.KELURAHAN,
            SIAKOFF.API_KELUARGA_IMPORT.KECAMATAN,
            SIAKOFF.API_KELUARGA_IMPORT.STATUS_KAWIN as STATUS_PERNIKAHAN,
            SIAKOFF.API_KELUARGA_IMPORT.AGAMA,
            SIAKOFF.API_KELUARGA_IMPORT.PENDIDIKAN_AKHIR,
            SIAKOFF.API_KELUARGA_IMPORT.JENIS_PEKERJAAN
            FROM
            SIAKOFF.API_KELUARGA_IMPORT WHERE NIK='$nik' AND NO_KK='$no_kk'");

        $hs = $dt->row_array();

        if (count($hs) > 0) {
            $this->set_response(array('status'=>'OK', 'data'=>$hs));
        }        

        else {
            $this->set_response(array("status"=>"Error", "message"=> 'data tidak ada dalam database'));
        }
    }



}
