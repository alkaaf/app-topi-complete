<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (! $this->aauth->is_loggedin()) 
           redirect('auth/login','refresh');
        $this->_user = $this->aauth->get_user();
    }

    public function response($data = NULL, $http_code = NULL)
    {
        // If the HTTP status is not NULL, then cast as an integer
        if ($http_code !== NULL)
        {
            // So as to be safe later on in the process
            $http_code = (int) $http_code;
        }

        // Set the output as NULL by default
        $output = NULL;

        if ($data === NULL && $http_code === NULL)
        {
            $http_code = 404;
        }

        // If data is not NULL and a HTTP status code provided, then continue
        elseif ($data !== NULL)
        {
          $output = json_encode($data);  
        }

        // If not greater than zero, then set the HTTP status code as 200 by default
        // Though perhaps 500 should be set instead, for the developer not passing a
        // correct HTTP status code
        $http_code > 0 || $http_code = 200;

        set_status_header($http_code);

        exit($output);
    }
    

}

/* End of file MY_Controller.php */
/* Location: .//F/WAMPP/htdocs/oop/opensource/app_monev/core/MY_Controller.php */