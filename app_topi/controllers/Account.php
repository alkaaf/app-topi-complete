<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        
    }
    public function index()
    {
        $this->load->helper('xcrud');
                
                $xcrud = xcrud_get_instance();
                $xcrud->table('aauth_users');
                $xcrud->where('username =', $this->_user->username);
                $xcrud->columns('username,email');
                
                $xcrud->fields('username,email,Api_Key', false, 'Informasi Login');
                $xcrud->fields('nama,telepon,nip,organisasi', false, 'Informasi Pribadi');
                $xcrud->fields('foto', false, 'Avatar');
                $xcrud->subselect('Api_Key','SELECT CONCAT(\'<ol><li>\',GROUP_CONCAT(`key` SEPARATOR \'</li><li>\'), \'</li></ol>\') FROM `keys` WHERE `user_id` = {id} GROUP BY `user_id`');
                $xcrud->change_type('foto', 'image', '', array('ratio' => 1, 'manual_crop' => true));
                $xcrud->readonly('username');
                $xcrud->unset_list()->unset_view();
                $xcrud->set_lang('save_return','Update');
                $data['content'] = $xcrud->render('edit', $this->_user->id);

                $data['judul'] = 'Update Profil';
                $this->load->view('common/header');
                $this->load->view('box', $data);
                $this->load->view('common/footer');
    }

    

}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */