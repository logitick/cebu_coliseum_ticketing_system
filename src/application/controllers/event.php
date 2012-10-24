<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('client', array($this));
		$this->load->library('parser');
	}
	
	private function index() {
		

	}
	
	public function about() {
		$templateData = array(
			"message" => "",
			"eventName" => "",
			"eventDescription" => ""
		);
		$event_id = $this->uri->segment(3);
		$sql = "SELECT NAME, DESCRIPTION FROM EVENT WHERE EVENT_ID = ? AND STATUS = 'A'";
		$query = $this->db->query($sql, array($event_id));
		if (count($query->result()) > 0) { 
			$event = $query->row();
			$templateData['eventName'] = $event->NAME;
			$templateData['eventDescription'] = $event->DESCRIPTION;
		} else {
			show_404();
		}
		if (!$this->uri->segment(4)) {
			$this->client->loadHeader("");
		}
		$this->parser->parse("event", $templateData);
		if (!$this->uri->segment(4)) {
			$this->client->loadFooter();
		}
	}
	
	
	
}