<?php

class Spreadsheet_loader {
	
	private $ss_rows = array();
	private $tracking_info_fields = array();
	private $aux_info_fields = array();
	private $aux_info_groups = array();
	private $entity_type = '';
	private $entity_list = array();
	
	// --------------------------------------------------------------------
	function __construct()
	{
	}

	// --------------------------------------------------------------------
	function get_tracking_info_fields()
	{
		return $this->tracking_info_fields;
	}
	
	// --------------------------------------------------------------------
	function get_aux_info_fields()
	{
		return $this->aux_info_fields;
	}
	
	// --------------------------------------------------------------------
	// read given spreadsheet TSV formatted file into an internal two-dimensional
	// array and parse it into supplementary arrays
	// AJAX
	function load($fname)
	{
		// read TSV file into array of rows of fields
		$this->ss_rows = array();
		$handle = fopen("./uploads/$fname", "r");
		while (($fields = fgetcsv($handle, 0, "\t")) !== FALSE) {
			$this->ss_rows[] = $fields;
		}
		fclose($handle);
		// figure out where things are and build supplemental arrays
		$this->find_tracking_info_fields();
		$this->find_aux_info_fields();
		$this->extract_entity_type();
		$this->extract_entity_list();
	}

	// --------------------------------------------------------------------
	// get list of tracking info fields and their row number
	private
	function find_tracking_info_fields()
	{
		$this->tracking_info_fields = array();
		$grab_it = FALSE;
		$end_it = FALSE;
		for($i=0; $i<count($this->ss_rows);$i++) {
		    if ($this->ss_rows[$i][0] == "AUXILIARY INFORMATION") {
		    	$end_it = TRUE;
	    		break;
	    	}
			if($grab_it) {
				if($this->ss_rows[$i][0] == '') {
					throw new exception("Blanks are not permitted in the first column in the tracking info section (row $i)");
					break;
				}
				$this->tracking_info_fields[$this->ss_rows[$i][0]] = $i;
			}
	    	if ($this->ss_rows[$i][0] == "TRACKING INFORMATION") {
				$grab_it = TRUE;
	    	}
		}
		if(!$end_it) throw new exception('The tracking info section was not terminated by "AUXILIARY INFORMATION" header');
	}
	
	// --------------------------------------------------------------------
	// get list of aux info items, including their category, subcategory, 
	// and item names and the row number in the main data array.
	// this builds the $this->aux_info_fields array (flat array of items labeled with category and subcategory)
	// and the $this->aux_info_groups (arrays of items nested within category/subcatory pairs)
	private
	function find_aux_info_fields()
	{
		$this->aux_info_fields = array();
		$in_section = FALSE;
		$in_data = FALSE;
		$mark = FALSE;
		$category = '';
		$subcategory = '';
		for($i=0; $i<count($this->ss_rows);$i++) {
			if($in_section) {
				$name = $this->ss_rows[$i][0];
				$has_data = (count($this->ss_rows[$i]) > 1 && $this->ss_rows[$i][1] != '');
				if($has_data) {
					if(!$in_data) throw new exception("Possible missing category or subcategory ('$name' near row $i)" . "<br><br>" . $this->sup_mes['header']);
					$mark = FALSE;
					$p = new stdClass();
					$p->category = $category;
					$p->subcategory = $subcategory;
					$p->item = $name;
					$p->row = $i;
					$this->aux_info_fields[] = $p;
				} else
				if(!$mark) {
					$category = $name;
					$mark = TRUE;
					$in_data = FALSE;
				} else {
					if($in_data) throw new exception("Possible extra subcategory ('$name' near row $i)". "<br><br>" . $this->sup_mes['header']);
					$subcategory = $name;
					$o = new stdClass();
					$o->category = $category;
					$o->subcategory = $subcategory;
					$this->aux_info_groups[] = $o;
					$in_data = TRUE;
				}				
			}
	    	if ($this->ss_rows[$i][0] == "AUXILIARY INFORMATION") {
				$in_section = TRUE;
	    	}
		}
	}

	// --------------------------------------------------------------------
	// suplemental messages
	private $sup_mes = array (
		'header' => "The usual reason for this is either forgetting to include both category and subcategory headers for each aux info section, or leaving the value blank for an aux info item for the first entity",
	);
	
	// --------------------------------------------------------------------
	// get array of field/values for the tracking information for the 
	// given entity
	function get_entity_tracking_info($id)
	{
		$info = array();
		$col = array_search($id, $this->entity_list);
		if(!($col === FALSE)) {
			$col++;
			foreach($this->tracking_info_fields as $field => $row) {
				if(count($this->ss_rows[$row]) <= $col) {
					$info[$field] = '';
				} else {
					$info[$field] = $this->ss_rows[$row][$col];
				}
			}
		}
		return $info;
	}

	// --------------------------------------------------------------------
	// get aux info for given entity
	function get_entity_aux_info($id)
	{
		$info = array();
		$col = array_search($id, $this->entity_list);
		if(!($col === FALSE)) {
			$col++;
			foreach($this->aux_info_fields as $obj) {
				$obj->value = $this->ss_rows[$obj->row][$col];
				$info[] = $obj;
			}
		}
		return $info;
	}

	// --------------------------------------------------------------------
	// repackage given aux info into array of category/subcategory groups
	// with array of item/values for each cat/subcat
	function group_aux_info_items($aux_info_fields)
	{
		$out = array();
		foreach($this->aux_info_groups as $g) {
			$group = clone($g);
			$items = array();
			foreach($aux_info_fields as $f) {
				if($g->category == $f->category and $g->subcategory == $f->subcategory ) {
					$items[$f->item] = $f->value;
				}
			}
			$group->items = $items;
			$out[] = $group;
		}
		return $out;
	}

	// --------------------------------------------------------------------
	// what type of entity was defined in the spreadsheet
	private
	function extract_entity_type()
	{
		foreach($this->ss_rows as $row) {
			$s = $row[0];
			if($s != '') {
				if($s == 'TRACKING INFORMATION') {
					throw new exception("Entity type is missing");
				}
				$this->entity_type = $s;
				break;
			} 
		}
		if($this->entity_type == '') {
			throw new exception("Entity type is not defined");
		}
		if($this->entity_type != strtoupper($this->entity_type)) {
			throw new exception("Entity type '$this->entity_type' must be upper case");
		}		
	}
	
	// --------------------------------------------------------------------
	// get list of entities that are defined in spreadsheet
	private
	function extract_entity_list()
	{
		// the entity is, by definition, the first field in the list, so get its row number
		$row = current($this->tracking_info_fields);

		// use row number to get row of entity values from parsed main data array
		$this->entity_list = $this->ss_rows[$row];
		
		// first field is header column, get rid of it
		array_shift($this->entity_list);
		
		// sometimes spreadsheet has extra empty columns, 
		// so remove trailing blank entries starting at end of list and working forward
		while(!empty($this->entity_list)) {
			if(trim(end($this->entity_list)) == '') {
				array_pop($this->entity_list);
			} else {
				break;
			}
		}
	}
	
	// --------------------------------------------------------------------
	function get_extracted_data()
	{
		return $this->ss_rows;
	}

	// --------------------------------------------------------------------
	function get_entity_type()
	{
		return $this->entity_type;
	}
	
	// --------------------------------------------------------------------
	function get_entity_list()
	{
		return $this->entity_list;
	}
}
?>
