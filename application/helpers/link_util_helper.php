<?php

	// --------------------------------------------------------------------
	function cmd_link_icon($usage = 'cmd') {
		$icon = get_link_image($usage);
		$s = "<span class='expando_section ui-icon $icon'></span>";
		return $s;
	}

	// --------------------------------------------------------------------
	function expansion_link($usage = 'plus') {
		$icon = get_link_image($usage);
		$s = "<span class='expando_section ui-icon $icon'></span>";
		return $s;
	}
	
	// --------------------------------------------------------------------
	function get_link_image($usage) {
		$s = "";
		switch($usage) {
			case "go":
				$s = "ui-icon-circle-arrow-e";
				break;
			case "down":
				$s = "ui-icon-circle-arrow-s";
				break;
			case "minus":
				$s = "ui-icon-circle-minus";
				break;
			case "plus":
				$s = "ui-icon-circle-plus";
				break;
			case "cmd":
				$s = "ui-icon-circle-triangle-e";
				break;
			case "close":
			case "delete":
				$s = "ui-icon-closethick";
				break;
			case "refresh":
				$s = "ui-icon-transferthick-e-w"; // ui-icon-refresh
				break;
			case "last":
				$s = "ui-icon-seek-end";
				break;
			case "first":
				$s = "ui-icon-seek-first";
				break;
			case "next":
				$s = "ui-icon-seek-next";
				break;
			case "prev":
				$s = "ui-icon-seek-prev";
				break;
			case "adjust":
				$s = "ui-icon-wrench";
				break;
			default:
				$s = '??';
				break;
		}
		return $s;	
	}
?>