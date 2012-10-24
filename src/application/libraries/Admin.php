<?php

class Admin {
    
    private $controller;

    function __construct($controller)
    {
        $this->controller = $controller;
    }
    
    public function isAuth()
    {
        if (!$this->controller[0]->session->userdata('status') == "A")
        {
            redirect("admin/login", "refresh");
            return false;
        }
        return true;
    }
    
    public function loadFooter()
    {
        $this->controller[0]->load->view("admin/footer.php");
    }
    
    public function loadHeader($title)
    {

        $this->controller[0]->load->library('parser');
        $data = array(
            "title" => $title." - ".$this->controller[0]->config->item("system_name"),
            "firstname" => ""
        );
        if (strlen($this->controller[0]->session->userdata('firstname')) > 0)
            $data["firstname"] = ", ".$this->controller[0]->session->userdata('firstname');
            
        $this->controller[0]->parser->parse("admin/header", $data);
    }
}