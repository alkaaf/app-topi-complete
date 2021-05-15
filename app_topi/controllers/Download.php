<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Download extends MY_Controller {

    protected $db2;


    public function index()
    {
        
        $this->load->helper('xcrud');
        
        $xcrud = xcrud_get_instance();
        $xcrud->table('vv_user_rekap');
        $xcrud->where('user =', $this->_user->id);
        $xcrud->columns('ket');
        $xcrud->unset_add();
        $xcrud->unset_edit();
        $xcrud->unset_remove();
        $xcrud->unset_view();
        $xcrud->unset_csv();
        $xcrud->unset_print();
        $xcrud->unset_search();
        $xcrud->unset_title();
        $xcrud->unset_sortable();
        $xcrud->label('ket','Nama Tabel');
        $xcrud->button(base_url('download/rekap/{rekap}/xls'),'XLS', 'fa fa-file-excel-o','bg-olive',array());
        
        $xcrud2 = xcrud_get_instance();
        $xcrud2->table('vv_user_individu');
        $xcrud2->where('user =', $this->_user->id);
        $xcrud2->columns('nama');
        $xcrud2->unset_add();
        $xcrud2->unset_edit();
        $xcrud2->unset_remove();
        $xcrud2->unset_view();
        $xcrud2->unset_csv();
        $xcrud2->unset_print();
        $xcrud2->unset_search();
        $xcrud2->unset_title();
        $xcrud2->unset_sortable();
        $xcrud2->label('nama','Nama Tabel');
        $xcrud2->button(base_url('download/individu/{individu}/xls'),'XLS', 'fa fa-file-excel-o','bg-olive',array());

        $data['content2'] = $xcrud2->render();
        $data['judul2'] = 'Download Tabel Individu';
        $data['content'] = $xcrud->render();
        $data['judul'] = 'Download Tabel Rekap';
        $this->load->view('common/header');
        $this->load->view('box2', $data);
        $this->load->view('common/footer');
    }

    public function individu($id = null, $mode = null)
    {
        if (! is_numeric($id) || strlen($mode) < 1 ) 
            show_404();
        
        if (! $this->_allowed($id, 'individu'))
            show_404();

        $this->_show_individu($id, 'individu');
    }

    public function rekap($id = null, $mode = null)
    {
        if (! is_numeric($id) || strlen($mode) < 1 ) 
            show_404();
        
        if (! $this->_allowed($id, 'rekap'))
            show_404();

        $this->_show_rekap($id, 'rekap');
    }

    protected function _allowed($id, $table = null)
    {
        $tables = 'vv_user_' .$table;

        $this->db->where('user', $this->_user->id);
        $this->db->where($table, $id);
        $ok = $this->db->get($tables)->row();

        return count($ok) > 0 ? true : false;
    }

    protected function _show_individu($id, $tabel)
    {
        $this->load->helper('xcrud');
        
        $xcrud = xcrud_get_instance();
        $xcrud->table('vv_download_'.$tabel);
        $xcrud->where('id =', $id);
        $xcrud->columns('ket, tanggal');
        $xcrud->label('ket','Keterangan Tabel');
        $xcrud->label('tanggal','Tanggal Import');
        $xcrud->unset_add();
        $xcrud->unset_edit();
        $xcrud->unset_remove();
        $xcrud->unset_view();
        $xcrud->unset_csv();
        $xcrud->unset_print();
        $xcrud->unset_search();
        $xcrud->unset_title();
        $xcrud->unset_sortable();
        $xcrud->button(base_url('download/out/{file}/{id}/'.$tabel),'XLS', 'fa fa-file-excel-o','bg-olive',array());
        
        $data['content'] = $xcrud->render();
        $data['judul'] = 'Download Tabel '. strtoupper($tabel);
        $this->load->view('common/header');
        $this->load->view('box', $data);
        $this->load->view('common/footer');
    }

    protected function _show_rekap($id, $tabel)
    {
        $this->load->helper('xcrud');
        
        $xcrud = xcrud_get_instance();
        $xcrud->table('vv_download_'.$tabel);
        $xcrud->where('id =', $id);
        $xcrud->columns('ket, tanggal');
        $xcrud->label('ket','Keterangan Tabel');
        $xcrud->label('tanggal','Tanggal Import');
        $xcrud->unset_add();
        $xcrud->unset_edit();
        $xcrud->unset_remove();
        $xcrud->unset_view();
        $xcrud->unset_csv();
        $xcrud->unset_print();
        $xcrud->unset_search();
        $xcrud->unset_title();
        $xcrud->unset_sortable();
        $xcrud->button(base_url('download/out/{file}/{id}/'.$tabel),'XLS', 'fa fa-file-excel-o','bg-olive',array());
        
        $data['content'] = $xcrud->render();
        $data['judul'] = 'Download Tabel '. strtoupper($tabel);
        $this->load->view('common/header');
        $this->load->view('box', $data);
        $this->load->view('common/footer');
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

    public function cron_rekap()
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

    public function out($path='', $id, $table)
    {
        $pathfile = APPPATH . 'export_data' .DIRECTORY_SEPARATOR;
        if (! file_exists($pathfile . $path))
            show_404();
        if (! is_numeric($id) || strlen($path) < 1 || strlen($table) < 1 ) 
           show_404();
       
         if (! $this->_allowed($id, $table))
           show_404();


       $this->load->helper('download');

       //log data ????

       $to_insert = array(
                "user" => $this->_user->id,
                "aksi" => "download file " 
                        . $table . " dengan ID " . $id,
                "tanggal" => date("Y-m-d H:i:s"),
            );
       $this->db->insert('log_download', $to_insert);

       force_download($pathfile . $path, NULL);
       
    }

    public function cron_individu()
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

        $this->load->view('oke', array('total'=>count($hasil)));

        $this->output->enable_profiler(TRUE);

    }

}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */