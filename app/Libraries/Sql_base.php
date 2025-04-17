<?php
namespace App\Libraries;

/**
 * Base class for Sql_sqlsrv and Sql_sqlsrv
 */
abstract class Sql_base {

    /**
     * Tracks the root part of the constructed SQL that can affect the number of rows
     * @var string
     */
    protected string $baseSQL = '';

    /**
     * SQL comparison definitions
     * @var array
     */
    protected $sqlCompDefs = array(
        "ContainsText" => array(
            'label' => "Contains Text",
            'type' => array('text'),
        ),
        "DoesNotContainText" => array(
            'label' => "Does Not Contain Text",
            'type' => array('text'),
        ),
        "MatchesText" => array(
            'label' => "Matches Text",
            'type' => array('text'),
        ),
        "StartsWithText" => array(
            'label' => "Starts With Text",
            'type' => array('text'),
        ),
        "GreaterThanOrEqualTo" => array(
            'label' => "Greater Than or Equal To",
            'type' => array('numeric'),
        ),
        "LessThanOrEqualTo" => array(
            'label' => "Less Than or Equal To",
            'type' => array('numeric'),
        ),
        "GreaterThan" => array(
            'label' => "Greater Than",
            'type' => array('numeric'),
        ),
        "LessThan" => array(
            'label' => "Less Than",
            'type' => array('numeric'),
        ),
        "Equals" => array(
            'label' => "Equals",
            'type' => array('numeric'),
        ),
        "NotEqual" => array(
            'label' => "Not Equal",
            'type' => array('numeric'),
        ),
        "LaterThan" => array(
            'label' => "Later Than",
            'type' => array('datetime'),
        ),
        "EarlierThan" => array(
            'label' => "Earlier Than",
            'type' => array('datetime'),
        ),
        "MostRecentWeeks" => array(
            'label' => "Most Recent N Weeks",
            'type' => array('datetime'),
        ),
    );

    /**
     * Build database-specifc SQL query from component parts
     * @param \App\Libraries\Query_parts $query_parts
     * @param string $option
     * @return string
     */
    public abstract function build_query_sql(\App\Libraries\Query_parts $query_parts, string $option = "filtered_and_paged");

    /**
     * Return the root part of the constructed SQL that can affect the number of rows
     * For example (sqlsrv)  : FROM V_Analysis_Job_List_Report_2 WHERE [Tool] LIKE '%MSGFPlus%' AND [Last_Affected] > DATEADD(Week, -1, GETDATE())
     * For example (postgres): FROM V_Analysis_Job_List_Report_2 WHERE "Tool" LIKE '%MSGFPlus%' AND "Last_Affected" > DATEADD(Week, -1, GETDATE())
     * @return string
     */
    public function get_base_sql() {
        return $this->baseSQL;
    }

    /**
     * Get the allowed comparisons for the given data type
     * @param mixed $data_type
     * @return array
     */
    public abstract function get_allowed_comparisons_for_type($data_type);
}
?>
