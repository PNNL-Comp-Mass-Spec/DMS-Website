<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

    // --------------------------------------------------------------------
    function make_suggested_sql_for_general_params($obj) {
        $s = <<<EOD
delete from general_params;
----
insert into general_params ("name","value") values ('base_table' , '$obj->tbl');
insert into general_params ("name","value") values ('list_report_data_table' , '$obj->lrn' );
insert into general_params ("name","value") values ('detail_report_data_table' , '$obj->drn');
insert into general_params ("name","value") values ('detail_report_data_id_col' , 'ID');
insert into general_params ("name","value") values ('entry_page_data_table' , '$obj->ern');
insert into general_params ("name","value") values ('entry_page_data_id_col' , 'ID');
insert into general_params ("name","value") values ('entry_sproc' , '$obj->spn');
EOD;
//insert into general_params ("name","value") values ('operations_sproc' , '$obj->upn');
//insert into general_params ("name","value") values ('my_db_group' , 'name of database connection [package, broker] (omit for connection to default DMS database)');
return $s;
}

	// --------------------------------------------------------------------
	// generate SQL to create basic database objects for a page family from
	// the given table.  This will include the three views and the AddUpdate sproc
	function make_family_sql($my_db, $gen_parms)
	{		
		$view_sql = "";
		$sa = array();
		
		$table = '';
		if(array_key_exists('base_table', $gen_parms)) {	
			$table = $gen_parms['base_table'];
		}
		if(!$table) {
			return "The 'base_table' parameter is not defined in config db table 'general_params'";
		}

		// read the definitions for the arguments for the given
		// table in the given database, do some conversions
		// from the raw format of the INFORMATION_SCHEMA, and save
		// to a local array
		$sql = "SELECT COLUMN_NAME, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$table."'";
		$result = $my_db->query($sql);
		if(!$result) {
			$str .=  "Couldn't get values from database.";
		} else {
			foreach($result->result_array() as $row) {
				$col = $row['COLUMN_NAME'];
				$arg = str_replace('_', '', $row['COLUMN_NAME']);
				$lbl = str_replace('_', ' ', $row['COLUMN_NAME']);
				$typ = $row['DATA_TYPE'];
				$siz = $row['CHARACTER_MAXIMUM_LENGTH'];
				$sa[] = array('argName' => $arg, 'colName' => $col, 'label' => $lbl, 'type' => $typ, 'size' => $siz);
			}
		}		

		if(array_key_exists('list_report_data_table', $gen_parms)) {			
			// make view
			$viewName = $gen_parms['list_report_data_table'];
			$sep = '';
			$view_sql .= "CREATE VIEW $viewName AS \n SELECT ";
			foreach($sa as $s) {
				$view_sql .= "{$sep}\n\t{$s['colName']} AS [{$s['label']}]";
				$sep =',';
			}
			$view_sql .= "\nFROM $table\n\n";
		} else {
			$view_sql .= "\n('list_report_data_table') not defined\n\n";
		}

		if(array_key_exists('detail_report_data_table', $gen_parms)) {
			// make view
			$viewName = $gen_parms['detail_report_data_table'];
			$sep = '';
			$view_sql .= "CREATE VIEW $viewName AS \n SELECT ";
			foreach($sa as $s) {
				$view_sql .= "{$sep}\n\t{$s['colName']} AS [{$s['label']}]";
				$sep =',';
			}
			$view_sql .= "\nFROM $table\n\n";
		} else {
			$view_sql .= "\n('detail_report_data_table') not defined\n\n";
		}

		if(array_key_exists('entry_page_data_table', $gen_parms)) {
			// make view
			$sep = '';
			$viewName = $gen_parms['entry_page_data_table'];
			$view_sql .= "CREATE VIEW $viewName AS \n SELECT ";
			foreach($sa as $s) {
				$view_sql .= "{$sep}\n\t{$s['colName']} AS {$s['argName']}";
				$sep =',';
			}
			$view_sql .= "\nFROM $table\n\n";
		} else {
			$view_sql .= "\n('entry_page_data_table') not defined\n\n";
		}

		$sprocName = '';
		if(array_key_exists('entry_sproc', $gen_parms)) {
			$sprocName = $gen_parms['entry_sproc'];
			$view_sql .= make_main_sproc_sql($sprocName, $table, $sa);	
		} else {
			$view_sql .= "\n('entry_sproc') not defined\n\n";
		}

		$sprocName = '';
		if(array_key_exists('operations_sproc', $gen_parms)) {
			$sprocName = $gen_parms['operations_sproc'];
			$view_sql .= make_operations_sproc_sql($sprocName, $table, $sa);	
		} else {
			$view_sql .= "\n('operations_sproc') not defined\n\n";
		}

	return $view_sql;
	}

	// --------------------------------------------------------------------
	function make_operations_sproc_sql($sprocName, $table)
	{
		$data['sprocName'] = $sprocName;
		$data['table'] = $table;

		$data['dt'] = date("m/d/Y");
		
		$CI =& get_instance();
		$body = $CI->load->view('config_db/tmplt_ops_sproc', $data, true);
		return $body;
	}

	// --------------------------------------------------------------------
	function make_main_sproc_sql($sprocName, $table, $sa)
	{
		$data['sprocName'] = $sprocName;
		$data['table'] = $table;

		$data['dt'] = date("m/d/Y");

		// make sproc args section
		$args = '';
		$sep = '';
		foreach($sa as $s) {
			$n = '@'.$s['argName'];
			$t = $s['type'];
			if($s['size'] != '') {
				$t .= '('.$s['size'].')';
			}
			$args .= "{$sep}\n\t{$n} {$t}";
			$sep =',';
		}
		$data['args'] = $args;

		// make sproc cols section
		$cols = '';
		$sep = '';
		foreach($sa as $s) {
			$n = $s['colName'];
			$cols .= "{$sep}\n\t\t{$n}";
			$sep =',';
		}
		$data['cols'] = $cols;

		// make sproc inserts section
		$insrts = '';
		$sep = '';
		foreach($sa as $s) {
			$n = '@'.$s['argName'];
			$insrts .= "{$sep}\n\t\t{$n}";
			$sep =',';
		}
		$data['insrts'] = $insrts;

		// make sproc updates section
		$updts = '';
		$sep = '';
		foreach($sa as $s) {
			$n = '@'.$s['argName'];
			$c = $s['colName'];
			$updts .= "{$sep}\n\t\t{$c} = {$n}";
			$sep =',';
		}
		$data['updts'] = $updts;
		
		$CI =& get_instance();
		$body = $CI->load->view('config_db/tmplt_sproc', $data, true);
		return $body;
	}

	// --------------------------------------------------------------------
	// return SQL to create appropriate entries in the sproc_args and
	// form field tables of the config database for the given stored procedure
	// in the given database.
	function make_csharp($my_db, $sa)
	{		
		$s = "";
		foreach($sa as $a) {
			$typ = "";
			switch($a['t']) {
				case "varchar":
					$typ = "VarChar, ".$a['s']."";
					break;
				default:
					$typ = ucfirst($a['t']);
					break;
			}
			$s .= "myParm = sc.Parameters.Add("."\"@".$a['a']."\", SqlDbType.".$typ.");"."\n";
			$s .= "myParm.Direction = ParameterDirection.".ucfirst($a['d']).";\n";
			if($a['d'] == 'input') {
				$s .= "myParm.Value = ".$a['a'].";\n";
			}
			$s .= "\n";
		}
		return $s;
	}

// --------------------------------------------------------------------
function make_controller_code($config_db, $page_fam_tag, $data_info, $title) 
{
		$data['tag'] = $page_fam_tag;
		$data['title'] = $title;

		$CI =& get_instance();
		$body = $CI->load->view('config_db/tmplt_controller', $data, true);
		return "<?php\n" . $body . "\n?>";
	}
	
	// --------------------------------------------------------------------
	function make_table_dump_display($config_db_table_list)
	{
		// dump content of each config db
		foreach($config_db_table_list as $db => $tables) {

			// set of each config db section with distinctive label
			echo "<hr>\n";
			echo "<span class='cfg_hdr'>$db</span>\n";
			echo "<a href='".site_url()."config_db/show_db/$db"."'>Config DB</a>";
			echo "<brr>\n";			

			// dump contents of each table
			foreach($tables as $table => $rows) {
				echo "<table class='cfg_tab' >\n";
// table header
echo "<tr><th>";
echo "$table  &nbsp; ";
echo "</th></tr>\n";
				echo "<tr><td>";
				echo "<table class='cfg_tab' >\n";
				$i = 0;
				foreach ($rows as $row) {
					if(!$i++) {
						$cols = array_keys($row);
						$n = count($cols);
//						// table header
//						echo "<tr><th colspan=\"$n\">";
//						echo "$table  &nbsp; ";
//						echo "</th></tr>\n";
						// column headers
						echo "<tr>\n";
						foreach($cols as $c) {
							echo "<th>$c</th>";
						}
						echo "</tr>\n";
					}
					echo "<tr>\n";
					foreach($cols as $c) {
						$x = ($row[$c])?$row[$c]:'&nbsp;';
						echo "<td>$x</td>";
					}
					echo "</tr>\n";
				}
				echo "</table>\n";
				echo "</td></tr>";
				echo "</table>\n";
				echo "<div style='height:1em;'></div>\n";
			}
			echo "\n\n";
		}		
	}

	// --------------------------------------------------------------------
	function make_config_nav_links($config_db)
	{
		$db = $config_db;
		$s = '';
		$s .= "<a href='http://prismwiki.pnl.gov/wiki/DMS_Config_DB_Help'>Help</a> &nbsp; | &nbsp;";
		$s .= "<a href='".site_url()."config_db/page_families'>Page Family Database List</a> &nbsp; | &nbsp;";
		$s .= "<a href='".site_url()."config_db/support_config_db_list'>Support Database List</a> &nbsp; | &nbsp;";
		if($config_db) {
			$s .= "<a href='".site_url()."config_db/show_db/$config_db'>Config DB</a> &nbsp; | &nbsp;";
		} else {
			$s .= "Config DB</a> &nbsp; | &nbsp;";
			$db = 'db';
		}
		$s .= "<a href='".site_url()."config_db/search/$db/_'>Search</a> &nbsp; | &nbsp;";
		return $s;
	}

	// --------------------------------------------------------------------
	function make_table_dump_text($config_db_table_list, $display_mode)
	{
		$sep = "\t";
		header("Content-type: text/plain");
		// dump content of each config db
		foreach($config_db_table_list as $db => $tables) {
			// dump contents of each table
			foreach($tables as $table => $rows) {
				// dump contents of each row with config db and table
				// as first two columns
				foreach ($rows as $row) {
					switch($display_mode) {
						case '':
							echo implode($sep, array_merge(array($db, $table), $row));
							echo "\n";
							break;
						case '-':
							echo implode($sep, $row);
							echo "\n";
							break;
						case 'dbo':
								if(stripos($row['name'], 'data_table') || stripos($row['name'], 'sproc')) {
									echo implode($sep, array_merge(array($db), $row));									
									echo "\n";
								}
							break;
					}
				}
			}
		}		
	}

	// --------------------------------------------------------------------
	// dump crosstab of selected general_params parameters for given list
	// of config dbs
	function make_general_params_dump($config_db_table_list, $config_db_table_name_list)
	{
		// params that are of interest
		$params = array(
			'page_family' => '',
			'list_report_data_table' => '',
			'entry_sproc' => '',
			'list_report_sproc' => '',
			'detail_report_data_table' => '',
			'operations_sproc' => '',
			'list_report_cmds' => '',
			'detail_report_commands' => '',
			'detail_report_cmds' => '',
			'my_db_group' => '',
			'tables' => '',
			);

		$param_labels = array(
			'page_family' => 'Page Family',
			'list_report_data_table' => 'List Report',
			'entry_sproc' => 'Entry Page',
			'list_report_sproc' => 'Param Report',
			'detail_report_data_table' => 'Detail Report',
			'operations_sproc' => 'Operations',
			'list_report_cmds' => 'List Report Cmds',
			'detail_report_commands' => 'Det Cmds T',
			'detail_report_cmds' => 'Detail Cmds X',
			'my_db_group' => 'DB Group',
			'tables' => 'Tables',
			);

		// build crosstab of parameters of interest
		$crosstab = array();
		// content of each config db
		foreach($config_db_table_list as $db => $tables) {
			// dump contents of each table
			foreach($tables as $table => $rows) {
				$output_row = $params;
				$output_row['page_family'] = str_replace('.db', '', $db);
				$output_row['tables'] = implode(', ', $config_db_table_name_list[$db]);
				if(in_array('detail_report_commands', $config_db_table_name_list[$db])) {
					$output_row['detail_report_commands'] = 'dr cmds T';
				}
				// dump contents of each row
				foreach ($rows as $row) {
					if(array_key_exists($row['name'], $params)) {
						$output_row[$row['name']] = $row['value'];
					}
				}
				$crosstab[] = $output_row;
			}
		}
		
		$s = '';
		// dump crosstab to output table
		$s .= "<table class='cfg_tab'>\n";
		// header row
		$s .= "<tr>\n";
		foreach(array_keys($params) as $c) {
			$s .= "<th> $param_labels[$c] </th>\n";			
		}
		// data rows
		$s .= "</tr>\n";
		foreach($crosstab as $output_row) {
			$s .= "<tr>\n";
			foreach($output_row as $c => $v) {
				$link = '';
				if(!$v) {
					$v = '&nbsp;';
				} else {
					if($c == 'page_family') {
						$link = '';
						$title = "Go to the config db page for this page family";
						$url = site_url().'config_db/show_db/'.$v.'.db';
						$v = "<a href='$url' title='$title'>$v</a> &nbsp;";
					}
					if($c == 'list_report_data_table') {
						$title = "Go to the list report page for this page family (associated view:$v)";
						$url = site_url().$output_row['page_family'].'/report';
						$link = "<a href='$url' title='$title'>list report</a> &nbsp;";
						$v = '';
					}
					if($c == 'list_report_sproc') {
						$title = "Go to the param report page for this page family (associated sproc:$v)";
						$url = site_url().$output_row['page_family'].'/param';
						$link = "<a href='$url' title='$title'>param report</a> &nbsp;";
						$v = '';
					}
					if($c == 'detail_report_data_table') {
						$title = "This page family has a detail report page (associated view:$v)";
						$url = 'javascript:void(0)';
						$link = "<a href='$url' title='$title'>(detail report)</a> &nbsp;";
						$v = '';
					}
					if($c == 'list_report_cmds') {
						$title = "This page family has a list report command section ($v)";
						$url = 'javascript:void(0)';
						$link = "<a href='$url' title='$title'>(lr cmds)</a> &nbsp;";
						$v = '';
					}
					if($c == 'detail_report_cmds') {
						$title = "This page family has an external cmds file ($v)";
						$url = 'javascript:void(0)';
						$link = "<a href='$url' title='$title'>(dr cmds X)</a> &nbsp;";
						$v = '';
					}
					if($c == 'operations_sproc') {
						$title = "This page family has an operation action defined (associated sproc:$v)";
						$url = 'javascript:void(0)';
						$link = "<a href='$url' title='$title'>(ops sproc)</a> &nbsp;";
						$v = '';
					}
					if($c == 'entry_sproc') {
						$title = "Go to the entry page for this page family (associated sproc:$v)";
						$url = site_url().$output_row['page_family'].'/create';
						$link = "<a href='$url' title='$title'>entry page</a> &nbsp;";
						$v = '';
					}
					if($c == 'tables') {
						$title = $v;
						$url = 'javascript:void(0)';
						$x = count(explode(',', $v));
						$link = "<a href='$url' title='$title'>$x</a> &nbsp;";
						$v = '';
					}
				}
				$s .= "<td>$link $v</td>\n";				
			}
			$s .= "</tr>\n";
		}	
		$s .= "</table>\n";
		return $s;
	}

	// --------------------------------------------------------------------
	// sql to move range of items to dest id
	//
	function make_sql_to_move_range_of_items($table_name, $range_start_id, $range_stop_id, $dest_id, $id_col)
	{
		$ceiling_id = 5000;
		$roof_id = $ceiling_id * 2;
		$range_size = $range_stop_id - $range_start_id;
		if($range_size < 0) return "Bad range";
		
		$rs = range($range_start_id, $range_stop_id);
		$rd = range($dest_id, $dest_id + $range_size);
		if(count(array_intersect($rs, $rd)) > 0) return "Source and destination ranges overlap";
		
		$r = ($range_start_id == $range_stop_id)?"item '$range_start_id'":"items with $id_col between '$range_start_id' and '$range_stop_id'";
		$s = "-- move $r in front of item '$dest_id'\n";

		$s .= "update $table_name set $id_col = ($id_col - $range_start_id) + $roof_id where $id_col >= $range_start_id and $id_col <= $range_stop_id;";
		$s .= " --move items in range out of main sequence\n";

		$s .= "update $table_name set $id_col = $id_col - ($range_size + 1) where $id_col >=  $range_stop_id and $id_col < $roof_id;";
		$s .= " --close gap left by items in range\n";

		$s .= "update $table_name set $id_col =  $id_col + $range_size + $ceiling_id  where $id_col >= $dest_id and $id_col < $roof_id;\n";
		$s .= "update $table_name set $id_col = $id_col - ($ceiling_id - 1) where $id_col > $ceiling_id and $id_col < $roof_id;"; 
		$s .= " --open gap at destination\n";

		$s .= "update $table_name set $id_col = ($id_col - $roof_id) + $dest_id where $id_col >= $roof_id;";
		$s .= " --put moved items back in main sequence\n";

		return $s;
	}
	
	// --------------------------------------------------------------------
	function make_edit_table_header($cols, $tag)
	{
		$hdr = "<tr>\n";
		foreach ($cols as $col_name) {
		    $hdr .= "<$tag>$col_name</$tag>";
		}
		$hdr .= "<$tag>&nbsp;</$tag>";
		$hdr .= "<$tag>&nbsp;</$tag>";
		$hdr .= "</tr>\n";
		return $hdr;
	}
	

?>
