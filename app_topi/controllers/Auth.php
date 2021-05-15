<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

    }

    public function index() {
        return redirect('auth/login','refresh');
    }

    public function login()
    {
        $this->load->helper('Form');
        if ($this->aauth->is_loggedin())
            redirect('/');

        if($this->input->post()){
            $email = $this->input->post('username');
            $password = $this->input->post('password');
            // var_dump($email);exit;
            if($this->input->post('remember') == 'TRUE'){
                $remember = TRUE;
            }else{
                $remember = FALSE;
            }
            if($this->aauth->login($email, $password, $remember)){
                redirect('/');
            }else{
                $this->load->view('auth/login');
            }
        }else{
            $this->load->view('auth/login');
        }

    }

    public function logout() {

        $this->aauth->logout();

        redirect('auth/login','refresh');
    }


    

}

/* End of file Auth.php */
/* Location: .//F/WAMPP/htdocs/oop/opensource/app_monev/controllers/Auth.php */