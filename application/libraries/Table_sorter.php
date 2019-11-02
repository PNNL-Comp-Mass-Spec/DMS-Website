<?php

class Table_sorter {

    protected $column;
    protected $sort_filter = array();

    // --------------------------------------------------------------------
    function __construct() {
        
    }

    /**
     * Sort a table on the given column
     * @param type $table
     * @param type $column
     * @param type $dir
     * @return type
     */
    function sort($table, $column, $dir = 'ASC') {
        $this->column = $column;
        switch ($dir) {
            case 'ASC':
                usort($table, array($this, 'compare_asc'));
                break;
            case 'DESC':
                usort($table, array($this, 'compare_desc'));
                break;
        }
        return $table;
    }

    /**
     * Compare values for an ascending sort
     * @param type $a
     * @param type $b
     * @return int
     */
    function compare_asc($a, $b) {
        if ($a[$this->column] == $b[$this->column]) {
            return 0;
        }
        return ($a[$this->column] < $b[$this->column]) ? -1 : 1;
    }

    /**
     * Compare values for a descending sort
     * @param type $a
     * @param type $b
     * @return int
     */
    function compare_desc($a, $b) {
        if ($a[$this->column] == $b[$this->column]) {
            return 0;
        }
        return ($a[$this->column] > $b[$this->column]) ? -1 : 1;
    }

    /**
     * Sort a table on multiple columns
     * @param type $table
     * @param type $sort_filter
     * @return type
     */
    function sort_multi_col($table, $sort_filter) {
//print_r($sort_filter); echo '<hr>';
        $this->sort_filter = $sort_filter;
        usort($table, array($this, 'compare_multi_col'));
        return $table;
    }

    /**
     * Compare multiple columns while sorting
     * @param type $a
     * @param type $b
     * @return type
     */
    function compare_multi_col($a, $b) {
//print_r($a); echo '<br>---------<br>';print_r($b); echo '<hr>';
        // Initially presume the two values are equal
        $comparison = 0;

        // Work forward through the sorting columns
        // until we either come to a column where
        // the values are different or we run out
        // of sorting columns to try
        $col = '';
        foreach ($this->sort_filter as $sort) {
            $col = $sort['qf_sort_col'];
            $dir = $sort['qf_sort_dir'];
            if (!$col) {
                break;
            }
            if ($a[$col] != $b[$col]) {
                if ($dir == 'ASC') {
                    $comparison = ($a[$col] < $b[$col]) ? -1 : 1;
                    break;
                } else
                if ($dir == 'DESC') {
                    $comparison = ($a[$col] > $b[$col]) ? -1 : 1;
                    break;
                }
            }
        }
//echo "a:$a[$col], b:$b[$col], col:$col, cmp:$comparison <hr>";
        return $comparison;
    }

}
