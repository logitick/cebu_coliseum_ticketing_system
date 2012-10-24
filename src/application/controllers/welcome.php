<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	
	public function index()
	{
	 
		//$this->load->database("db2://db2:db2@DATABASE=SAMPLE;HOSTNAME=localhost;PORT=50000;PROTOCOL=TCPIP;UID=db2;PWD=db2;");
        $this->load->database();
		
        $query = $this->db->query("SELECT * FROM ACCOUNT");
 
        foreach ($query->result() as $row)
        {
           print_r($row);
        }
/* 		$conn = db2_connect("CCTICKET", "db2", "db2");

		$sql = "SELECT * FROM ACCOUNT";
	if ($conn) {
    
    $stmt = db2_exec($conn, $sql, array('cursor' => DB2_SCROLLABLE));
    while ($row = db2_fetch_array($stmt)) {
        print_r($row);
    } 
	} */
		
		//db2_close($conn);

		/* $this->load->view('welcome_message', array("hash" => "B44CCE1F8AEEF328A344A5F8D783E64C6C409F271D705DA275CAD1C6A9EB23515324844AEC997C96F1AE7223C0DC5E56656F50356E32A788081ED95DD1ADD6DA")); */
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */