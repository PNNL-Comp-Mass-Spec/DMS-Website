<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// -----------------------------------
// create HTML to display detail report fields
//
function make_detail_report_section($fields, $hotlinks, $controller_name, $id, $show_entry_links=TRUE)
{
	$str = '';
	// fields are contained in a table
	$str .= "\n<table class='DRep' >\n";

	$str .= "<tr>";
	$str .= "<th colspan='2'>";
	$str .= "<h2><a class=help_link title='This section shows the tracking information for the Analysis Job Request'>Tracking Information</a></h2>";
	if($show_entry_links) {
	 	$str .= make_detail_report_edit_links($controller_name, $id);
	}
 	$str .= "</th>";
	$str .= "</tr>";

	$str .= "<tr>";
	$str .= "<th>Parameter</th>";
	$str .= "<th>Value</th>";
	$str .= "</tr>";

	// make a form field for each field in the field specs
	foreach ($fields as $f_name => $f_val) {
		$label = $f_name;
		$val = $f_val;
		
		// primary hotlink for field
		if(isset($hotlinks[$f_name])) {
			$link_id = $fields[$hotlinks[$f_name]["WhichArg"]];
			if($hotlinks[$f_name]['Placement'] == 'labelCol') {
				$label = make_detail_report_hotlink($hotlinks[$f_name], $link_id, $f_name, $val);
			} else {
				$val = make_detail_report_hotlink($hotlinks[$f_name], $link_id, $val);				
			}
		}

		// secondary hotlink for field
		$pf_name = '+'.$f_name;
		if(isset($hotlinks[$pf_name])) {
			$link_id = $fields[$hotlinks[$pf_name]["WhichArg"]];
			if($hotlinks[$pf_name]['Placement'] == 'labelCol') {
				$label = make_detail_report_hotlink($hotlinks[$pf_name], $link_id, $f_name, $val);
			} else {
				$val = make_detail_report_hotlink($hotlinks[$pf_name], $link_id, $val);				
			}
		}
		
		// open row in table
		$rowColor = alternator('ReportEvenRow', 'ReportOddRow');
		$str .= "<tr class='$rowColor' >\n";

		// first column in table is field name
		$str .= "<td>" . $label . "</td>\n"; 
		// second column in table is field value
		$str .= "<td>" . $val . "</td>\n"; 

		// close row in table
		$str .= "</tr>\n";
	}
	$str .= "</table>\n";
	return $str;
}

// -----------------------------------
//
function make_detail_report_hotlink($spec, $link_id, $display, $val='')
{
	$str = "";
	$fld_id = $spec["id"];
	$wa = $spec["WhichArg"];
	$type = $spec['LinkType'];
	$target = $spec['Target'];
	switch($type) {
		case "detail-report":
			if(strpos($target, '@') === FALSE) {
				$sep = (substr($target, -1) == '~')?'':'/';
				$target .= $sep.'@';
			}
			$url = reduce_double_slashes(site_url().str_replace('@', $link_id, $target));
			$str = "<a id='lnk_${fld_id}' href='$url'>$display</a>";
			break;		
		case "href-folder":
			$lnk = str_replace('\\', '/', ($val=='')?$display:$val);
			$str = "<a href='file:///$lnk'>$display</a>";
			break;
		case "literal_link":
			$str .= "<a href='$display'>$display</a>";
			break;
		case "link_list":
			$delim = (preg_match('/[,;]/', $display, $match))? $match[0] : '';
			$flds = ($delim == '')? array($display) : explode($delim, $display);
			$links = array();
			foreach($flds as $ln) {
				$ln = trim($ln);
				$url = strncasecmp($ln, "http", 4)? site_url().$target.'/'.$ln: $ln;
				$links[] = "<a href='$url'>$ln</a>";
			}
			$str .= implode($delim.' ', $links);
			break;
		case "link_table":
			$str .= "<table class='inner_table'>";
			foreach(explode(',', $display) as $ln) {
				$ln = trim($ln);
				$url = strncasecmp($ln, "http", 4)? site_url().$target.'/'.$ln: $ln;
				$str .= "<tr><td><a href='$url'>$ln</a></td></tr>";
			}
			$str .= "</table>";
			break;
		case "tablular_list":
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
		case "xml_params":
			$str .= make_table_from_param_xml($display);
			break;
		case "markup":
			$str .= nl2br($display);
			break;
		default:
			$str = "??? $display ???";
			break;
		
	};
	return $str; 
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
	$str .= "<span><a id='btn_goto_edit_main' title='Edit this record' href='$edit_url' >Edit</a></span>";
	$str .= "<span><a id='btn_goto_copy_main' title='Copy this record' href='$copy_url' >Copy</a></span>";
	$str .= "<span><a id='btn_goto_create_main' title='Make new record' href='$new_url' >New</a></span>";
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

// -----------------------------------
// create HTML to display detail report commands section
//
function make_detail_report_commands($commands, $tag, $id)
{
	$str = "";
	foreach($commands as $label => $spec) {
		$target = $spec['Target'];
		$cmd = $spec['Command'];
		$tooltip = $spec['Tooltip'];
		switch($spec['Type']) {
			case 'copy_from':
				$url =  site_url().$target . "/create/$tag/" . $id;
				$icon = cmd_link_icon("go");
				$str .= "$label<<a href='$url' title='$tooltip'>$icon/a>";
				break;
			case 'call':
				$url =  site_url().$target . "/$cmd/" . $id;
				$icon = cmd_link_icon("go");
				$str .= "$label<a href='$url' title='$tooltip'>$icon</a>";
				break;
			case 'cmd_op':
				$url =  site_url().$target . "/command";
				$icon = cmd_link_icon();
				$str .= "$label<a href='javascript:delta.performCommand(\"$url\", \"$id\", \"$cmd\")' title='$tooltip'>$icon</a>";
				break;
		}
		$str = "<span class='cmd_link_cartouche'>$str</span>";
	}
	return $str;
}
// -----------------------------------
function make_export_links($entity, $id)
{
	// http://dmsdev.pnl.gov/experiment/export_detail/Rifle_20_04/excel
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
	$style_sheet = base_url().'css/base.css';
	$class = (strripos($message, 'error') === false)?'EPag_message':'EPag_error';
	$s = '';
	$s .= "<div class='$class' style='width:40em;margin:20px;'>";
	$s .= $message;
	$s .= "</div>";
	return $s;
}
?>
