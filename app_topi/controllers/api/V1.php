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
class V1 extends REST_Controller {

    protected $db2;

    function __construct()
    {
        parent::__construct();
        $this->db2 = $this->load->database('api', TRUE);

    }

    public function profil_get($profil='')
    {
        
        
        $dt = $this->db2->query("SELECT NAMA_LENGKAP,JENIS_KLMIN,TEMPAT_LAHIR,TANGGAL_LAHIR,ALAMAT,DUSUN,NO_RT,NO_RW,KELURAHAN,KECAMATAN FROM SIAKOFF.API_KELUARGA_IMPORT WHERE NIK=" . $profil);

        $hs = $dt->row_array();

        if (count($hs) > 0) {
            $this->set_response($hs);
        }        

        else {
            $this->set_response(array("Status"=>"Error, Data Tidak Ada"));
        }
    }



}
