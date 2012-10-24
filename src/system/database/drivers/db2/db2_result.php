<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * db2 Result Class
 *
 * This class extends the parent result class: CI_DB_result
 *
 * @category	Database
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/database/
 */
class CI_DB_db2_result extends CI_DB_result {

	/**
	 * Number of rows in the result set
	 *
	 * @access	public
	 * @return	integer
	 */
	function num_rows()
	{
		return @db2_num_rows($this->result_id);
	}

	// --------------------------------------------------------------------

	/**
	 * Number of fields in the result set
	 *
	 * @access	public
	 * @return	integer
	 */
	function num_fields()
	{
		return @db2_num_fields($this->result_id);
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch Field Names
	 *
	 * Generates an array of column names
	 *
	 * @access	public
	 * @return	array
	 */
	function list_fields()
	{
		$field_names = array();
		for ($i = 0; $i < $this->num_fields(); $i++)
		{
			$field_names[]	= db2_field_name($this->result_id, $i);
		}

		return $field_names;
	}

	// --------------------------------------------------------------------

	/**
	 * Field data
	 *
	 * Generates an array of objects containing field meta-data
	 *
	 * @access	public
	 * @return	array
	 */
	function field_data()
	{
		$retval = array();
		for ($i = 0; $i < $this->num_fields(); $i++)
		{
			$F				= new stdClass();
			$F->name		= db2_field_name($this->result_id, $i);
			$F->type		= db2_field_type($this->result_id, $i);
			$F->max_length	= db2_field_len($this->result_id, $i);
			$F->primary_key = 0;
			$F->default		= '';

			$retval[] = $F;
		}

		return $retval;
	}

	// --------------------------------------------------------------------

	/**
	 * Free the result
	 *
	 * @return	null
	 */
	function free_result()
	{
		if (is_resource($this->result_id))
		{
			db2_free_result($this->result_id);
			$this->result_id = FALSE;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Data Seek
	 *
	 * Moves the internal pointer to the desired offset.  We call
	 * this internally before fetching results to make sure the
	 * result set starts at zero
	 *
	 * @access	private
	 * @return	array
	 */
	function _data_seek($n = 0)
	{
		return FALSE;
	}

	// --------------------------------------------------------------------

	/**
	 * Result - associative array
	 *
	 * Returns the result set as an array
	 *
	 * @access	private
	 * @return	array
	 */
	function _fetch_assoc()
	{
		if (function_exists('db2_fetch_object'))
		{
			return db2_fetch_array($this->result_id);
		}
		else
		{
			return $this->_db2_fetch_array($this->result_id);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Result - object
	 *
	 * Returns the result set as an object
	 *
	 * @access	private
	 * @return	object
	 */
	function _fetch_object()
	{
		if (function_exists('db2_fetch_object'))
		{
			return db2_fetch_object($this->result_id);
		}
		else
		{
			return $this->_db2_fetch_object($this->result_id);
		}
	}


	/**
	 * Result - object
	 *
	 * subsititutes the db2_fetch_object function when
	 * not available (db2_fetch_object requires unixdb2)
	 *
	 * @access	private
	 * @return	object
	 */
	function _db2_fetch_object(& $db2_result) {
		$rs = array();
		$rs_obj = FALSE;
		if (db2_fetch_into($db2_result, $rs)) {
			foreach ($rs as $k=>$v) {
				$field_name= db2_field_name($db2_result, $k+1);
				$rs_obj->$field_name = $v;
			}
		}
		return $rs_obj;
	}


	/**
	 * Result - array
	 *
	 * subsititutes the db2_fetch_array function when
	 * not available (db2_fetch_array requires unixdb2)
	 *
	 * @access	private
	 * @return	array
	 */
	function _db2_fetch_array(& $db2_result) {
		$rs = array();
		$rs_assoc = FALSE;
		if (db2_fetch_into($db2_result, $rs)) {
			$rs_assoc=array();
			foreach ($rs as $k=>$v) {
				$field_name= db2_field_name($db2_result, $k+1);
				$rs_assoc[$field_name] = $v;
			}
		}
		return $rs_assoc;
	}

}


/* End of file db2_result.php */
/* Location: ./system/database/drivers/db2/db2_result.php */