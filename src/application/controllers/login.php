<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
class Login extends CI_Controller {
	
	
	public function __construct() {
		parent::__construct();
		$this->load->library("client", array($this));
		$this->load->library('session');
		$this->load->database();
		$this->load->helper("encrypt");
		$this->load->library("client", array($this));
		$this->load->helper('url');
		if ($this->client->isAuth()) {
			if (!is_null($this->session->flashdata('lastURL')))
				redirect($this->session->flashdata('lastURL'), "refresh");
			redirect("/", "refresh");
		}
	}

	public function index() {
		$this->load->helper('client_notify');
		$templateData = array(
			"message" => "",
			"firstname" => $this->input->post("signup_firstname"),
			"lastname" => $this->input->post("signup_lastname"),
			"email" => $this->input->post("signup_email"),
		);
		if ($this->input->post("action")) {
			if ($this->input->post("signup_password") == $this->input->post("signup_verify")) {
				$password = $this->input->post("signup_password");
				
				if ($templateData['firstname'] == "") {
					$templateData['message'] .= getError("First name is empty", "Did you forget your first name?");
				}
				
				if ($templateData['lastname'] == "") {
					$templateData['message'] .= getError("You've forgetten to enter you last name", "Please tell us your last name.");
				}
				
				$this->load->helper('email');
				if (!valid_email($templateData["email"])) {
					$templateData['message'] .= getError("Invalid email format", "Please enter a valid email address");
				}
				
				if ($this->input->post("signup_password") == ""){
					$templateData['message'] .= getError("Password cannot be empty", "Please enter a password at least 6 characters long");
				}
	
				if ($templateData['message'] == "") {
					$checkSql = "SELECT email FROM ACCOUNT WHERE email = ?";
					$query = $this->db->query($checkSql, array($templateData['email']));
					
					if (count($query->result()) == 0) {
						$insertSql = "INSERT INTO ACCOUNT VALUES (DEFAULT, ?, ?, ?, ?, CURRENT DATE, DEFAULT, DEFAULT)";
						$query = $this->db->query($insertSql, array($templateData['email'], ci_encrypt($password), $templateData['firstname'], $templateData['lastname']));
						if ($query) {
							$templateData['message'] = getSuccess("Account successfuly created", "You can now login");
						} else {
							$templateData['message'] = getError("Please try again", "There was an error while creating your account");
						}
					} else {
						$templateData['message'] = getWarning("This email address is already registered", "Please try to login using ".$templateData['email']);
					}
				}
				
			} else {
				$templateData['message'] = getError("Your passwords do not match", "Please enter your password twice to verify");
			}
		}
		if ($this->input->post("login_email") && $this->input->post("login_password")) {
			
			$username = $this->input->post('login_email');
			$password = ci_encrypt($this->input->post('login_password'));
			$sql = "SELECT * FROM ACCOUNT WHERE EMAIL=? AND PASSWORD=? AND STATUS='A'";
			$query = $this->db->query($sql, array($username, $password));
			if (count($query->result()) > 0) {
				
				$user = $query->row();
				if ($user->ACCOUNT_TYPE == "A") {
					redirect("/admin", "refresh");
				}
				if ($user->ACCOUNT_TYPE == "R") {
					$this->session->set_userdata('account_id', $user->ACCOUNT_ID);
					$this->session->set_userdata('status', 'OK');
					$this->session->set_userdata('name', $user->FIRSTNAME);
					if (!($this->session->flashdata('lastURL') === FALSE)) {
						redirect($this->session->flashdata('lastURL'), "refresh");
					} else if (!($this->session->userdata('tickets_quantity') === FALSE)) {
						redirect("/buy/confirm", "refresh");
					}

					//redirect("/", "refresh");
				}
			} else {
				$templateData['message'] = getWarning("Cannot Sign in", "Wrong email or password.");
			}
		}
		
		$this->client->loadHeader("Login");
		$this->parser->parse("login", $templateData);
		$this->client->loadFooter();
	}	
}