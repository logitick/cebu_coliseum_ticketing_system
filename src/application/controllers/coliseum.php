<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Coliseum extends CI_Controller {
	

	
	public function index() {
		$this->load->database();
		

		
	}
	
	public function sections() {
		$this->load->database();
		$area_id = $this->uri->segment(3);
		$sql = 'SELECT AREA_ID AS ID, SECTION_NUMBER, COUNT(ROW_NUMBER) AS ROWS FROM AREA_SECTION WHERE AREA_ID = ? GROUP BY AREA_ID, SECTION_NUMBER';
		$query = $this->db->query($sql, array($area_id));
		if (count($query->result()) > 1) {
			foreach ($query->result() as $area) {
				echo '<option value="'.$area->SECTION_NUMBER.'">'.$area->SECTION_NUMBER.'</option>';
			}
		} else {
			echo '<option value="1">Not Applicable</option>';
		}
	}
	
	public function rows() {
		$this->load->database();
		$area_section_id = $this->uri->segment(3);
		$sql = 'SELECT * FROM AREA_SECTION WHERE AREA_SECTION_ID = ? ';
		$query = $this->db->query($sql, array($area_section_id));
		if (count($query->result()) == 1) {
			$row = $query->row();
			for ($i = 1; $i <= $row->SEATS; $i++) {
				echo '<option value="'.$i.'">'.$i.'</option>\n';
			}
		} else {
			echo '<option value="1">Not Applicable</option>';
		}
	}
}