<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        if (! $this->aauth->is_admin()) {
            redirect('account','refresh');
        }

        $this->load->model('All');
        
    }

    public function tabel()
    {
        $this->load->helper('xcrud');
        
        $xcrud = xcrud_get_instance();
        $xcrud->table('tabel_rekap');
        $xcrud->label('ket', 'Keterangan Tabel');
        $xcrud->label('nama', 'Nama Tabel');
        $xcrud->fields('nama,ket', false, 'Informasi Tabel');
        $xcrud->unset_add();
        $xcrud->unset_edit();
        $xcrud->unset_remove();
        $xcrud->unset_view();
        $xcrud->unset_csv();
        $xcrud->unset_limitlist();
        $xcrud->unset_numbers();
        $xcrud->unset_pagination();
        $xcrud->unset_print();
        $xcrud->unset_search();
        $xcrud->unset_title();
        $xcrud->unset_sortable();
        // $xcrud->button(base_url('admin/rekap/{nama}'),'Preview', 'fa fa-eye','bg-olive',array());
        $data['content'] = $xcrud->render();
        $data['judul'] = 'Manajemen Tabel Rekap';
        $this->load->view('common/header');
        $this->load->view('box', $data);
        $this->load->view('common/footer');
    }

    public function rekap($tabel = null)
    {
        echo($tabel);
    }

    public function individu()
    {
        $this->load->helper('xcrud');
        
        $xcrud = xcrud_get_instance();
        $xcrud->table('individu');
        $xcrud->label('ket', 'Keterangan Tabel');
        $xcrud->label('nama', 'Nama Tabel');
        $xcrud->fields('nama,ket', false, 'Informasi Tabel');
        $xcrud->fk_relation('Daftar Kolom Tersedia','id','tabel_individu','individu_id','kolom_id','kolom','id',array('keterangan'));
        $xcrud->fields('Daftar Kolom Tersedia', false, 'Daftar Kolom Tersedia');
        $data['content'] = $xcrud->render();
        $data['judul'] = 'Manajemen Tabel Individu (by Name By Address)';
        $this->load->view('common/header');
        $this->load->view('box', $data);
        $this->load->view('common/footer');
    }

    public function kartukeluarga()
    {
        $this->load->helper('xcrud');
        
        $xcrud = xcrud_get_instance();
        $xcrud->table('tabel_kartu_keluarga');
      
        $xcrud->fields('nama,ket', false, 'Informasi Tabel');
        $xcrud->unset_add();
        $xcrud->unset_edit();
        $xcrud->unset_remove();
        $xcrud->unset_view();
        $xcrud->unset_csv();
        $xcrud->unset_limitlist();
        $xcrud->unset_numbers();
        $xcrud->unset_pagination();
        $xcrud->unset_print();
        $xcrud->unset_search();
        $xcrud->unset_title();
        $xcrud->unset_sortable();
        $data['content'] = $xcrud->render();
        $data['judul'] = 'Manajemen Tabel Kartu Keluarga';
        $this->load->view('common/header');
        $this->load->view('box', $data);
        $this->load->view('common/footer');
    }

    public function Api()
    {
        $this->load->helper('xcrud');
        
        $xcrud = xcrud_get_instance();
        $xcrud->table('api');
        $xcrud->label('ket', 'Keterangan Api');
        $xcrud->label('field', 'Nama Api');
        $xcrud->fields('field,ket', false, 'Informasi Api');

        $data['content'] = $xcrud->render();
        $data['judul'] = 'Manajemen Api';
        $this->load->view('common/header');
        $this->load->view('box', $data);
        $this->load->view('common/footer');
    }

    public function kolom()
    {
        $this->load->helper('xcrud');
        
        $xcrud = xcrud_get_instance();
        $xcrud->table('kolom');
        $xcrud->label('keterangan', 'Keterangan Kolom');
        $xcrud->label('kolom', 'Nama Kolom');
        $xcrud->label('tabel', 'Source Tabel');
        $xcrud->unset_add();
        $xcrud->unset_edit();
        $xcrud->unset_remove();
        $xcrud->unset_view();
        $xcrud->field_tooltip('tabel', 'Isi dengan : Source Tabel');
        $xcrud->fields('keterangan,kolom,tabel', false, 'Informasi Kolom');

        $data['content'] = $xcrud->render();
        $data['judul'] = 'Manajemen Kolom';
        $this->load->view('common/header');
        $this->load->view('box', $data);
        $this->load->view('common/footer');
    }

    public function logs()
    {
        $this->load->helper('xcrud');
        
        $xcrud = xcrud_get_instance();
        $xcrud->table('vv_log_download');
        

        $data['content'] = $xcrud->render();
        $data['judul'] = 'Log Aktivitas';
        $this->load->view('common/header');
        $this->load->view('box', $data);
        $this->load->view('common/footer');
    }

    public function logs_activity_json(){

       $list = $this->All->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $params = unserialize($field->params);
                $dataShown = "";
                if(isset($params['nik'])){
                    $dataShown += "NIK ".$params['nik'];
                }
                if(isset($params['kk'])){
                    $dataShown += "KK ".$params['kk'];
                }
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $field->nama;
                $row[] = $field->username;
                $row[] = $field->uri;
                $row[] = $dataShown;
                $row[] = $field->api_key;
                $row[] = $field->ip_address;
                $row[] = $field->terakhir_akses;

                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->All->count_all(),
                "recordsFiltered" => $this->All->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);

    }

    public function logs_activity()
    {
            
            // get users
            $qUser = $this->db->select('*')->from('aauth_users')->get();
            $user = $qUser->result();

            $data = [
                "user" => $user
            ];
            $this->load->view('common/header');
            $this->load->view('view_logs_activity', $data);
            $this->load->view('common/footer');    
    }


    public function user()
    {
        $this->load->helper('xcrud');
        
        $xcrud = xcrud_get_instance();
        $xcrud->table('aauth_users');
        $xcrud->table_name('My custom table!');
        $xcrud->columns('username,email');
        $xcrud->fk_relation('Group Akses','id','aauth_user_to_group','user_id','group_id','aauth_groups','id',array('name'));
        $xcrud->fk_relation('User Rekap','id','user_rekap','user','rekap','tabel_rekap','id',array('ket'));
        $xcrud->fk_relation('User Individu','id','user_individu','user','individu','individu','id',array('ket'));
        $xcrud->fields('username,email,banned', false, 'Informasi Login');
        $xcrud->readonly('username,email', 'edit');
        $xcrud->label('username', 'Username');
        $xcrud->label('email', 'e-mail');
        $xcrud->fields('Group Akses', false, 'Informasi Group');
        $xcrud->fields('User Rekap', false, 'Akses Tabel Rekap');
        $xcrud->fields('User Individu', false, 'Akses Tabel Individu');
        $xcrud->button(base_url('admin/keys/{id}'),'Api Key', 'fa fa-key','bg-olive',array());
        
        $xcrud->after_insert('after_insert_user');

        $data['content'] = $xcrud->render();
        $data['judul'] = 'Manajemen User';
        $this->load->view('common/header');
        $this->load->view('box', $data);
        $this->load->view('common/footer');
    }

    public function group()
    {
        $this->load->helper('xcrud');
        
        $xcrud = xcrud_get_instance();
        $xcrud->table('aauth_groups');
        $xcrud->columns('name,definition');
        $xcrud->label('name','Nama Group');
        $xcrud->label('definition','Keterangan');
        $xcrud->fields('name,definition', false, 'Informasi Group');
        
        

        $data['content'] = $xcrud->render();
        $data['judul'] = 'Manajemen Group';
        $this->load->view('common/header');
        $this->load->view('box', $data);
        $this->load->view('common/footer');
    }
    
    public function keys($id = NULL)
    {
        if ($id === NULL || $id <1) {
            redirect('admin/user','refresh');
        }
        
        $this->load->helper('xcrud');
                
        $xcrud = xcrud_get_instance();
        $xcrud->table('keys');
        $xcrud->where('user_id =', $id);
        $xcrud->pass_var('user_id', $id);
        $xcrud->fields('level,ignore_limits,is_private_key,ip_addresses', false, 'Informasi Api Keys', 'create');
        $xcrud->fields('key,level,ignore_limits,is_private_key,ip_addresses', false, 'Informasi Api Keys', 'edit');
        $xcrud->readonly('key', 'edit');
        // $xcrud->unset_view();

        $xcrud->before_insert('generate_api'); 
        // $xcrud->set_lang('save_return','Update');
        $data['content'] = $xcrud->render();

        $data['judul'] = 'Akses API Key';
        $data['linkback'] = '<div class="pull-right header"><a href="'.base_url('admin/user').'" class="btn bg-orange btn-flat">Kembali Ke Daftar User</a></div>';
        $this->load->view('common/header');
        $this->load->view('box', $data);
        $this->load->view('common/footer');
    }

    public function test()
    {
        $this->load->helper('xcrud');
        
        $xcrud = xcrud_get_instance();
        $xcrud->table('aauth_users');
        $xcrud->label('username', 'Username');
        $xcrud->label('email', 'Email');
        $xcrud->fields('username,email', false, 'Informasi Tabel');
        $xcrud->fk_relation('Daftar Kolom Tersedia','id','tabel_individu','individu_id','kolom_id','kolom','id',array('keterangan'));
        $xcrud->fields('Daftar Kolom Tersedia', false, 'Daftar Kolom Tersedia');

        $data['content'] = $xcrud->render();
        $data['judul'] = 'Manajemen Tabel Individu (by Name By Address)';

        $this->load->view('common/header');
        $this->load->view('box', $data);
        $this->load->view('common/footer');
    }

    public function add_api($id = NULL)
    {
        if ($id === NULL || $id <1) {
            redirect('admin/user','refresh');
        }
        
        $this->load->helper('xcrud');
                
        $xcrud = xcrud_get_instance();
        $xcrud->table('keys');
        $xcrud->where('user_id =', $id);
        $xcrud->pass_var('user_id', $id);
        $xcrud->fields('level,ignore_limits,is_private_key,ip_addresses', false, 'Informasi Api Keys', 'create');
        $xcrud->fields('key,level,ignore_limits,is_private_key,ip_addresses', false, 'Informasi Api Keys', 'edit');
        $xcrud->readonly('key', 'edit');
        // $xcrud->unset_view();

        $xcrud->before_insert('generate_api'); 
        // $xcrud->set_lang('save_return','Update');
        $data['content'] = $xcrud->render();

        $data['judul'] = 'Akses API Key';
        $data['linkback'] = '<div class="pull-right header"><a href="'.base_url('admin/te').'" class="btn bg-orange btn-flat">Kembali Ke Daftar User API</a></div>';
        $this->load->view('common/header');
        $this->load->view('box', $data);
        $this->load->view('common/footer');
    }

}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */