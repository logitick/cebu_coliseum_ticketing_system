<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
class Buy extends CI_Controller {
	
	
	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('client', array($this));
		$this->load->library('parser');
	}

	public function index() {
		
	}	
	
	public function ticket() {
		$event_id = $this->uri->segment(3);
		$this->load->helper("client_notify");
		$templateData = array(
			"title" => "Buy tickets",
			"message" => "",
			"floors" => "",
			"sections" => "",
			"event_id" => $event_id
		);
		
		$eventSql = "SELECT NAME FROM EVENT WHERE EVENT_ID = ? AND STATUS = 'A'";
		$eventQuery = $this->db->query($eventSql, array($event_id));
		if (count($eventQuery->result()) > 0) {
			$event = $eventQuery->row();
			$templateData['title'] = "Buy tickets to ".$event->NAME;
			$templateData['heading'] = $event->NAME;
			$sectionSql = "
SELECT A.AREA_ID, A.NAME AS AREA, SUM(ASE.SEATS) AS SEATS, EAP.PRICE, SUM(ASE.SEATS) - COUNT(T.TICKET_ID) AS AVAILABLE
				FROM AREA_SECTION ASE 
					JOIN AREA A 
						ON ASE.AREA_ID = A.AREA_ID 
					JOIN EVENT_AREA_PRICE EAP 
						ON A.AREA_ID = EAP.AREA_ID 
					LEFT JOIN TICKET T
						ON ASE.AREA_SECTION_ID = T.AREA_SECTION_ID AND EAP.EVENT_ID = T.EVENT_ID
				WHERE NOT A.AREA_ID = 'GA'
				GROUP BY A.AREA_ID, A.NAME, EAP.PRICE, EAP.EVENT_ID 
					HAVING EAP.EVENT_ID = ? 
UNION all					
SELECT A.AREA_ID, A.NAME AS AREA, SUM(DISTINCT ASE.SEATS) AS SEATS, EAP.PRICE, SUM( DISTINCT ASE.SEATS) - COUNT(T.TICKET_ID) AS AVAILABLE
				FROM AREA_SECTION ASE 
					JOIN AREA A 
						ON ASE.AREA_ID = A.AREA_ID 
					JOIN EVENT_AREA_PRICE EAP 
						ON A.AREA_ID = EAP.AREA_ID 
					LEFT JOIN TICKET T
						ON ASE.AREA_SECTION_ID = T.AREA_SECTION_ID AND EAP.EVENT_ID = T.EVENT_ID
				WHERE A.AREA_ID = 'GA'
				GROUP BY A.AREA_ID, A.NAME, EAP.PRICE, EAP.EVENT_ID 
					HAVING EAP.EVENT_ID = ?				
ORDER BY 4 DESC;
			";
			$sectionsQuery = $this->db->query($sectionSql, array($event_id, $event_id));
			foreach ($sectionsQuery->result() as $section) {
				if ($section->AVAILABLE > 0)
					$templateData['floors'] .= '<option value="'.$section->AREA_ID.'">'.$section->AREA.'</option>';
				switch ($section->AREA_ID) {
					case "GA":
						$templateData['gaPrice'] = $section->PRICE;
						$templateData['gaAvailability'] = $section->AVAILABLE." left";
						break;
					case "UB":
						$templateData['upperBoxPrice'] = $section->PRICE;
						$templateData['upperBoxAvailability'] = $section->AVAILABLE." left";
						break;
					case "LB":
						$templateData['lowerBoxPrice'] = $section->PRICE;
						$templateData['lowerBoxAvailability'] = $section->AVAILABLE." left";
						break;
					
				}
			}
		} else {
			$templateData['message'] = getError("This event is invalid");
		}
		//$priceSql = "SELECT P.PRICE FROM EVENT_AREA A JOIN EVENT_AREA_PRICE P ON A.AREA_ID = P.AREA_ID AND P.EVENT_ID = ?";
		$this->client->loadHeader($templateData['title']);
		$this->parser->parse("buy", $templateData);
		$this->client->loadFooter();
	}
	
	public function confirm() {
		if ($this->input->post('action') && $this->input->post('action') == "Buy Tickets") {
			$this->session->set_userdata('tickets_floor', $this->input->post('buy_ticket_floor'));
			$this->session->set_userdata('tickets_section', $this->input->post('buy_ticket_section'));
			$this->session->set_userdata('tickets_event_id', $this->input->post("event_id"));
			$this->session->set_userdata('tickets_quantity', $this->input->post('buy_ticket_quantity'));
		}
		$quantity = $this->session->userdata('tickets_quantity');
		$templateData = array(
			"title" => "Error",
			"message" => "",
			"form" => "",
			"floor" => $this->session->userdata('tickets_floor'),
			"section" => $this->session->userdata('tickets_section'),
			"quantity" => $quantity,
			"price" => "",
			"total" => "",
			"text" => "Seats that are already taken aren't included in this list",
			"event_id" => $this->session->userdata('tickets_event_id')
		);
		
		if ($this->input->post('action') && $this->input->post('action') == "Process Payment") {
			if (!$this->client->isAuth()) {
				redirect("/login", "refresh");
			}
			
			if ($this->session->userdata('tickets_floor') != "GA") {
				$checkArray = array_unique($this->input->post('confirm_ticket_seat'));
					$ticketRows = $this->input->post('confirm_ticket_row');
					$ticketSeats = $this->input->post('confirm_ticket_seat');
				if (!(array_search(0, $checkArray)) && count($checkArray) == count($this->input->post('confirm_ticket_seat'))) {
					$seats = array();
					foreach ($ticketRows as $key => $row) {
						$seats[$key]['area_section_id'] = $row;
						$seats[$key]['seat_number'] = $ticketSeats[$key];
					}
					
					$this->session->set_userdata('seats', $seats);
					redirect("/buy/payment", "refresh");
				} else if (!(array_search(0, $checkArray) === FALSE)) {
					$this->load->helper("client_notify");
					$templateData['message'] = getWarning("You must have forgotten your seat numbers.", "Please pick seat numbers for all your tickets");
				} else {
					$this->load->helper("client_notify");
					$templateData['message'] = getWarning("Please check the seat numbers", "Please pick different seat numbers for each ticket");
				}
			} else {
					
					$seats = array();
					$sql = "SELECT SEAT_NUMBER FROM TICKET WHERE EVENT_ID = ? AND AREA_SECTION_ID = 71 ORDER BY 1 DESC FETCH FIRST 1 ROWS ONLY";
					$query = $this->db->query($sql, array($this->session->userdata('tickets_event_id')));
					$lastSeat = 0;
					if (count($query->result()) > 0) {
						$lastSeat = $query->row()->SEAT_NUMBER;
					} else {
						$lastSeat = 0;
					}
					
					for ($i = 0; $i < $this->session->userdata('tickets_quantity'); $i++) {
						$seats[$i]['area_section_id'] = 71;
						$seats[$i]['seat_number'] = ++$lastSeat;
					}
					
					$this->session->set_userdata('seats', $seats);
					redirect("/buy/payment", "refresh");
			}
		}
		
		
		$sql = "SELECT * FROM EVENT WHERE EVENT_ID = ? AND STATUS = 'A'";
		$query = $this->db->query($sql, array($templateData['event_id']));
		if (count($query->result()) > 0) {
			$event = $query->row();
			$ticket = $quantity  == 1 ? "ticket":"tickets";
			$templateData['title'] = "Confirm purchase";
			$templateData['heading'] = "Details of " .$quantity . " " . $ticket . " for ". $event->NAME;
		}
		$sql = "SELECT PRICE FROM EVENT_AREA_PRICE WHERE AREA_ID = ? AND EVENT_ID = ?";
		$query = $this->db->query($sql, array($templateData['floor'], $templateData['event_id']));
		$eap = $query->row();
		$templateData['price'] = $eap->PRICE;
		$sql = "SELECT * FROM AREA WHERE AREA_ID = ?";
		$query = $this->db->query($sql, array($this->session->userdata('tickets_floor')));
		$templateData['floor'] = $query->row()->NAME;
		$templateData['section'] = $this->session->userdata('tickets_floor') != "GA" ? $this->session->userdata('tickets_section'):"-";
		if ($this->session->userdata('tickets_floor') != "GA") {
			$sql = "SELECT * FROM AREA_SECTION WHERE AREA_ID = ? AND SECTION_NUMBER = ?";
			$query = $this->db->query($sql, array($this->session->userdata('tickets_floor'), $this->session->userdata('tickets_section')));
			//print_r($this->db);
			$rowOptions = "";
			$seatOptions = "";
			$boxCount = 0;
			$seatOptions = '<option value="0">Pick a seat</option>';
			foreach ($query->result() as $row) {
				$rowOptions .= '<option value="'.$row->AREA_SECTION_ID.'">'.$row->ROW_NUMBER.'</option>';
				if ($row->ROW_NUMBER == 1) {
					for ($i = 1; $i <= $row->SEATS; $i++) {
						$seatOptions .= '<option value="'.$i.'">'.$i.'</option>\n';
					}
				}
			}
			
			
			for ($i = 0; $i < $quantity; $i++) {
				$templateData['form'] .= '
					<div class="box">
					 <h4>Pick a seat for ticket '.($i+1).'</h4>
					  <div class="control-group">
						<label class="control-label" for="confirm_ticket_row['.$i.']">Row</label>
						<div class="controls">
							<select id="confirm_ticket_row['.$i.']" name="confirm_ticket_row[]" class="rowSelector" control="confirm_ticket_seat'.$i.'">
							'.$rowOptions.'
							</select>
						</div>
					  </div>
					  <div class="control-group">
						<label class="control-label" for="confirm_ticket_seat['.$i.']">Seat Number</label>
						<div class="controls">
							<select id="confirm_ticket_seat'.$i.'" name="confirm_ticket_seat[]" class="seatSelector">
							'.$seatOptions.'
							</select>
						</div>
					  </div>
					  
					</div>
				';
			}
		} else {
			$templateData['text'] = "Please proceed to payment processing";
		}
		$this->client->loadHeader($templateData['title']);
		$this->parser->parse("confirm", $templateData);
		$this->client->loadFooter();
	}
	
	public function payment() {
		$templateData = array(
			"message" => "",
			"summary" => "",
			"total" => 0.00,
			"expiry" => "",
			"visaSelected" => $this->input->post('method') == "visa" ? "checked":"",
			"mastercardSelected" => $this->input->post('method') == "mastercard" ? "checked":""
		);
		$this->load->helper("client_notify");
		if ($this->input->post('action') == "Submit") {
			if ($this->input->post('method') && $this->input->post('cardNumber') && $this->input->post('verify') && $this->input->post('expiry')) {
				$check = "";
				if ($this->input->post('method') == "visa") {
					$check = "{^4[0-9]{12}(?:[0-9]{3})?$}";
				} else if ($this->input->post('method') == "mastercard") {
					$check = "{^5[1-5][0-9]{14}$}";
				} else {
					$check = null;
				}		
				if (!is_null($check)) {
					if (preg_match($check, trim($this->input->post('cardNumber'))) == 1) {
						$this->createTicket();
						redirect("/tickets", "refresh");
					} else {
						$templateData['message'] = getWarning("Credit card number is invalid", "Pleas enter a valid credit card number");
					}
				}
			} else if (!$this->input->post('cardNumber')) {
				$templateData['message'] = getWarning("No credit card number", "Please enter your credit card number");
			} else if ($this->input->post('verify')) {
				$templateData['message'] = getWarning("Please enter the verification code", "The verification code is located at the back of the card");
			} else if ($this->input->post('expiry')) {
				$templateData['message'] = getWarning("Expiry code not found", "Please enter the expiry code");
			}
		}
		
		$sql = "SELECT A.NAME AS FLOOR, ASE.ROW_NUMBER AS ROW, EAP.PRICE 
				FROM AREA_SECTION ASE 
						JOIN AREA A 
							ON ASE.AREA_ID = A.AREA_ID 
						JOIN EVENT_AREA_PRICE EAP
							ON EAP.AREA_ID = ASE.AREA_ID 
				WHERE ASE.AREA_SECTION_ID = ? AND EAP.EVENT_ID = ?";
		if ($this->session->userdata('tickets_floor') != "GA") {
			foreach ($this->session->userdata('seats') as $seat) {
				$query = $this->db->query($sql, array($seat['area_section_id'], $this->session->userdata('tickets_event_id')));
				$eventData = $query->row();
				$templateData['summary'] .= '
							<tr>
								<td>'.$eventData->FLOOR.'</td>
								<td>'.$this->session->userdata('tickets_section').'</td>
								<td>'.$eventData->ROW.'</td>
								<td>'.$seat['seat_number'].'</td>
								<td style="text-align:right">'.$eventData->PRICE.'</td>
							</tr>
				';
				$templateData['total'] += (double)$eventData->PRICE;
			}
		} else {
			foreach ($this->session->userdata('seats') as $seat) {
				$query = $this->db->query($sql, array($seat['area_section_id'], $this->session->userdata('tickets_event_id')));
				$eventData = $query->row();
				$templateData['summary'] .= '
							<tr>
								<td>'.$eventData->FLOOR.'</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td style="text-align:right">'.$eventData->PRICE.'</td>
							</tr>
				';
				$templateData['total'] += (double)$eventData->PRICE;
			}
		}
		$templateData['total'] = sprintf("%.2f", $templateData['total']);
		$this->client->loadHeader("Process Payment");
		$this->parser->parse("payment", $templateData);
		$this->client->loadFooter();
	}
	
	private function createTicket() {
		foreach ($this->session->userdata('seats') as $seat) {
			$sql = "SELECT A.NAME AS FLOOR, ASE.ROW_NUMBER AS ROW, EAP.PRICE 
				FROM AREA_SECTION ASE 
						JOIN AREA A 
							ON ASE.AREA_ID = A.AREA_ID 
						JOIN EVENT_AREA_PRICE EAP
							ON EAP.AREA_ID = ASE.AREA_ID 
				WHERE ASE.AREA_SECTION_ID = ? AND EAP.EVENT_ID = ?";
			$query = $this->db->query($sql, array($seat['area_section_id'], $this->session->userdata('tickets_event_id')));
			$eventData = $query->row();
			
			$sql = "INSERT INTO TICKET VALUES (DEFAULT, ?, ?, ?, ?, ?, CURRENT DATE)";
			$query = $this->db->query($sql, array(
				$this->session->userdata('tickets_event_id'),
				$seat['area_section_id'],
				$this->session->userdata('account_id'),
				$seat['seat_number'],
				$eventData->PRICE			
			));
			
			if (!$query) {
				print_r($this->db);
				die();
			}
		}
		$this->session->set_flashdata("message", "You can access and print your tickets anytime");
		$this->session->unset_userdata('seats');
	}

}