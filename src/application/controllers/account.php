<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('client', array($this));
		$this->load->library('parser');
		if (!$this->client->isAuth())
			redirect("/login", "refresh");
	}
	
	public function index() {		
		$templateData = array(
			"message" => ""
		);
		
		if ($this->input->post('email_action')) {
			$this->load->helper('email');
			$this->load->helper('client_notify');
			if (!valid_email($this->input->post('new_email'))) {
				$templateData['message'] .= getWarning("Invalid email format", "Please enter a valid email address");
			} else {
				$sql = "UPDATE ACCOUNT SET EMAIL = ? WHERE ACCOUNT_ID = ?";
				$query = $this->db->query($sql, array($this->input->post('new_email'), $this->session->userdata('account_id')));
				if ($query == 1) {
					$templateData['message'] = getSuccess("Email successfuly changed", "Please remember to use this email next time you login.");
				} else {
					$templateData['message'] = getError("An unknown error has occured.", "Please try again");
				}
			}
		}
		
		if ($this->input->post('password_action')) {
			$this->load->helper("encrypt");
			$checkPassword = "SELECT * FROM ACCOUNT WHERE ACCOUNT_ID = ? AND PASSWORD = ?";
			$password = ci_encrypt($this->input->post('old_password'));
			$query = $this->db->query($checkPassword, array($this->session->userdata('account_id'), $password));
			if (count($query->result()) > 0) {
				$this->load->helper('client_notify');
				if ($this->input->post('new_password') == $this->input->post("verify_password")) {
				
				$sql = "UPDATE ACCOUNT SET PASSWORD = ? WHERE ACCOUNT_ID = ?";
				$query = $this->db->query($sql, array(ci_encrypt($this->input->post('new_password')), $this->session->userdata('account_id')));
					if ($query == 1) {
						$templateData['message'] = getSuccess("Password changed successfuly", "Please try to remember your password");
					} else {
						$templateData['message'] = getError("An unknown error has occured.", "Please try again");
					}
				} else {
					$templateData['message'] = getWarning("The passwords do not match", "Please try again.");
				}
				
			} else {
				$this->load->helper('client_notify');
				$templateData['message'] = getError("You entered the wrong password", "Please try again");
			}
		}
		
		$sql = "SELECT FIRSTNAME, LASTNAME, EMAIL, CREATED  FROM ACCOUNT WHERE ACCOUNT_ID = ?";
		$query = $this->db->query($sql, array($this->session->userdata('account_id')));
		if (count($query->result()) > 0) {
			$account = $query->row();
			$templateData['name'] = $account->FIRSTNAME . " " . $account->LASTNAME;
			$templateData['joinDate'] = $account->CREATED;
			$templateData['email'] = $account->EMAIL;
		}

		$this->client->loadHeader("My Account");
		$this->parser->parse("account.php", $templateData);
		$this->client->loadFooter();
	}
}