<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller {

   public function __construct()
   {
        parent::__construct();
        $this->load->library('admin', array($this));
   }
    
   public function index()
   {
        $this->admin->isAuth();
        $viewData = array(
            "title" => "Welcome"
        );
        $this->load->library('parser');
        $this->admin->loadHeader("Admin");
        $this->parser->parse("admin/admin", $viewData);
        $this->admin->loadFooter();
   }
}