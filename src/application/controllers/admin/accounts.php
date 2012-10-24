<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accounts extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->library("admin", array($this));
        $this->admin->isAuth();
        $this->load->library('parser');
		$this->load->database();
    }
    public function index() {
        
        $this->display();
    }
	
	public function display() {
		$this->load->library("table");
		$templateData = array(
			"h2" => "Accounts List",
			"list" => ""
		);
		$table_template = array (
                    'table_open'          => '<table class="DataList">',

                    'heading_row_start'   => '<tr>',
                    'heading_row_end'     => '</tr>',
                    'heading_cell_start'  => '<th>',
                    'heading_cell_end'    => '</th>',

                    'row_start'           => '<tr>',
                    'row_end'             => '</tr>',
                    'cell_start'          => '<td>',
                    'cell_end'            => '</td>',

                    'row_alt_start'       => '<tr class="AltRow">',
                    'row_alt_end'         => '</tr>',
                    'cell_alt_start'      => '<td>',
                    'cell_alt_end'        => '</td>',

                    'table_close'         => '</table>'
              );
		$this->table->set_template($table_template);
		$this->table->set_heading("First Name", "Last Name", "Email", "Date Created", "Account Type","Status", "Actions");
		
		$sql = "SELECT account_id, firstname, lastname, email, created, status, account_type FROM ACCOUNT";
		$query = $this->db->query($sql);
		foreach ($query->result() as $row) {
				$actions = '<a href="/admin/accounts/update/'.$row->ACCOUNT_ID.'"><img src="/media/images/icon-pencil-small.png" title="Edit '.$row->LASTNAME.'"></a>';
				$email = '<a href="/admin/accounts/user/'.$row->ACCOUNT_ID.'">'.$row->EMAIL.'</a>';
				
				if ($row->STATUS == "A" && $row->ACCOUNT_ID != 0)
					$actions .=' <a href="/admin/accounts/suspend/'.$row->ACCOUNT_ID.'"><img src="/media/images/icon-lock-small.png" title="Suspend '.$row->LASTNAME.'"></a>';
				else if ($row->ACCOUNT_ID != 0)
					$actions .=' <a href="/admin/accounts/activate/'.$row->ACCOUNT_ID.'"><img src="/media/images/icon-unlock-small.png" title="Activate '.$row->LASTNAME.'"></a>';
					
				
				
				$type = $row->ACCOUNT_TYPE == "A" ? "Administrator":"Regular";
				$this->table->add_row($row->FIRSTNAME, $row->LASTNAME, $email, $row->CREATED, $type, $row->STATUS, $actions);
		}

		$templateData["list"] =  $this->table->generate();
		
        $this->admin->loadHeader("Accounts List");
        $this->parser->parse("admin/accounts", $templateData);
        $this->admin->loadFooter();
	}
    
    public function create() {
		

		$this->load->helper("notify");
		$this->load->library("form_validation");
		$this->form_validation->set_rules("type", "Account type", "required");
		$this->form_validation->set_rules("firstname", "First Name", "required");
		$this->form_validation->set_rules("lastname", "Last Name", "required");
		$this->form_validation->set_rules("email", "Email", "required|valid_email");
		$this->form_validation->set_rules("password", "Password", "required|matches[verifyPassword]");
		
        $templateData = array(
			"heading" => "Create a new account",
			"notification" => "",
			"rSelected" => 'checked="checked"',
			"aSelected" => "",
			"firstname" => "",
			"lastname" => "",
			"email" => "",
			"etc" => "",
			"buttonValue" => "Create User"
        );
		
		if ($this->input->post("action") && $this->input->post("action") == "Create User") {
			if ($this->form_validation->run() === false) {
				$templateData["notification"] = getWarning(validation_errors());
			} else {
				$this->load->helper("encrypt");
				$sql = "INSERT INTO ACCOUNT (ACCOUNT_ID, EMAIL, PASSWORD, FIRSTNAME, LASTNAME, CREATED, ACCOUNT_TYPE, STATUS) VALUES (DEFAULT, ?, ?, ?, ?, CURRENT DATE, ?, DEFAULT);";
				
				$formData = array(
					$this->input->post("email"),
					ci_encrypt($this->input->post("password")),
					$this->input->post("firstname"),
					$this->input->post("lastname"),
					$this->input->post("type")
				);
				$query = $this->db->query($sql, $formData);
				if ($query === true)
					
					$templateData["notification"] = getMessage("User created successfully");
				else
					$templateData["notification"] = getError("An error has occured. Try again.");
			}
		}
		
        $this->admin->loadHeader("Create a new account");
        $this->parser->parse("admin/accounts-form", $templateData);
        $this->admin->loadFooter();
    }
	
	
	public function update() {
		$account_id = $this->uri->segment(4);
        $templateData = array(
			"heading" => "Update account",
			"notification" => "",
			"rSelected" => '',
			"aSelected" => "",
			"firstname" => "",
			"lastname" => "",
			"email" => "",
			"etc" => "",
			"buttonValue" => "Update User"
        );
		
		$this->load->helper("notify");
		$this->load->library("form_validation");
		$this->form_validation->set_rules("type", "Account type", "required");
		$this->form_validation->set_rules("firstname", "First Name", "required");
		$this->form_validation->set_rules("lastname", "Last Name", "required");
		if ($account_id != 0)
			$this->form_validation->set_rules("email", "Email", "required|valid_email");
		else 
			$this->form_validation->set_rules("email", "Email", "required");
		$this->form_validation->set_rules("password", "Password", "matches[verifyPassword]");
		

		if ($account_id === false) {
			$templateData["notification"] = getError("Error: no user specified");
		} else {
				
			$templateData["notification"] = getMessage("Leave the password field blank if you do not need to change the password");
			$sql = "SELECT *  FROM ACCOUNT WHERE account_id = ?";
			$query = $this->db->query($sql, array($account_id));
			if (count($query->result()) > 0) {
				$row = $query->row();
				$templateData["heading"] = "Update $row->FIRSTNAME $row->LASTNAME's account.";
				if ($row->ACCOUNT_TYPE == "R") 
					$templateData["rSelected"] = 'checked="checked"';
				else 
					$templateData["aSelected"] = 'checked="checked"';
				
				$templateData["firstname"] = $row->FIRSTNAME;
				$templateData["lastname"] = $row->LASTNAME;
				$templateData["email"] = $row->EMAIL;
			} else {
				$templateData["notification"] = getError("No record of this user found");
			}
			
			if ($this->input->post("action") == "Update User") {
				if ($this->form_validation->run() === true) {
					$query = "";
					if ($this->input->post("password") != false) {
						$sql = "UPDATE ACCOUNT SET firstname = ?, lastname = ?, email = ?, password = ?, account_type = ? WHERE account_id = ?";
						$this->load->helper("encrypt");
						$formData = array(
										$this->input->post("firstname"),
										$this->input->post("lastname"),
										$this->input->post("email"),
										ci_encrypt($this->input->post("password")),
										$this->input->post("type"),
										$account_id
									);
						$query = $this->db->query($sql, $formData);
					} else {
						$sql = "UPDATE ACCOUNT SET firstname = ?, lastname = ?, email = ?, account_type = ? WHERE account_id = ?";
						$formData = array(
										$this->input->post("firstname"),
										$this->input->post("lastname"),
										$this->input->post("email"),
										$this->input->post("type"),
										$account_id
									);
						$query = $this->db->query($sql, $formData);
					}
						if ($query === true) {
							$this->load->helper("url");
							redirect("/admin/accounts", "refresh");
						} else {
							$templateData["notification"] .= getError("An error with the database has occured. Please retry.");
						}
					
				} else {
					$templateData["notification"] .= getWarning(validation_errors());
				}
			}
			
		}
		$this->admin->loadHeader("Create a new account");
        $this->parser->parse("admin/accounts-form", $templateData);
        $this->admin->loadFooter();
	}
	
	
	public function suspend() {
		$account_id = $this->uri->segment(4);
		if ($account_id != false) {
			$sql = "UPDATE ACCOUNT SET status = 'S' WHERE account_id = ?";
			$query = $this->db->query($sql, array($account_id));
		}
		$this->load->helper("url");
		redirect("/admin/accounts/", "refresh");
	}
	
	public function activate() {
		$account_id = $this->uri->segment(4);
		if ($account_id != false) {
			$sql = "UPDATE ACCOUNT SET status = 'A' WHERE account_id = ?";
			$query = $this->db->query($sql, array($account_id));
		}
		$this->load->helper("url");
		redirect("/admin/accounts/", "refresh");
	}
	

}