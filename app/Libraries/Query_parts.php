<?php
namespace App\Libraries;

/**
 * Track parts of the SQL query
 * Used primarily in Q_model, and therefore also in the Sql_... library classes
 * @category Helper class
 */
class Query_parts {
    /**
     * Database name
     * @var string
     */
    public string $dbn = 'default';

    /**
     * Table to retrieve data from
     * @var string
     */
    public string $table = '';

    /**
     * Only used on detail reports (via detail_report_sproc); only used when detail_report_data_table is empty
     * @var string
     */
    public string $detail_sproc = '';

    /**
     * Columns to show
     * @var string
     */
    public string $columns = '*';

    /**
     * Query where clause info
     * @var array
     */
    public array $predicates = array();      // of Query_predicate

    /**
     * User-defined list of column name and direction to sort on
     * @var array
     */
    public array $sorting_items = array();   // column => direction

    /**
     * Paging information
     * @var array
     */
    public array $paging_items = array('first_row' => 1, 'rows_per_page' => 12);

    /**
     * Default column and direction to sort on
     * Multiple column names can be specified by separating them with a comma
     * When using multiple columns, the same sort direction is applied to all of them
     * @var array
     */
    public array $sorting_default = array('col' => '', 'dir' => '');
}

/**
 * Track where clause items
 * @category Helper class
 */
class Query_predicate {
    /**
     * Boolean operator
     * @var string
     */
    public string $rel = 'AND';

    /**
     * Column name to filter on
     * @var string
     */
    public string $col;

    /**
     * Comparison mode (ContainsText, StartsWithText, GreaterThan, etc.)
     * @var string
     */
    public string $cmp;

    /**
     * Value to filter on
     * @var string
     */
    public string $val;
}
?>
