<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
class Index extends CI_Controller {
	
	
	public function __construct() {
		parent::__construct();
		$this->load->library("client", array($this));
		$this->load->library('parser');
		$this->load->library('session');
		
		
		$this->load->helper('url');
		$this->session->set_flashdata('lastURL', current_url());

		$this->load->database();
		

	}
	
	

	public function Index() {
		$templateData = array(
			"eventsList" => ""
		);
		$query = $this->db->query("SELECT * FROM EVENT WHERE STATUS='A' AND EVENT_DATE > CURRENT DATE ORDER BY EVENT_DATE");
		if (count($query->result()) > 0) {
			$this->load->helper('date');
			$this->load->helper('client');
			$now = time()-100000;
			foreach ($query->result() as $event) {
				$templateData['eventsList'] .= '
				<div class="box clearfix">
					<h3>'.$event->NAME.'</h3>
					<div>'.fdate($event->EVENT_DATE).'<br/><sub>'.getTimespan( $now, getTimestamp($event->EVENT_DATE)).'</sub></div>
					<p>'.$event->DESCRIPTION.'</p>
					<p><a href="/event/about/'.$event->EVENT_ID.'" class="btn btn-info">More</a><a class="btn btn-success pull-right" href="/buy/ticket/'.$event->EVENT_ID.'">Buy tickets</a></p>
				</div>
				';
			}
		} else {
			
		}
		$this->client->loadHeader("Welcome");
		$this->parser->parse("index", $templateData);
		$this->client->loadFooter();
	}	
}