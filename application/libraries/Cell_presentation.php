<?php  
	if (!defined('BASEPATH')) {
		exit('No direct script access allowed');
	}

/**
 * This class is used to format data in list reports, including adding hotlinks
 */
class Cell_presentation {

	private $hotlinks = array();
	var $col_filter = array();
		
	// --------------------------------------------------------------------
	function __construct()
	{
	}

	/**
	 * Initialize
	 * @param mixed $cell_presentation_specs
	 */
	function init($cell_presentation_specs)
	{
		$this->hotlinks = $cell_presentation_specs;		
	}
	
	// --------------------------------------------------------------------
	private
	function get_display_cols($cols)
	{
		if(!empty($this->col_filter)) {
			$cols = $this->col_filter;
		} 
		return $cols;
	}

	// --------------------------------------------------------------------
	function render_row($row)
	{	
		$str = "";
		$display_cols = $this->get_display_cols(array_keys($row));
		$colIndex = 0;
		foreach($display_cols as $name) {
			// don't display columns that begin with hash character
			if($name[0] == '#') {
				continue;
			}

			$value = $row[$name];
			$colSpec = null;
			if(array_key_exists($name, $this->hotlinks)) {
				$colSpec = $this->hotlinks[$name];
			}
			elseif(array_key_exists('@exclude', $this->hotlinks)) {
				if(!in_array($name, $this->hotlinks['@exclude']['Options'])) {
					$colSpec = $this->hotlinks['@exclude'];
				}
			}
			if($colSpec) {
				$str .= $this->render_hotlink($value, $row, $colSpec, NULL, $name, $colIndex);
			}  else {
				$str .= "<td>" . $value . "</td>";
			}
			$colIndex++;
		}
		return $str;
	}

	
	/**
	 * Render a hotlink as HTML, as specified by $colSpec["LinkType"]
	 * @param mixed  $value     String or number
	 * @param mixed  $row       Array of row data
	 * @param mixed  $colSpec
	 * @param type   $col_width Unused variable
	 * @param string $col_name  Column name
	 * @param int    $colIndex  Column index
	 * @return string
	 */
	private
	function render_hotlink($value, $row, $colSpec, $col_width, $col_name='', $colIndex)
	{
		$str = "";
		// resolve target for hotlink
		$target = $colSpec["Target"];
		
		// resolve value to use for hotlink
		$wa = $colSpec["WhichArg"];		
		$ref = $value;
		if($wa != "") {
			switch($wa) {
				case "value":
					break;
				default:
					$ref = $row[$wa];
					break;
			}
		}
		
		// tooltip?
		$tool_tip = '';
		if(array_key_exists('ToolTip', $colSpec) && $colSpec['ToolTip'] ) {
			$tool_tip = "title='".$colSpec['ToolTip']."'";
		}

		// render the hotlink
		switch($colSpec["LinkType"]) {
			case "invoke_entity":
				// look for conditions on link
				// Supported condition is GreaterOrEqual
				$noLink = $this->evaulate_conditional($colSpec, $ref, $value);
				if($noLink) {
					$str .= "<td>$value</td>";
				} else {
					// place target substitution marker 
					// (and preserve special primary filter characters)
					if(strpos($target, '@') === FALSE) {
						$sep = (substr($target, -1) == '~')?'':'/';
						$target .= $sep.'@';
					}
					$url = reduce_double_slashes(site_url().str_replace('@', $ref, $target));
					$str .= "<td><a href='$url' $tool_tip>$value</a></td>";
				}
				break;
			case "invoke_multi_col":
				$cols = (array_key_exists('Options', $colSpec))?$colSpec['Options']:array();
				foreach($cols as $col => $v) {
					if($v) {
						$cols[$col] = $col;
					} else {
						$cols[$col] = $row[$col];
					}
				}
				$ref = implode('/', array_values($cols));
				$url = reduce_double_slashes(site_url()."$target/$ref");
				$str .= "<td><a href='$url' $tool_tip>$value</a></td>";
				break;
			case "literal_link":
				$url = $target.$ref;
				$str .= "<td><a href='$url' target='External$colIndex' $tool_tip>$value</a></td>";
				break;
			case "masked_link":
				$url = $target.$ref;
				if($url) {
					$lbl = $this->getOptionValue($colSpec, 'Label', 'Undefined_masked_link');
					$str .= "<td><a href='$url' target='External$colIndex' $tool_tip>$lbl</a></td>";
				} else {
					$str .= "<td></td>";					
				}
				break;
			case "CHECKBOX":
				// $str .= "<td>" . form_checkbox('ckbx', $ref, FALSE) . "</td>";
				$str .= "<td><input type='checkbox' value='$ref' name='ckbx' class='lr_ckbx'></td>";
				break;
			case "checkbox_json":
				// This is an old, unused mode
				$cols = (array_key_exists('Options', $colSpec))?$colSpec['Options']:array();
				foreach($cols as $col => $v) {
					$cols[$col] = $row[$col];
				}
				$ref = implode('|', array_values($cols));
				$str .= "<td><input type='checkbox' value='$ref' name='ckbx' class='lr_ckbx'></td>";
				break;

			case "update_opener":
				$str .= "<td>" . "<a href='javascript:opener.epsilon.updateFieldValueFromChooser(\"" . $ref . "\", \"replace\")' >" . $value . "</a>" . "</td>";
				break;
			case "color_label":
				if(array_key_exists($ref, $colSpec["cond"])) {
					$colorStyle = "class='".$colSpec['cond'][$ref]."'";
				} else {
					$colorStyle = "";					
				}
				$str .= "<td $colorStyle >$value</td>";
				break;
			case "bifold_choice":
				// This mode has been superseded by select_case
				$t = $colSpec['Options'];
				$target = ($ref == $target)?$t[0]:$t[1];
				$url = reduce_double_slashes(site_url()."$target/show/$value");
				$str .= "<td><a href='$url'>$value</a></td>";
				break;
			case "format_commas":
				$matches = array();
				if(is_numeric($value)) {
					$valueNum = floatval($value);
					$decimals = $this->getOptionValue($colSpec, 'Decimals', '0');
					$value = number_format($valueNum, $decimals);
				}
				$str .= "<td>" . $value . "</td>";
				break;				
			case "select_case":
				$t = $colSpec['Options'];
				$link_item = ($target) ? $row[$target] : $value ;
				if(array_key_exists($ref, $t)) {
					$link_base = $t[$ref];
					$url = reduce_double_slashes(site_url()."$link_base/show/$link_item");
					$str .= "<td><a href='$url'>$value</a></td>";
				} else {
					$str .= "<td>$value</td>";
				}
				break;
			case "copy_from":
				// Old, unused mode; superseded by "row_to_json" and "row_to_url"
				$url = reduce_double_slashes(site_url()."$target/$ref");
				$str .= "<td><a href='$url'>$value</a></td>";
				break;
			case "row_to_url":
				$s = "";
				foreach($row as $f => $v) {
					$s.= ($s)?'|':'';
					$s .= "$f@$v";
				}
				$url = reduce_double_slashes(site_url()."$target");
				$str .= "<td><a href='javascript:void(0)' onclick='submitDynamicForm(\"$url\", \"$s\")'>$value</a></td>";
				break;
			case "row_to_json":
				$fsp = "";
				$rowAction = 'localRowAction';
				if(array_key_exists('Options', $colSpec)) {
					$rowAction = $this->getOptionValue($colSpec, 'rowAction', $rowAction);
					if(array_key_exists('fields', $colSpec['Options'])) {
						$fsp = ', "' . $colSpec['Options']['fields'] . '"';
					}
				}
				foreach(array_keys($row) as $k) {if($row[$k] == null) {$row[$k] = ''; }}
				$s = json_encode($row);
				$url = reduce_double_slashes(site_url()."$target");
				$str .= "<td><a href='javascript:void(0)' onclick='$rowAction(\"$url\", \"$ref\", $s $fsp)'>$value</a></td>";				
				break;
			case "masked_href-folder":
				$lbl = $this->getOptionValue($colSpec, 'Label', 'Undefined_masked_href-folder');
				$lnk = str_replace('\\', '/', $ref);
				if($lnk) {
					$str = "<td>" . "<a href='file:///$lnk'>$lbl</a>" . "</td>";					
				} else {
					$str = "<td></td>";										
				}
				break;
			case "href-folder":
				$lnk = str_replace('\\', '/', $ref);
				$str = "<td>" . "<a href='file:///$lnk'>$value</a>" . "</td>";
				break;
			case "inplace_edit":
				$cn = str_replace(' ', '_', $col_name);
				$id = $cn . '_' . $ref;
				$str .= "<td><input class='$cn' id='$id' name='$ref' value='$value' /></td>";
				break;

			case "link_list":
				$matches = array();
				$delim = (preg_match('/[,;]/', $ref, $matches)) ? $matches[0] : '';
				$flds = ($delim == '') ? array($ref) : explode($delim, $ref);
				$links = array();
				foreach($flds as $ln) {
					$ln = trim($ln);
					$url = strncasecmp($ln, "http", 4)? site_url().$target.'/'.$ln: $ln;
					$links[] = "<a href='$url'>$ln</a>";
				}
				$str .= "<td>" . implode($delim.' ', $links) . "</td>";
				break;
			case "markup":
				$str .= "<td>" . nl2br($value) . "</td>";
				break;
			case "min_col_width":
				// No special rendering here, though get_cell_padding will pad the cell if the text is too short
				$str .= "<td>" . $value . "</td>";
				break;
			case "image_link":
				$url = $ref;
				$link_url = $url;
				if($target) {
					$url_parts = explode('/', $ref);
					$last_seg = count($url_parts) - 1;
					$url_parts[$last_seg] = $target;
					$link_url = implode("/", $url_parts);
				}
				$width = $this->getOptionValue($colSpec, 'width', '250');
				if($url) {
					$str .= "<td><a href='$link_url'><img src='$url' width='$width' border='0'></a></td>";
				} else {
					$str .= "<td></td>";					
				}
				break;
			case "column_tooltip":
				// No special rendering
				$str .= "<td>" . $value . "</td>";
				break;
			default:
				$str .= "<td>???" . $colSpec["LinkType"] . "???</td>";
				break;
		}
		return $str;
	}
	
	// -----------------------------------
	// 
	function evaulate_conditional($colSpec, $ref, $value)
	{
		$noLink = false;
		if(array_key_exists('Options', $colSpec)) {
			$test = $this->getOptionValue($colSpec, 'GreaterOrEqual');
			$options = $colSpec['Options'];
			if(!empty($test)) {
				if($value < $test) {
					$noLink = true;
				}
			}
			// more conditionals here when needed
		}
		return $noLink;
	}
	
	/**
	 * Create HTML to display a set of column headers
	 * @param type $rows
	 * @param type $sorting_cols
	 * @return string
	 */
	function make_column_header($rows, $sorting_cols = array())
	{	
		if(empty($rows)) {
			return '';
		}
		$str = "";
		
		// which columns are showing
		$display_cols = $this->get_display_cols(array_keys(current($rows)));
		
		// get array of col sort makers
		$col_sort = $this->get_column_sort_markers($sorting_cols);

		foreach($display_cols as $col_name){
			if($col_name[0] != '#') { // do not show columns with names that begin with hash
				// sorting marker
				$marker = $this->get_column_sort_marker($col_name, $col_sort);
				
				// Check for a column header tooltip
				$toolTip = $this->get_column_tooltip($col_name);
				if($toolTip) {
					$toolTip = 'title="' . $toolTip . '"';
					$str .= '<th style="background-color:#C2E7F6;">';
				} else {
					$toolTip = '';
					$str .= '<th>';					
				}
					
				// make header label
				$str .= $marker;
				$str .= "<a href='javascript:void(0)' onclick='lambda.setColSort(\"$col_name\")'  class='col_header' " . $toolTip . ">$col_name</a>";
				$str .= $this->get_cell_padding($col_name);
				$str .= "</th>";
			}
		}
		return "<tr>".$str."</tr>";
	}

	// -----------------------------------
	private
	function get_column_sort_marker($col_name, $col_sort)
	{
		$marker = '';
		if(array_key_exists($col_name, $col_sort)) {
			$arrow = 'arrow_' . $col_sort[$col_name]->dir . $col_sort[$col_name]->precedence . '.png';
			$marker = "<img src='" . base_url(). "/images/$arrow' border='0' >";
		}
		return $marker;
	}
	
	/**
	 * Return an array containing columns that will be used for
	 * sorting and info about their precedence and direction.
	 * accepts sorting column information in two different formats
	 * and produces a common output format
	 * @param type $sorting_cols
	 * @return \stdClass
	 */
	private
	function get_column_sort_markers($sorting_cols)
	{
		$col_sort = array();
		$sorting_precedence = 1;
		foreach($sorting_cols as $obj) {
			if(is_object($obj)) { // query parts sorting spec format
				$sort_marker = new stdClass();
				$sort_marker->precedence = $sorting_precedence++;
				$sort_marker->dir = ($obj->dir == 'ASC')?'up':'down';
				$col_sort[$obj->col] = $sort_marker;
			} else
			if(is_array($obj)) { // raw sorting filter format
				$sort_marker = new stdClass();
				$sort_marker->precedence = $sorting_precedence++;
				$sort_marker->dir = ($obj['qf_sort_dir'] == 'ASC')?'up':'down';
				$col_sort[$obj['qf_sort_col']] = $sort_marker;
			}
		}
		return $col_sort;
	}
	
	// -----------------------------------
	private
	function get_cell_padding($col_name)
	{
		$padding = '';
		if(array_key_exists($col_name, $this->hotlinks)) {
			$colSpec = $this->hotlinks[$col_name];
			if($colSpec["LinkType"] == 'min_col_width') {
				$min_width = $colSpec["Target"];
				$len = strlen($col_name);
				if($len < $min_width) {
					$padding = str_repeat("&nbsp;", $min_width - $len);
				}
			}
		}
		return $padding;
	}

	// --------------------------------------------------------------------
	private
	function get_column_tooltip($col_name)
	{
		$toolTip = '';
		if(array_key_exists($col_name, $this->hotlinks)) {
			$colSpec = $this->hotlinks[$col_name];
			if($colSpec["LinkType"] == 'column_tooltip') {
				$toolTip = $colSpec["Target"];
			}
		}
		return $toolTip;
	}
	
	/**
	 * Update the date columns to have user-friendly dates
	 * @param type $result
	 * @param type $col_info
	 * @return type
	 */
	function fix_datetime_display(&$result, $col_info)
	{
		// get list of datetime columns
		$dc = array();
		foreach($col_info as $f) {
			if($f->type=='datetime') {
				$dc[] = $f->name;
			}
		}
		
		if(count($dc)==0) {
			// none - we are done
			return;
		}

		// traverse the array of rows, and fix the datetime colum formats
		//
		// get the date display format from global preferences
		$CI =& get_instance();
		$CI->load->model('dms_preferences', 'preferences');
		$format = $CI->preferences->get_date_format_string();

		// traverse all the rows in the reslut
		for($i=0;$i<count($result);$i++) {
			// traverse all the date columns in the current row
			foreach($dc as $col) {
				// skip if the column value is empty
				if(!isset($result[$i][$col])) {
					continue;
				}
				
				// convert to blank if column value is null
				if(is_null($result[$i][$col])) {
					$result[$i][$col] = '';
				} else {
					// convert original date string to date object
					// and then convert that to desired display format.
					// mark display if original format could not be parsed.
					$dt = strtotime($result[$i][$col]);
					if($dt) {
						$result[$i][$col] = date($format, $dt);
					} else {
						$result[$i][$col] = "??".$result[$i][$col];
					}
				}
			}
		}
	}
	
	/**
	 * Look for item $itemName in the Options array in $colSpec
	 * If found, return its value, otherwise return $valueIfMissing
	 * @param type $colSpec
	 * @param string $itemName
	 * @param string $valueIfMissing
	 * @return type
	 */
	private
	function getOptionValue($colSpec, $itemName, $valueIfMissing = "") {
		if (array_key_exists('Options', $colSpec)) {
			$options = $colSpec['Options'];
			if ($options != null &&
				is_array($colSpec['Options']) && 
				array_key_exists($itemName, $colSpec['Options'])) {
				return $colSpec['Options'][$itemName];
			}
		}
		return $valueIfMissing;
	}
	
	// --------------------------------------------------------------------
	function set_col_filter($col_filter)
	{
		$this->col_filter = $col_filter;
	}
	
}
