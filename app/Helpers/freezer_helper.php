<?php

/**
 * Render contents of freezer location
 * @param array $location
 * @param array $contents
 * @return string
 */
function render_location_contents(array $location, array $contents): string {
    $loc = $location['Location'];
    $avail = $location['Available'];

    // Render location
    $s = '';
    $s .= "<div>" . $loc . "</div>";

    // If space for more containers is available
    // render link to make a new one
    if (($avail != '0')) {
        $s .= "<div>";
        $s .= "<a href='" . site_url("material_container/create/init/-/-/$loc") . "'>Add New Container</a>";
        $s .= "</div>";
    }

    // Render containers, if any
    if (array_key_exists($loc, $contents)) {
        foreach ($contents[$loc] as $content) {
            $cn = $content['Container'];
            $s .= "<div>";
            $s .= "<a href='" . site_url("material_container/show/$cn") . "'>" . $cn . "</a>";
            $s .= " &nbsp; ";
            $s .= "<span>" . $content['Comment'] . "</span>";
            $s .= "</div>";
        }
    }
    return $s;
}

/**
 * Build nested array representation of freezer locations
 * @param array $locs
 * @return array
 */
function make_freezer_matrix_array(array $locs): array {
    $fzr = array();
    foreach ($locs as $loc) {
        $status = $loc['status'];
        $active_desc = "<span style='color:green;'>$status</span>";
        $inactive_desc = "";
        $desc = ($status == 'Active') ? $active_desc : $inactive_desc;
        $fzr[$loc['shelf']][$loc['rack']][$loc['row']][$loc['col']] = $desc;
    }
    return $fzr;
}

/**
 * Make inner tables for a matrix row
 * @param array $fzr
 * @param string $table_setup
 * @param string $tstyl
 * @return array
 */
function make_matrix_row_col_tables(array $fzr, string $table_setup, string $tstyl): array {
    // Make inner tables (row, col)
    $otr = array();

    // Make row for each shelf
    for ($shelf = 1; $shelf <= count($fzr); $shelf++) {
        for ($rack = 1; $rack <= count($fzr[$shelf]); $rack++) {
            $tbrc = "<table $table_setup $tstyl >\n";
            for ($row = 1; $row <= count($fzr[$shelf][$rack]); $row++) {
                $cols = $fzr[$shelf][$rack][$row];

                // Make header row
                if ($row == 1) {
                    $hdr = array_keys($cols);
                    $thdr = "<thead><tr>";
                    $thdr .= "<th></th>";
                    for ($i = 0; $i < count($hdr); $i++) {
                        $thdr .= "<th style='width:10em;'>Col $hdr[$i]</th>";
                    }
                    $thdr .= "</tr></thead>\n";
                    $tbrc .= $thdr;
                }

                // Make rack row
                $tbrc .= "<tr>";
                $tbrc .= "<th>Row $row</th>";
                for ($j = 1; $j <= count($cols); $j++) {
                    $tbrc .= "<td>";
                    $tbrc .= $cols[$j];
                    $tbrc .= "</td>";
                }
                $tbrc .= "</tr>";
            }
            $tbrc .= "</table>";
            $otr[$shelf][$rack] = $tbrc;
        }
    }
    return $otr;
}

/**
 * Render a matrix table
 * @param array $otr
 * @param string $table_setup
 * @return string
 */
function render_matrix_table(array $otr, string $table_setup): string {
    // Make outer table (shelf, rack) containing inner tables (row, col)
    $tbs = "<table $table_setup >\n";

    // Make header row
    $thdr = "<thead><tr>";
    $thdr .= "<th></th>";

    if (sizeof($otr) > 0) {
        for ($i = 1; $i <= count($otr[1]); $i++) {
            $thdr .= "<th>Rack $i</th>";
        }
    }
    $thdr .= "</tr></thead>\n";
    $tbs .= $thdr;

    if (sizeof($otr) > 0) {
        // Make row for each shelf
        for ($shelf = 1; $shelf <= count($otr); $shelf++) {
            $tbs .= "<tr>";
            $tbs .= "<th>Shelf $shelf</th>";
            for ($rack = 1; $rack <= count($otr[$shelf]); $rack++) {
                $tbs .= "<td>";
                $tbs .= $otr[$shelf][$rack];
                $tbs .= "</td>";
            }
            $tbs .= "</tr>";
        }
    }

    $tbs .= "</table>";
    return $tbs;
}
