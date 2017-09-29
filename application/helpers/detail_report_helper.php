<?php  
	if (!defined('BASEPATH')) {
		exit('No direct script access allowed');
	}

/**
 * Create HTML to display detail report fields, including adding hotlinks
 * Also adds the header fields, including the link to the list report and edit/entry buttons
 * This method is called from view application/views/main/detail_report_data.php
 * That view is loaded from method show_data in Base_controller.php
 * @param type $columns
 * @param type $fields
 * @param type $hotlinks
 * @param type $controller_name
 * @param type $id
 * @param type $show_entry_links
 * @return string
 */
function make_detail_report_section($columns, $fields, $hotlinks, $controller_name, $id, $show_entry_links=TRUE)
{
	$str = '';
	// fields are contained in a table
	$str .= "\n<table class='DRep' >\n";

	$str .= "<tr>";
 	$str .= "<th align='left'>";
	$str .= "<a title='Back to the list report' href='../report'><img src='" . base_url(). "/images/page_white_get.png' border='0' ></img></a>";	
 	$str .= "</th>";
	$str .= "<th>";
	
	if($show_entry_links) {
	 	$str .= make_detail_report_edit_links($controller_name, $id);
	}
 	$str .= "</th>";
	$str .= "</tr>";

	$str .= "<tr>";
	$str .= "<th>Parameter</th>";
	$str .= "<th>Value</th>";
	$str .= "</tr>";

	$str .= make_detail_table_data_rows($columns, $fields, $hotlinks);

	$str .= "</table>\n";
	return $str;
}

/**
 * Convert the rows of data into html, including formatting datetime values and adding hotlinks
 * @param type $columns
 * @param type $fields
 * @param type $hotlinks
 * @return string
 */
function make_detail_table_data_rows($columns, $fields, $hotlinks)
{
	$str = "";
	$colIndex = 0;

	$dc = array();

	// Look for any datetime columns
	foreach ($columns as $column) {
		if($column->type=='datetime') {
			$dc[] = $column->name;
		}
	}
	
	// Show dates/times in the form: Dec 5 2016 5:44 PM
	$dateFormat = "M j Y g:i A";

	$pathCopyData = array();
	$pathCopyButtonCount = 0;
	
	// make a form field for each field in the field specs
	foreach ($fields as $f_name => $f_val) {
		// don't display columns that begin with a hash character
		if($f_name[0] == '#') {
			continue;
		}

		// default field display for table
		$label = $f_name;
		$val = $f_val;		
		
		if (!is_null($f_val) && in_array($f_name, $dc)) {
			// Convert original date string to date object
			// then convert that to the desired display format.
			$dt = strtotime($f_val);
			if($dt) {
				$val = date($dateFormat, $dt);
			}
		}
			
		$label_display = "<td>$label</td>\n";
		
		// We will append </td> below
		$val_display = "<td>$val";
		
		// override default field display with hotlinks
		$hotlink_specs = get_hotlink_specs_for_field($f_name, $hotlinks);
		foreach($hotlink_specs as $hotlink_spec) {
			if (array_key_exists("WhichArg", $hotlink_spec) && strlen($hotlink_spec["WhichArg"]) > 0){
				$link_id = $fields[$hotlink_spec["WhichArg"]];
			} else {
				$link_id = "";
			}
			
			if($hotlink_spec['Placement'] == 'labelCol') {
				$label_display = make_detail_report_hotlink($hotlink_spec, $link_id, $colIndex, $f_name, $val);
			} else {
				$val_display = make_detail_report_hotlink($hotlink_spec, $link_id, $colIndex, $val);
			}
		}

		// open row in table
		$rowColor = alternator('ReportEvenRow', 'ReportOddRow');
		$str .= "<tr class='$rowColor' >\n";

		// Check whether the value points to a shared folder on a window server
		$charIndex = strpos($val, "\\\\");
		
		if ($charIndex !== false)
		{
			$pathCopyButtonCount++;

			// Note: Copy functionality is implemented in clipboard.min.js
			// More info at https://www.npmjs.com/package/clipboard-js
			// and at       https://github.com/lgarron/clipboard.js
			
			$buttonHtml = "<button id='copy-data-button$pathCopyButtonCount' class='copypath_btn'>Copy</button>";

			$val_display .= " " . $buttonHtml;

			$folderPath = str_replace("\\", "\\\\", substr($val, $charIndex));
			
			$pathCopyData[$pathCopyButtonCount] = $folderPath;
		}
		
		// first column in table is field name
		// second column in table is field value, possibly with special formatting
		$str .= $label_display . $val_display . "</td>\n";

		// close row in table
		$str .= "</tr>\n";
		
		$colIndex++;
	}
	
	if (sizeof($pathCopyData) > 0)
	{	
		$scriptData = "\n<script>\n";    // Or "<p>\n";
		
		foreach ($pathCopyData as $key => $value)
		{
			// Attach code to the JQuery dialog's .on("click") method (synonymous with .click())
			$scriptData .= '$("#copy-data-button' . $key . '").on("click",function(e) {';
			$scriptData .= "    clipboard.copy({ 'text/plain': '$value' }); ";
			$scriptData .= "    console.log('success: copy-data-button$key'); ";
			$scriptData .= "  });\n";
				
			/*
			 * Alternative approach, using .getElementById
			 * and a Javascript promise
			 *
				$scriptData .= "document.getElementById('copy-data-button$key').addEventListener('click', function() {";
				$scriptData .= "  clipboard.copy({\n";
				$scriptData .= "    'text/plain': '$value',\n";
				// $scriptData .= "    'text/html': '$value'\n";
				$scriptData .= "  }).then(\n";
				$scriptData .= "    function(){console.log('success'); },\n";
				$scriptData .= "    function(err){console.log('failure', err);\n";
				$scriptData .= "  });\n";
				$scriptData .= "});\n";
			*/
		}
		
		$scriptData .= "</script>\n";    // Or "</p>\n";
						
		$str .= $scriptData;

	}
	
	return $str;
}
// -----------------------------------
//
function get_hotlink_specs_for_field($f_name, $hotlinks) 
{
	// List of any hotlink spec(s) for the field
	$hotlink_specs = array();
	
	// Is a primary hotlink defined for the field?
	if(array_key_exists($f_name, $hotlinks)) {
		$hotlink_specs[] = $hotlinks[$f_name];
	}
	
	// Is a secondary hotlink defined for field?
	if(array_key_exists('+'.$f_name, $hotlinks)) {
		$hotlink_specs[] = $hotlinks['+'.$f_name];
	}
	
	return $hotlink_specs;
}
// -----------------------------------
//
function make_detail_report_hotlink($spec, $link_id, $colIndex, $display, $val='')
{
	$str = "";
	$fld_id = $spec["id"];
	if (array_key_exists("WhichArg", $spec)) {
		$wa = $spec["WhichArg"];
	} else {
		$wa = "";
	}	
	$type = $spec['LinkType'];
	$target = $spec['Target'];
	$options = $spec['Options'];
	$cell_class = "";

	switch($type) {
		case "detail-report":
			// Link to another DMS page, including both list reports and detail reports
			$url = make_detail_report_url($target, $link_id, $options);
			$str = "<a id='lnk_${fld_id}' href='$url'>$display</a>";
			break;		
		case "href-folder":
			if ($val) {
				$lnk = str_replace('\\', '/', $val);
				$str = "<a href='file:///$lnk'>$display</a>";
			}
			else {
				$str = $display;
			}
			break;
		case "literal_link":
			$str .= "<a href='$display' target='External$colIndex'>$display</a>";
			break;
		case "masked_link":
			if($display) {
				$lbl = "(label is not defined)";
				if (!empty($options) && array_key_exists('Label', $options)) {
					$lbl = $options['Label'];
				}
				$str .= "<a href='$display' target='External$colIndex'>$lbl</a>";
			} else {
				$str .= "";
			}
			break;
		case "link_list":
			// Create a separate hotlink for each item in a semi-colon list of items
			// The link to use is defined by the target column in the detail_report_hotlinks section of the config DB
			$matches = array();
			$delim = (preg_match('/[,;]/', $display, $matches))? $matches[0] : '';
			$flds = ($delim == '')? array($display) : explode($delim, $display);
			$links = array();
			foreach($flds as $ln) {
				$ln = trim($ln);
				$renderHTTP=TRUE;
				$url = make_detail_report_url($target, $ln, $options, $renderHTTP);
				$links[] = "<a href='$url'>$ln</a>";
			}
			$str .= implode($delim.' ', $links);
			break;
		case "link_table":
			// Table with links
			$str .= "<table class='inner_table'>";
			foreach(explode(',', $display) as $ln) {
				$ln = trim($ln);
				$renderHTTP=TRUE;
				$url = make_detail_report_url($target, $ln, $options, $renderHTTP);
				$str .= "<tr><td><a href='$url'>$ln</a></td></tr>";
			}
			$str .= "</table>";
			break;
		case "tablular_list":
		case "tabular_list":
			// Parse data separated by colons and vertical bars and create a table
			// Row1_Name:Row1_Value|Row2_Name:Row2_Value|
			$str .= "<table class='inner_table'>";
			foreach(explode('|', $display) as $ln) {
				$str .= '<tr>';
				foreach(explode(':', $ln) as $f) {
					$str .= '<td>'.trim($f).'</td>';
				}
				$str .= '</tr>';
			}
			$str .= "</table>";
			break;
		case "tabular_link_list":
			// Parse data separated by colons and vertical bars and create a table
			// Values in the second column are linked to the page defined by the target column in the detail_report_hotlinks section of the config DB
			// Row1_Name:Row1_Value|Row2_Name:Row2_Value|
			//
			// Row1_Value will link to the given target
			$str .= "<table class='inner_table'>";
			foreach(explode('|', $display) as $ln) {
				$str .= '<tr>';				
				$rowColNum = 0;
				foreach(explode(':', $ln) as $f) {
					$rowColNum += 1;
					if ($rowColNum == 2) {
						$trimmedValue = trim($f);
						$renderHTTP=TRUE;
						$url = make_detail_report_url($target, $trimmedValue, $options, $renderHTTP);
						$str .= '<td>' . "<a href='$url'>$trimmedValue</a>" . '</td>';
					} else {
						$str .= '<td>' . trim($f) . '</td>';
					}
				}				
				$str .= '</tr>';
			}
			$str .= "</table>";
			break;
		case "color_label":
			$cx = "";
			if(!empty($options) && array_key_exists($link_id, $options)) {
				$cx = "class='" . $options[$link_id] ."' style='padding: 1px 5px 1px 5px;'";
			}
			$str .= "<span $cx>$display</span>";
			break;
		case "xml_params":
			$str .= make_table_from_param_xml($display);
			break;
		case "markup":
			// Replace newlines with <br> using nl2br
			$str .= nl2br($display);
			break;
		case "monomarkup":
			// Replace newlines with <br> using nl2br
			// Also surround the entire block with <code></code>
			// CSS formatting in base.css renders the text as monospace; see table.DRep pre
			$str .= '<code>' . nl2br($display) . '</code>';
			break;
		case "glossary_entry":
			$url = make_detail_report_url($target, $wa, $options);

			if(!empty($options) && array_key_exists('Label', $options)) {
				$linkTitle = "title='" . $options['Label'] . "'";
			} else {
				$linkTitle = "";
			}
			
			$str = "<a id='lnk_${fld_id}' target='_GlossaryEntry' " . $linkTitle . " href='$url'>$display</a>";
			
			// Pop-up option
			// $str = "<a id='lnk_${fld_id}' target='popup' href='$url'  onclick=\"window.open('$url','$display','width=800,height=600')\">$display</a>";
			
			break;		
		default:
			$str = "??? $display ???";
			break;
		
	}
	
	// The calling method will append </td>
	return "<td $cell_class>$str";
}

// -----------------------------------

function make_table_from_param_xml($xml)
{
	$dom = new DomDocument();
	$dom->loadXML('<root>'.$xml.'</root>');
	$xp = new domxpath($dom);
	$params = $xp->query("//Param");

	$s = '';
	$s .= "<table class='inner_table'>\n";
	$cur_section = '';
	foreach ($params as $param) {
		$name = $param->getAttribute('Name');
		$value = $param->getAttribute('Value');
		$section = $param->getAttribute('Section');
		if($section != $cur_section) {
			$cur_section = $section;
			$s .= "<tr><td colspan='2'><span style='font-weight:bold;'>$section</span></td></tr>\n";
		}
		$s .= "<tr><td>$name</td><td>$value</td></tr>\n";
	}		
	$s .= "</table>\n";
	return $s;
}


// -----------------------------------
// create HTML to display detail report edit links
//
function make_detail_report_edit_links($controller_name, $id)
{
	$str = '';
	$edit_url = site_url()."$controller_name/edit/$id";
	$copy_url = site_url()."$controller_name/create/$id";
	$new_url = site_url()."$controller_name/create";
	$str .= "<span><a id='btn_goto_edit_main' class='button' title='Edit this record' href='$edit_url' >Edit</a></span>";
	$str .= "<span><a id='btn_goto_copy_main' class='button' title='Copy this record' href='$copy_url' >Copy</a></span>";
	$str .= "<span><a id='btn_goto_create_main' class='button' title='Make new record' href='$new_url' >New</a></span>";
	return $str;
}
// -----------------------------------
// create HTML to display detail report aux info section
//
function make_detail_report_aux_info_section($result)
{
	$str = '';
	$str .= "<table class='DRep'>\n";
		$str .= "<tr>";
		$str .= "<th>Category</th>";
		$str .= "<th>Subcategory</th>";
		$str .= "<th>Item</th>"; 
		$str .= "<th>Value</th>"; 
		$str .= "</tr>\n";
	foreach($result as $row) {
		$rowColor = alternator('ReportEvenRow', 'ReportOddRow');
		$str .= "<tr class='$rowColor' >\n";
		$str .= "<td>".$row['Category']."</td>";
		$str .= "<td>".$row['Subcategory']."</td>";
		$str .= "<td>".$row['Item']."</td>"; 
		$str .= "<td>".$row['Value']."</td>"; 
		$str .= "</tr>\n";
	}
	$str .= "</table>\n";
	return $str;
}

// -----------------------------------
// create HTML for controls for displaying and editing aux info on detail report page
function make_detail_report_aux_info_controls($aux_info_target, $aux_info_id, $id)
{
		$js = "javascript:showAuxInfo(\"aux_info_container\", \"" . site_url(). "aux_info/show/"  .$aux_info_target . "/" . $aux_info_id."\")";
		$str = '';
		$str .= "Aux Info: |";
		$str .= "<span>";
		$str .= "<a href='$js'>Show...</a>";
		$str .= "</span>|";
		$str .= "<span>";
		$str .= "<a href='". site_url()."aux_info/entry/".$aux_info_target."/".$aux_info_id."/".$id. "'>Edit...</a>";	
		$str .= "</span>|";
		return $str;
}

/**
 * Create HTML to display detail report commands section
 * @param type $commands
 * @param type $tag
 * @param type $id
 * @return type
 */
function make_detail_report_commands($commands, $tag, $id)
{
	$cmds = array();
	foreach($commands as $label => $spec) {
		$target = $spec['Target'];
		$cmd = $spec['Command'];
		$tooltip = $spec['Tooltip'];

		// Message to show the user to confirm the action		
		$prompt = $spec['Prompt'];
		if (empty($prompt))
			$prompt = 'Are you sure that you want to update the database?';
 
		switch($spec['Type']) {
			case 'copy_from':
				$url =  site_url().$target . "/create/$tag/" . $id;
				$icon = cmd_link_icon("go");
				$cmds[] = "<a class='cmd_link_a' href='$url' title='$tooltip'>$label $icon</a>";
				break;
			case 'call':
				$url =  site_url().$target . "/$cmd/" . $id;
				$icon = cmd_link_icon("go");
				$cmds[] = "<a class='cmd_link_a' href='$url' title='$tooltip'>$label $icon</a>";
				break;
			case 'cmd_op':
				$url =  site_url().$target . "/command";
				$icon = cmd_link_icon();
				$cmds[] = "<a class='cmd_link_a' href='javascript:delta.performCommand(\"$url\", \"$id\", \"$cmd\", \"$prompt\")' title='$tooltip'>$label $icon</a>";
				break;
		}
	}
	$str = "";
	foreach($cmds as $cmd) {
		$str .= "<span class='cmd_link_cartouche'>$cmd</span>\n";
	}
	return $str;
}
// -----------------------------------
function make_detail_report_url($target, $link_id, $options, $renderHTTP=FALSE)
{

	if ($renderHTTP && strncasecmp($link_id, "http", 4) == 0) {
		$url = $link_id;
	}
	else {
	
		// Insert an @ sign if it is not already present
		// When constructing the URL, we will replace the @ sign in $target with $link_id
		if(strpos($target, '@') === FALSE) {
			// Need to add the @ sign
			// If $target does not end in ~, then add /
			$sep = (substr($target, -1) == '~') ? '' : '/';
			$targetNew = $target.$sep.'@';
		} else {
			$targetNew = $target;
		}

		if (!empty($options) && array_key_exists('RemoveRegEx', $options)) {
			$pattern = $options['RemoveRegEx'];
			if (!empty($pattern)) {
				$pattern = '/' . $pattern . '/';
				$link_id = preg_replace($pattern, "", $link_id);
			}
		}
		
		$url = reduce_double_slashes(site_url().str_replace('@', $link_id, $targetNew));				
	}
	
	return $url;
}				
// -----------------------------------
function make_export_links($entity, $id)
{
	// Example URLs:
	// http://dms2.pnl.gov/experiment/export_detail/QC_Shew_16_01/excel
	// http://dms2.pnl.gov/experiment/export_detail/QC_Shew_16_01/tsv
	// http://dms2.pnl.gov/experiment/export_spreadsheet/QC_Shew_16_01/data
	$s = '';
	$excel_lnk = site_url(). $entity . "/export_detail/" . $id . "/excel";
	$tsv_lnk   = site_url(). $entity . "/export_detail/" . $id . "/tsv";
	$spreadsheet_lnk = site_url(). $entity . "/export_spreadsheet/" . $id . "/data";
	
	$s .= "Download in other formats: ";
	$s .= "|<span><a href='$excel_lnk'>Excel</a></span>";
	$s .= "|<span><a href='$tsv_lnk'>Tab-Delimited Text</a></span>";
	$s .= "|<span><a href='$spreadsheet_lnk'>Spreadsheet Template</a></span>";
	$s .= "|";
	
	return $s;
}
// -----------------------------------
function make_message_box($message)
{
	//$style_sheet = base_url().'css/base.css';
	$class = (strripos($message, 'error') === false)?'EPag_message':'EPag_error';
	$s = '';
	$s .= "<div class='$class' style='width:40em;margin:20px;'>";
	$s .= $message;
	$s .= "</div>";
	return $s;
}
