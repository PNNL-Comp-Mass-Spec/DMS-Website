<?php  
	if (!defined('BASEPATH')) {
		exit('No direct script access allowed');
	}


	// --------------------------------------------------------------------
	// NOTE: code is adapted from http://codeigniter.com/wiki/Excel_Plugin/
	//
	function export_to_excel($result, $filename='excel_download', $col_filter = array())
	{
		$cols = array_keys(current($result));
		if(!empty($col_filter)) {
			$cols = $col_filter;
		}
		
		$headers = implode("\t", fix_ID_column($cols));

		$data = get_tab_delimited_text($result, $cols);
		
		// Use a file extension of .tsv for tab-separated
		// Prior to June 2016 we used .xls but that's not an accurate file extension given the actual data
		header("Content-type: application/x-msdownload");
		header("Content-Disposition: attachment; filename=$filename.tsv");
		echo "$headers\n$data";
	}
	// --------------------------------------------------------------------
	//
	function export_to_tab_delimited_text($result, $filename='tsv_download', $col_filter = array())
	{
		$cols = array_keys(current($result));
		if(!empty($col_filter)) {
			$cols = $col_filter;
		}
		
		$headers = implode("\t", fix_ID_column($cols));

		$data = get_tab_delimited_text($result, $cols);
		
		header("Content-type: text/plain");
		header("Content-Disposition: attachment; filename=$filename.txt");
		echo "$headers\n$data";
	}
	// --------------------------------------------------------------------
	//
	function get_tab_delimited_text($result, $cols)
	{

		$data = '';
	
		// field data
		foreach($result as $row) {
			$line = '';
			foreach($cols as $name) {
				$value = $row[$name];
				if ((!isset($value)) OR ($value == "")) {
					 $value = "\t";
				}
				else {
					 $value = quote_if_contains_tab($value) . "\t";
				}		
				$line .= $value;
			}
			$data .= trim($line)."\n";
		}
	
		$dataNoCR = str_replace("\r","",$data);
		
		return $dataNoCR;
	}
	// --------------------------------------------------------------------
	//
	function export_detail_to_tab_delimited_text($result, $aux_info, $filename='tsv_download')
	{
		// detail report for tracking entity
		$data = '';
		$data .= "Parameter" . "\t" . "Value" . "\n";
		foreach($result as $name => $value) {
				if ((!isset($value)) OR ($value == "")) {
					 $value = "\t";
				} else {
					 $value = quote_if_contains_tab($value) . "\t";
				}	
				$data .= trim($name ."\t" . $value)."\n";
		}
	
		// detail report for aux info (if any)
		$ai = '';
		if(count($aux_info) > 0) {
			$fields = array("Category", "Subcategory", "Item", "Value");
			$ai .= "Category" . "\t" . "Subcategory" . "\t" . "Item" . "\t" . "Value" . "\n";
			foreach($aux_info as $row) {
				$line = '';
				foreach($fields as $field) {
					$value = $row[$field];
					if ((!isset($value)) OR ($value == "")) {
						 $value = "\t";
					} else {
						 $value = quote_if_contains_tab($value) . "\t";
					}	
					$line .= $value;			
				}
				$ai .= trim($line)."\n";
			}
		}
	
		header("Content-type: text/plain");
		header("Content-Disposition: attachment; filename=$filename.txt");
		echo $data;
		echo $ai;
	}
	// --------------------------------------------------------------------
	//
	function export_detail_to_excel($result, $aux_info, $filename='tsv_download')
	{
		// detail report for tracking entity
		$data = '';
		$data .= "Parameter" . "\t" . "Value" . "\n";
		foreach($result as $name => $value) {
				if ((!isset($value)) OR ($value == "")) {
					 $value = "\t";
				} else {
					 $value = quote_if_contains_tab($value) . "\t";
				}	
				$data .= trim($name ."\t" . $value)."\n";
		}
	
		// detail report for aux info (if any)
		$ai = '';
		if(count($aux_info) > 0) {
			$fields = array("Category", "Subcategory", "Item", "Value");
			$ai .= "Category" . "\t" . "Subcategory" . "\t" . "Item" . "\t" . "Value" . "\n";
			foreach($aux_info as $row) {
				$line = '';
				foreach($fields as $field) {
					$value = $row[$field];
					if ((!isset($value)) OR ($value == "")) {
						 $value = "\t";
					} else {
						 $value = quote_if_contains_tab($value) . "\t";
					}
					$line .= $value;			
				}
				$ai .= trim($line)."\n";
			}
		}
	
		// Use a file extension of .tsv for tab-separated
		// Prior to June 2016 we used .xls but that's not an accurate file extension given the actual data
		header("Content-type: application/x-msdownload");
		header("Content-Disposition: attachment; filename=$filename.tsv");
		echo $data;
		echo $ai;
	}

	// --------------------------------------------------------------------
	function export_xml_to_dot($scriptName, $description, $script)
	{
		// build contents of dot file
		$s = convert_script_to_dot($script);

		// set up file names
		$dir = "tmpfiles/";
		$fn = $dir.$scriptName.'.dot';
		$typ = "png";
		$fo = $dir.$scriptName.'.'.$typ;
		
		// create dot file
		file_put_contents($fn, $s);

		// generate graph image from dot file
		$output = shell_exec("dot -T$typ -o $fo $fn");
		echo "<pre>$output</pre>";
		
		// display graph image
		echo "<h2>Workflow for $scriptName Script</h2>";
		echo "<div style='width:60em;'>$description</div>";
		echo "<div style='height:1em;'></div>";
		echo '<img src="'.base_url().$fo.'" ></img>';		
	}
	
	// --------------------------------------------------------------------
	// converts a job script from XML to a dot grapic file
	function convert_script_to_dot($script) {
		$dom = new DomDocument();
		$dom->loadXML($script);
		$xp = new domxpath($dom);

		$dot_cmds = "digraph graphname {\n";
		$dot_cmds .= 'node [ shape = "record"  color=black fontname = "Verdana" fontsize = 10 ]';
		$dot_cmds .= 'edge [ color=black fontname = "Verdana" fontsize = 10 ]';
		$dot_cmds .= "\n";
		
		$steps = $xp->query("//Step");
		foreach ($steps as $step) {
			 $description = "";
			 $desc_items = $step->getElementsByTagName( "Description" );
			 if($desc_items->length > 0) {
				 $description = ($desc_items)?$desc_items->item(0)->nodeValue:"";
				 $description = str_replace("\n", "\\l", $description);
			 }
		     $step_number = $step->getAttribute('Number');
		     $tool = $step->getAttribute('Tool');
		     $special = $step->getAttribute('Special');
		     $shape = "box";
		     // $color = "black";
		     switch ($special) {
		          case "Clone":
		               $shape = "trapezium";
		               break;
		     }
		     //$dot_cmds .= "$step_number [label=\"$step_number $tool\"] [shape=$shape, color=$color]" . ";\n";
		     $dot_cmds .= "$step_number [label = \"{ $step_number $tool| $description }\"]";
		}
		
		$dependencies = $xp->query("//Depends_On");
		foreach ($dependencies as $dependency) {
		     $step_number = $dependency->parentNode->getAttribute('Number');
		     $target_step_number = $dependency->getAttribute('Step_Number');
		     $test = $dependency->getAttribute('Test');
		     // $value = $dependency->getAttribute('Value');
		     $enable_only = $dependency->getAttribute('Enable_Only');
		     $label = "";
		     $line = "";
		     if($test) {
		          $label = "[label=\"Skip if:$test\"]";
		     }    
		     if($enable_only) {
		          $line = " [style=dotted]";
		     }    
		     $dot_cmds .= "$target_step_number -> $step_number $label $line" . "\n";
		}
		$dot_cmds .= "}";
		return $dot_cmds;
	}

	// --------------------------------------------------------------------
	//
	function export_spreadsheet($entity, $result, $aux_info, $filename='tsv_download')
	{
		// detail report for tracking entity
		$data = strtoupper(str_replace('_', ' ', $entity)). "\n";
		$data .= "\n";
		$data .= "TRACKING INFORMATION" . "\n";
	
		foreach($result as $name => $value) {
				if ((!isset($value)) OR ($value == "")) {
					 $value = "\t";
				} else {
					 $value .= "\t";
				}	
				$data .= trim($name ."\t" . $value)."\n";
		}
	
		// detail report for aux info (if any)
		$ai = '';
		$ai .= "AUXILIARY INFORMATION" . "\n";
		$firstRow = True;
		$prevCategory = '';
		$prevSubCategory = '';
		if(count($aux_info) > 0) {
			// $fields = array("Category", "Subcategory", "Item", "Value");
			foreach($aux_info as $row) {
				$line = '';
	
				if (!$firstRow) {
					$prevCategory = $row['Category'];
					$prevSubCategory = $row['Subcategory'];
					$firstRow = False;
				}
	
				$rowCategory = fix_data($row['Category']);
				$rowSubCategory = fix_data($row['Subcategory']);
				$rowItem = fix_data($row['Item']);
				$rowValue = fix_data($row['Value']);
	
				if ($row['Category'] != $prevCategory) {
					$ai .= trim($rowCategory)."\n".trim($rowSubCategory)."\n".trim($rowItem)."\t".trim($rowValue)."\n";
				} else {
					if ($row['Subcategory'] != $prevSubCategory) {
						$ai .= trim($rowCategory)."\n".trim($rowSubCategory)."\n".trim($rowItem)."\t".trim($rowValue)."\n";
					} else {
						$ai .= trim($rowItem)."\t".trim($rowValue)."\n";
					}
				}
	
				$prevCategory = $row['Category'];
				$prevSubCategory = $row['Subcategory'];
	
			}
		}
	
		// Use a file extension of .tsv for tab-separated
		// Prior to June 2016 we used .xls but that's not an accurate file extension given the actual data
		header("Content-type: application/x-msdownload");
		header("Content-Disposition: attachment; filename=$filename.tsv");
		echo $data;
		echo $ai;
	}

	// --------------------------------------------------------------------
	function dump_spreadsheet($entity_info, $aux_info)
	{
		$CI = &get_instance();
		$CI->load->library('table');
		$CI->table->set_template(array ('table_open'  => '<table class="EPag">'));
		foreach($entity_info as $fld => $val) {
			$CI->table->add_row($fld, $val);
		}
		$ti = $CI->table->generate();
		$CI->table->clear();
		foreach($aux_info as $row) {
			$CI->table->add_row($row);
		}
		$ai = $CI->table->generate();
		$data['title'] = 'Spreadsheet Loader Template Contents';
		$data['content'] = $ti . $ai;
		$CI->load->vars($data);	
		$CI->load->view('basic');
	}
	
	// --------------------------------------------------------------------
	function fix_data($rowValue)
	{
		if ((!isset($rowValue)) OR ($rowValue == "")) {
			 $rowValue = "\t";
		} else {
			 $rowValue = quote_if_contains_tab($rowValue) . "\t";
		}
		return $rowValue;
	}

	// --------------------------------------------------------------------
	function fix_ID_column($cols) 
	{	
		// Make a copy of the $cols array
		$colsCopy = $cols;
		
		if (strtoupper(substr($colsCopy[0], 0, 2)) == "ID") {
			// The first column's name starts with ID
			// Excel will interpret this as meaning the file is an SYLK file (http://support.microsoft.com/kb/323626)
			// To avoid this, change the column name to Id
			$colsCopy[0] = 'Id';
		}

		return $colsCopy;
	}

	// --------------------------------------------------------------------
	// Surround $value with double quotes if it contains a tab character
	function quote_if_contains_tab($value)
	{
		// convert any newlines
		$valueNoCrLf = str_replace(array("\r\n", "\r", "\n"), "; ", $value);

		// Look for a tab character in $value
		$pos = strpos($valueNoCrLf, "\t");
		
		// Note that you must use !== instead of !=
		// See http://www.php.net/manual/en/function.strpos.php
		if ($pos !== false) {
			// Match found; surround with double quotes
			// However, first replace double quotes with ""			
			return '"' . str_replace('"', '""', $valueNoCrLf) . '"';
		}
		
		return $valueNoCrLf;
	}
