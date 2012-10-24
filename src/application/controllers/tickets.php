<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tickets extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('client', array($this));
		$this->load->library('parser');
		if (!$this->client->isAuth()) {
			redirect("/login", "refresh");
		}
	}
	
	public function index() {
		$templateData = array(
			"user" => $this->session->userdata('name'),
			"tickets" => "",
			"message" => ""
		);
		if ($this->session->flashdata('message')) {
			$this->load->helper('client_notify');
			$templateData['message'] = getSuccess("Transaction successful",$this->session->flashdata('message'));
		}
		$sql = "
			SELECT T.TICKET_ID, E.NAME AS EVENT, A.NAME AS FLOOR, ASE.SECTION_NUMBER AS SECTION, ASE.ROW_NUMBER AS ROW, T.SEAT_NUMBER, T.SALES_PRICE AS PRICE, T.DATE_PURCHASED AS DATE
			FROM TICKET T 
				JOIN EVENT E 
					ON T.EVENT_ID = E.EVENT_ID
				JOIN AREA_SECTION ASE
					ON T.AREA_SECTION_ID = ASE.AREA_SECTION_ID
				JOIN AREA A 
				ON ASE.AREA_ID = A.AREA_ID 
			WHERE T.ACCOUNT_ID = ?
			ORDER BY T.DATE_PURCHASED, 2, 4, 5
		";
		
		$query = $this->db->query($sql, array($this->session->userdata('account_id')));
		$ticketCount = count($query->result());
		if ($ticketCount > 0) {
			$this->load->helper('client');
			$lastEvent = "";
			foreach ($query->result() as $ticket) {
				if ($lastEvent != $ticket->EVENT) {
					$templateData['tickets'] .= '
						<thead>
							<tr>
								<th colspan="7"><h3>'.$ticket->EVENT.'</h3></th>
							</tr>
						</thead>
						<thead>
							<tr>
								<th>Floor</th>
								<th>Section</th>
								<th>Row</th>
								<th>Seat Number</th>
								<th>Date Purchased</th>
								<th>Price</th>
								<th></th>
							</tr>
						</thead>
					';
					$lastEvent = $ticket->EVENT;
				}
				$templateData['tickets'] .= '
							<tr>
								<td>'.$ticket->FLOOR.'</td>
								<td>'.$ticket->SECTION.'</td>
								<td>'.$ticket->ROW.'</td>
								<td>'.$ticket->SEAT_NUMBER.'</td>
								<td>'.fdate($ticket->DATE).'</td>
								<td>'.$ticket->PRICE.'</td>
								<td><a href="/tickets/download/'.$ticket->TICKET_ID.'"><i class="icon-download"></i> <small>download</small></a>&nbsp&nbsp&nbsp&nbsp&nbsp<a href="/tickets/download/'.$ticket->TICKET_ID.'/preview"><small><i class="icon-file"></i> preview</small></a></td>
							</tr>
				';

			}
		} else {
			$this->load->helper('client_notify');
			$templateData['tickets'] = getWarning("You haven't bought any tickets yet", "");
		}
		$this->client->loadHeader($templateData['user'] . "'s tickets");
		$this->parser->parse("tickets", $templateData);
		$this->client->loadFooter();
	}
	
	private function getTicketNumber($ticket_id) {
		$sql = "SELECT T.EVENT_ID, T.ACCOUNT_ID, ASE.AREA_ID, T.AREA_SECTION_ID, ASE.SECTION_NUMBER, ASE.ROW_NUMBER, T.SEAT_NUMBER, T.TICKET_ID
				FROM TICKET T
					JOIN AREA_SECTION ASE
						ON T.AREA_SECTION_ID = ASE.AREA_SECTION_ID
				WHERE T.TICKET_ID = ?
		";
		$query = $this->db->query($sql, array($ticket_id));
		
		if (count($query->result()) > 0) {
			$ticket = $query->row();
			return sprintf("%02d-%d-%d-%s%02d%02d%02d%04d", $ticket->EVENT_ID, $ticket->ACCOUNT_ID, $ticket->AREA_SECTION_ID, $ticket->AREA_ID, $ticket->SECTION_NUMBER, $ticket->ROW_NUMBER, $ticket->SEAT_NUMBER, $ticket->TICKET_ID);
		}
		return 0;
	
	}
	
	public function download() {
		$preview = $this->uri->segment(4);
		$id = $this->uri->segment(3);
		$this->load->library('mpdf');
		
		$templateData = array(
			"event" => "",
			"eventDate" => "",
			"ticketNumber" => $this->getTicketNumber($id),
			"floor" => "",
			"section" => "",
			"row" => "",
			"seat" => ""
		);
		
		$sql = "
			SELECT T.ACCOUNT_ID, E.NAME AS EVENT, E.EVENT_DATE AS DATE, A.NAME AS FLOOR, ASE.SECTION_NUMBER AS SECTION, ASE.ROW_NUMBER AS ROW, T.SEAT_NUMBER
			FROM TICKET T 
				JOIN EVENT E 
					ON T.EVENT_ID = E.EVENT_ID
				JOIN AREA_SECTION ASE
					ON T.AREA_SECTION_ID = ASE.AREA_SECTION_ID
				JOIN AREA A 
				ON ASE.AREA_ID = A.AREA_ID 
			WHERE T.TICKET_ID = ?
		";
		$query = $this->db->query($sql, array($id));
	
		if (count($query->result()) > 0) {
			$this->load->helper('client_helper');
			$ticket = $query->row();
			if ($ticket->ACCOUNT_ID != $this->session->userdata('account_id')) {
				die();
			}
			$templateData['event'] = $ticket->EVENT;
			$templateData['eventDate'] = fdate($ticket->DATE);
			$templateData['floor'] = $ticket->FLOOR;
			$templateData['section'] = $ticket->SECTION;
			$templateData['row'] = $ticket->ROW;
			$templateData['seat'] = $ticket->SEAT_NUMBER;
			$html = $this->parser->parse('ticketTemplate', $templateData, true);
			$mpdf = new mPDF('utf-8', 'Letter');
			$mpdf->WriteHTML($html);
			if ($preview == "preview") 
				$mpdf->Output();
			else
				$mpdf->Output("cebu-coliseum-ticket.pdf", "D");
			exit;
		}

	}
	
}