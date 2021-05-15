<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

	public function index()
	{
		echo "string";
	}

	public function rekap()
	{
		$this->load->helper('inflector');
        $this->db2 = $this->load->database('api', TRUE);
        // grab dari data rekap
        
        $query_grab = $this->db->get('tabel_rekap');
        $grabs = $query_grab->result_array();

        foreach ($grabs as $grab) {
            
            $grab['nama'] = strtoupper('api_' . $grab['nama']);
            echo $grab['nama'] . '<br />';

            $q = $this->db2->get($grab['nama']);
            $r = $q->result_array(); 

            if (empty($r))
                continue;
            
            $hasil_export = $this->_xls($grab['nama'], $r);
            if($hasil_export == '')
                continue;

            //write to db download

            $to_insert = array(
                    "jenis" => "rekap",
                    "tabel" => $grab['id'],
                    "tanggal" => date("Y-m-d"),
                    "file" => $hasil_export
                );

            if($this->db->insert('download', $to_insert)) {
                echo "BERHASIL EKSPORT DATA" . $grab['nama'];
                echo "<br />";
            } else {
                echo "GAGAL EKSPORT DATA" . $grab['nama'];
                echo "<br />";
            }          
            
        }
	}

	protected function _xls($nama_file = 'sheet1', $data) {
        $this->load->library('XLSXWriter');
        $this->load->helper('inflector');

        $nama_file = str_replace('API_', '', $nama_file);

        $sheet = strtoupper(humanize($nama_file));
        

        $writer = new XLSXWriter();
        $header = array();
        foreach ($data as $value) {
            foreach ($value as $key => $v) {
                array_push($header,strtoupper(humanize($key)));
            }
            break;
        }
        
        $writer->writeSheetRow($sheet,$header);
        
        foreach ($data as $value) {
            $writer->writeSheetRow($sheet,$value);
        }
        $nama_file = date("Y_m_d") . '_'. $nama_file . '.xlsx';
        $writer->writeToFile(APPPATH . 'export_data' .DIRECTORY_SEPARATOR . $nama_file);

        return file_exists(APPPATH . 'export_data' .DIRECTORY_SEPARATOR . $nama_file) ? $nama_file : '';
    }

	protected function _xls_individu($nama_file = 'sheet1', $data) {
        $this->load->library('XLSXWriter');
        $this->load->helper('inflector');

        $nama_file = str_replace('API_', '', $nama_file);
        $nama_file = str_replace(' ', '', $nama_file);

        $sheet = strtoupper(humanize($nama_file));
        

        $writer = new XLSXWriter();
        $header = array();
        foreach ($data as $value) {
            foreach ($value as $key => $v) {
                array_push($header,strtoupper(humanize($key)));
            }
            break;
        }
        
        $writer->writeSheetRow($sheet,$header);
        
        foreach ($data as $value) {
            $writer->writeSheetRow($sheet,$value);
        }
        $nama_file = date("Y_m_d") . '_'. $nama_file . '.xlsx';
        $writer->writeToFile(APPPATH . 'export_data' .DIRECTORY_SEPARATOR . $nama_file);

        return file_exists(APPPATH . 'export_data' .DIRECTORY_SEPARATOR . $nama_file) ? $nama_file : '';
    }

    public function individu()
    {
        $this->db2 = $this->load->database('api', TRUE);
        $data = $this->db2->query("SELECT *
            FROM
            SIAKOFF.API_KELUARGA_IMPORT");
        
        $hasil = $data->result_array();
        foreach ($hasil as $k => $v) {
            $this->db->insert('a_data_master',$hasil[$k]);
            
            unset($hasil[$k]);
        }

        // $this->load->view('oke', array('total'=>count($hasil)));

    }


    public function indi()
    {

  	
    	$query = $this->db->get('vv_query_individu')->result();

    	echo "<pre>";
    	print_r($query);

    	foreach ($query as $v) {
    		
    		$this->db->where('TANGGAL_IMPORT', $v->tgl);
    		$data = $this->db->select($v->queri)->get('a_data_master')->result_array(); 

    		if (empty($data))
    		    continue;
    		
    		$hasil_export = $this->_xls_individu($v->nama, $data);
    		if($hasil_export == '')
    		    continue;

    		$to_insert = array(
    		        "jenis" => "individu",
    		        "tabel" => $v->id,
    		        "tanggal" => date("Y-m-d"),
    		        "file" => $hasil_export
    		    );

    		if($this->db->insert('download', $to_insert)) {
    		    echo "BERHASIL EKSPORT DATA" . $v->nama;
    		    echo "<br />";
    		} else {
    		    echo "GAGAL EKSPORT DATA" . $v->nama;
    		    echo "<br />";
    		} 

    	}


    }
	

	public function all()
	{
		$this->individu();
		$this->rekap();
		$this->indi();
	}
}
