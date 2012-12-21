<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/// -----------------------------------
// create HTML to display search form for DMS
//
function make_search_form($name = 'dms_search')
{
	$srchSel = $name."_sel";
	$srchVal = $name."_val";

	$str = '';
	$str = _make_search_handler($srchSel, $srchVal);
	$str .= _make_search_selector($srchSel, $srchVal);
	$str .= _make_search_input($srchSel, $srchVal);
	$str .= _make_search_command($srchSel, $srchVal);
	return $str;
}

// --------------------------------------------------------------------
function make_search_form_vertical($name = 'dms_search')
{
	$srchSel = $name."_sel";
	$srchVal = $name."_val";

	$str = '';
	$str = _make_search_handler($srchSel, $srchVal);
	$str .= "<span>" . _make_search_selector($srchSel, $srchVal) . "</span>\n";
	$str .= "<span>" . _make_search_input($srchSel, $srchVal) . "</span>\n";
	$str .= "<span>" ._make_search_command($srchSel, $srchVal) . "</span>\n";
	return $str;
}

// --------------------------------------------------------------------
function _make_search_selector($srchSel, $srchVal)
{
	$options = array(
	array('target' => 'Analysis Job', 'search_key' => 'By Job', 'link' => 'analysis_job/report/' ),
	array('target' => 'Analysis Job', 'search_key' => 'By Dataset', 'link' => 'analysis_job/report/-/-/-/' ),
	array('target' => 'Analysis Job', 'search_key' => 'By State', 'link' => 'analysis_job/report/-/' ),
	array('target' => 'Dataset', 'search_key' => 'By Name', 'link' => 'dataset/report/' ),
	array('target' => 'Dataset', 'search_key' => 'By ID', 'link' => 'dataset/report/-/' ),
	array('target' => 'Dataset', 'search_key' => 'By State', 'link' => 'dataset/report/-/-/' ),
	array('target' => 'Experiment', 'search_key' => 'By Name', 'link' => 'experiment/report/' ),
	array('target' => 'Experiment', 'search_key' => 'By Campaign', 'link' => 'experiment/report/-/' ),
	array('target' => 'Experiment', 'search_key' => 'By ID', 'link' => 'experiment/report/-/-/' ),
	array('target' => 'Campaign', 'search_key' => 'By Name', 'link' => 'campaign/report/' ),
	array('target' => 'Archive', 'search_key' => 'By Dataset', 'link' => 'archive/report/' ),
	array('target' => 'Archive', 'search_key' => 'By Dataset ID', 'link' => 'archive/report/-/' ),
	array('target' => 'Requested Run', 'search_key' => 'By ID', 'link' => 'requested_run/report/-/' ),
	array('target' => 'Requested Run', 'search_key' => 'By Name', 'link' => 'requested_run/report/' ),
	);	
	
	$str = '';
	$str .= "<select id='".$srchSel."' name='".$srchSel."' onchange='gamma.dms_search(\"$srchSel\", \"$srchVal\")' >\n";
	$str .= "<option value=''>Search for...</option>\n";
	$cur_target = '';
	foreach($options as $option) {
		$target = $option['target'];
		$search_key = $option['search_key'];
		$link = site_url(). $option['link'];
		if($target != $cur_target) {
			if($cur_target != '') {
				$str .= "</optgroup>\n";
			}
			$cur_target = $target;
			$str .= "<optgroup label='$target'>\n";
		}
		$str .= "<option value='$link'>$search_key</option>\n";
	}
	$str .= "</optgroup>\n";
	$str .= "</select>\n";

	return $str;
}

// --------------------------------------------------------------------
function _make_search_input($srchSel, $srchVal)
{
	$str = '';
	$str .= "<input type='text' id='".$srchVal."' size='18' maxlength='80' value=''  onkeypress='enter_$srchSel(event)' >\n";
	return $str;
}

// --------------------------------------------------------------------
function _make_search_command($srchSel, $srchVal)
{
	$str = '';
	$str .= "<a title='Perform selected search' href='javascript:gamma.dms_search(\"$srchSel\", \"$srchVal\")'>Go</a>\n";
	return $str;
}

// --------------------------------------------------------------------
function _make_search_handler($srchSel, $srchVal)
{
$func = <<<EOD
<script type="text/javascript">
function enter_$srchSel(e) {
	var code;
	if (!e) var e = window.event;
	if (e.keyCode) code = e.keyCode;
	else if (e.which) code = e.which;
	if(code == 13) {
		gamma.dms_search("$srchSel", "$srchVal"); 
		return false;
	}
   return true;
}
</script>
EOD;
	return $func;
}


?>
