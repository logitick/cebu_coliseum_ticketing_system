<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Events extends CI_Controller {

	public function __construct() {
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
		$templateData = array(
			"list" => ""
		);
		$this->load->library("table");
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
		$this->table->set_heading("Event", "Date", "Description", "Actions");

$sql = "SELECT * FROM EVENT WHERE STATUS = 'A' AND EVENT_DATE > CURRENT DATE ORDER BY EVENT_DATE ASC";
		$query = $this->db->query($sql);

		foreach ($query->result() as $row) {
			$actions = '<a href="/admin/events/edit/'.$row->EVENT_ID.'"><img src="/media/images/icon-pencil-small.png" title="Edit"></a>';
			$actions .= ' <a href="/admin/events/delete/'.$row->EVENT_ID.'"><img src="/media/images/icon-cross.png" title="Delete"></a>'; 
			$actions .= ' <a href="/admin/events/sales/'.$row->EVENT_ID.'"><img src="/media/images/icon-dollar.png" title="Sales"></a>'; 
			$desc = strlen($row->DESCRIPTION) > 100 ? substr($row->DESCRIPTION, 0, 100)."..":$row->DESCRIPTION;
			$this->table->add_row('<a href="#eventModal" class="modalTrigger" id="'.$row->EVENT_ID.'">'.$row->NAME.'</a>', $row->EVENT_DATE, $desc, $actions);
		}



		$templateData["list"] =  $this->table->generate();

        $this->admin->loadHeader("Events");
        $this->parser->parse("admin/events", $templateData);
        $this->admin->loadFooter();
	}

	public function create() {
		$areas = $this->getAreas();
		$this->load->library("form_validation");
		$templateData = array(
			"heading" => "Create Event",
			"notification" => "",
			"list" => "",
			"areaPriceEntries" => "",
			"name" => "",
			"description" => "",
			"date" => "",
			"buttonValue" => "Add this event"
		);
		if (!is_null($areas)) {
			$templateData["areaPriceEntries"].= '<h3>Section Prices</h3>';
			foreach ($areas as $row) {
				$templateData["areaPriceEntries"] .= '<div><label>'.$row->NAME.'</label><input type="text" name="area['.$row->AREA_ID.']" value="" id="createArea['.$row->AREA_ID.']"  /></div>';
				$this->form_validation->set_rules('area['.$row->AREA_ID.']', $row->NAME.' price', 'required|numeric');
			}
		}

		if ($this->input->post("action") == "Add this event") {
			$this->load->helper("notify");
			$this->form_validation->set_rules("event", "Name", "required");
			$this->form_validation->set_rules("description", "Description", "max_length[1000]");
			$this->form_validation->set_rules("date", "Date", "required");

			if ($this->form_validation->run() == false) {
				$templateData["notification"] .= getWarning(validation_errors());
			} else {
				$this->db->trans_start();
				$sql = "INSERT INTO EVENT ( event_id, name, description, event_date ) VALUES ( DEFAULT, ?, ?, ?)";
				$formData = array(
					$this->input->post("event"),
					$this->input->post("description"),
					$this->input->post("date")
				);

				$query = $this->db->query($sql, $formData);
				if ($query == true) {
					$event_id = $this->db->insert_id();
					$area = $this->input->post("area");
					foreach ($area as $key => $price) {
						$sql = "INSERT INTO EVENT_AREA_PRICE (event_id, area_id, price) VALUES ($event_id, '$key', ?)";
						$query = $this->db->query($sql, array((float)$price));
					}
					$this->load->helper("notify");
					$templateData["notification"] = getMessage("Event added successfully");
				}
				
				$this->db->trans_complete();
				if ($this->db->trans_status() === FALSE) {
					$templateData["notification"] = getError("An error has occured. Please recheck the seat/area prices");
				}
			}

		}

        $this->admin->loadHeader("Add Event");
        $this->parser->parse("admin/events-form", $templateData);
        $this->admin->loadFooter();
	}

	private function getAreas() {
		$sql = "SELECT * FROM AREA";
		$query = $this->db->query($sql);
		if (count($query->result()) > 0) {
			return $query->result();
		}
		return null;
	}

	public function edit() {

		$event_id = $this->uri->segment(4);

		$templateData = array(
			"heading" => "Edit Event",
			"notification" => "",
			"list" => "",
			"areaPriceEntries" => "",
			"name" => "",
			"description" => "",
			"date" => "",
			"buttonValue" => "Edit Event"
		);



		$this->load->helper("notify");
		if ($event_id != false) {




			// get the event data
			$sql = "SELECT * FROM EVENT WHERE event_id = ?";
			$query = $this->db->query($sql, array($event_id));

			if (count($query->result()) < 1) {
				$templateData["notification"] = "No record of this event found.";
			} else {
				$this->load->helper("notify");
				$this->load->library("form_validation");
				$this->form_validation->set_rules("event", "Name", "required");
				$this->form_validation->set_rules("description", "Description", "max_length[1000]");
				$this->form_validation->set_rules("date", "Date", "required");

				$templateData["name"] = $query->row()->NAME;
				$templateData["description"] = $query->row()->DESCRIPTION;
				$templateData["date"] = $query->row()->EVENT_DATE;

				$sql = "SELECT * FROM EVENT_AREA_PRICE WHERE event_id = ?";
				$query = $this->db->query($sql, array($event_id));

				foreach($query->result() as $row) {
					$areaSql = "SELECT * FROM AREA WHERE area_id = '".$row->AREA_ID."'";
					$areaQuery = $this->db->query($areaSql);
					$templateData["areaPriceEntries"] .= '<div><label>'.$areaQuery->row()->NAME.'</label><input type="text" name="area['.$row->AREA_ID.']" value="'.$row->PRICE.'" id="createArea['.$row->AREA_ID.']"  /></div>';
					$this->form_validation->set_rules('area['.$row->AREA_ID.']', $areaQuery->row()->NAME.' price', 'required|numeric');
				}

				if ($this->input->post("action") == $templateData["buttonValue"]) {

					if ($this->form_validation->run() === true) {
						$sql = "UPDATE EVENT SET name = ?, description = ?, event_date = ? WHERE event_id = ?";
						$query = $this->db->query($sql, array($this->input->post("event"), $this->input->post("description"), $this->input->post("date"), $event_id));
						$areas = $this->input->post("area");
						foreach($areas as $id => $price) {
							$sql = "UPDATE EVENT_AREA_PRICE SET price = ? WHERE event_id = ? AND area_id = ?";
							$query = $this->db->query($sql, array($price, $event_id, $id));
						}

						$templateData["notification"] = getMessage("Event updated");

						$sql = "SELECT * FROM EVENT WHERE event_id = ?";
						$query = $this->db->query($sql, array($event_id));
						$templateData["name"] = $query->row()->NAME;
						$templateData["description"] = $query->row()->DESCRIPTION;
						$templateData["date"] = $query->row()->EVENT_DATE;

						$sql = "SELECT * FROM EVENT_AREA_PRICE WHERE event_id = ?";
						$query = $this->db->query($sql, array($event_id));

						$templateData["areaPriceEntries"] = "";

						foreach($query->result() as $row) {
							$areaSql = "SELECT * FROM AREA WHERE area_id = '".$row->AREA_ID."'";
							$areaQuery = $this->db->query($areaSql);
							$templateData["areaPriceEntries"] .= '<div><label>'.$areaQuery->row()->NAME.'</label><input type="text" name="area['.$row->AREA_ID.']" value="'.$row->PRICE.'" id="createArea['.$row->AREA_ID.']"  /></div>';
							$this->form_validation->set_rules('area['.$row->AREA_ID.']', $areaQuery->row()->NAME.' price', 'required|numeric');
						}

					} else {
						$templateData["notification"] = getWarning(validation_errors());
					}
				}
			}


		} else {
			$templateData["notification"] = getError("Event is not specified");
		}


        $this->admin->loadHeader("Edit Event");
        $this->parser->parse("admin/events-form", $templateData);
        $this->admin->loadFooter();
	}
	
	public function delete() {
		$event_id = $this->uri->segment(4);
		
		if (!is_null($event_id)) {
			$deleteSql = "UPDATE EVENT SET STATUS='D' WHERE EVENT_id = ?";
			$query = $this->db->query($deleteSql, array($event_id));
			$this->load->helper("url");
			redirect("/admin/events", "refresh");
		}
		
	}
	
	public function sales() {
		$event_id = $this->uri->segment(4);
		$templateData = array(
		
		);
		
		$sql = 'SELECT NAME FROM EVENT WHERE EVENT_ID = ?';
		$this->admin->loadHeader("Edit Event");
        $this->parser->parse("admin/sales", $templateData);
		$this->admin->loadFooter();
	}	
}