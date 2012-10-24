<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {


    public function index()
    {
        if ($this->session->userdata('status') == "A") {
            $this->load->helper('url');
            redirect('admin', 'refresh');
        }
        $message = "";
        if ($this->input->post("action") === "Login") {
            $this->load->helper('notify');
            $message = getError($this->auth());
        }
        $viewData = array(
            "title" => "Login",
            "formAction" => "",
            "notification" => $message
        );
        $this->load->library('parser');
        $this->parser->parse("admin/login", $viewData);
    }
    
    private function auth()
    {
        $this->load->database();
        $username = $this->input->post("username");
        $password = $this->input->post("password");
        $this->load->helper('encrypt');
        $password = ci_encrypt($password);
        
        $sql = "SELECT * FROM ACCOUNT WHERE EMAIL=? AND PASSWORD=? AND ACCOUNT_TYPE='A' AND STATUS='A'";
        $query = $this->db->query($sql, array($username, $password));
        if (count($query->result()) > 0) {
            $this->session->set_userdata(array(
                "username" => $username,
                "firstname" => $query->row()->FIRSTNAME,
                "status" => 'A'
            ));
            redirect("admin", "refresh");
        } else {
            return "Invalid username or password";
        }
    }
}