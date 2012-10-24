<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->index();
    }
    
    public function index() 
    {
        $this->session->sess_destroy();
        $this->load->helper('url');
        redirect('admin/login', 'refresh');
    }
}