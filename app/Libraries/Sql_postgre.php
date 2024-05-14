<?php
namespace App\Libraries;

class Sql_postgre {

    /**
     * Tracks the root part of the constructed SQL that can affect the number of rows
     * @var type
     */
    private $baseSQL = '';

    // --------------------------------------------------------------------
    function __construct() {

    }

    /**
     * Build MSSQL T-SQL query from component parts
     * @param type $query_parts
     * @param type $option
     * @return string
     */
    function build_query_sql($query_parts, $option = "filtered_and_paged") {
        // process the predicate list
        $p_and = array();
        $p_or = array();
        foreach ($query_parts->predicates as $predicate) {
            switch (strtolower($predicate->rel)) {
                case 'and':
                    $p_and[] = $this->make_where_item($predicate);
                    break;
                case 'or':
                    $p_or[] = $this->make_where_item($predicate);
                    break;
                case 'arg':
                    // replace parameter in table spec with filter value
                    $query_parts->table = str_replace($predicate->col, "'" . $predicate->val . "'", $query_parts->table);
                    break;
            }
        }

        // Build the guts of query
        $baseSql = " FROM " . $query_parts->table;

        // Collect all 'or' clauses as one grouped item and put it into the 'and' item array
        if (!empty($p_or)) {
            $orClause = implode(' OR ', $p_or);

            // Make sure $orList does not end in ' OR '
            $pattern = '/ OR *$/i';
            $orClauseChecked = preg_replace($pattern, '', $orClause);

            if (!empty($orClauseChecked)) {
                $p_and[] = '(' . $orClauseChecked . ')';
            }
        }

        // 'and' all predicate clauses together
        $andClause = implode(' AND ', $p_and);

        if (!empty($andClause)) {
            // Make sure $andList does not end in ' AND '
            // We sometimes see this because the last item in $p_and is empty
            $pattern = '/ AND *$/i';
            $andClauseChecked = preg_replace($pattern, '', $andClause);

            if (!empty($andClauseChecked)) {
                $baseSql .= " WHERE $andClauseChecked";
            }
        }

        // Replace '[_]' with '\_'
        $baseSql = str_replace("[_]", "\_", $baseSql);

        //columns to display
        //$display_cols = $query_parts->columns; // TODO: handle '#..." columns, [], spaces
        // convert [#sortkey] to "#sortkey"
        $wrapHash = preg_replace("/\[(#[^\]]*)\]/", "\"$1\"", $query_parts->columns);
        // convert #sortkey (no quotes) to "#sortkey"; NOTE: will break a column that has '#' anywhere but the start of the name
        $wrapHash2 = preg_replace("/(#[^,]*)/", "\"$1\"", $wrapHash);
        // remove square brackets, and replace spaces on the matches with '_'
        $replaceSpace = preg_replace_callback("/\[([^\]]*)\]/", function($matches) { return str_replace(" ", "_", $matches[1]); }, $wrapHash2);

        // Replace '[' and ']' with ''
        $display_cols = str_replace(array("[", "]"), "", $replaceSpace);

        // construct final query according to its intended use
        $sql = "";
        switch ($option) {
            case "count_only": // query for returning count of total rows
                $sql .= "SELECT COUNT(*) AS numrows";
                $sql .= $baseSql;
                break;
            case "column_data_only":
                $sql .= "SELECT " . $display_cols;
                $sql .= $baseSql . " LIMIT 1";
                break;
            case "filtered_only":
                $sql .= "SELECT " . $display_cols;
                $sql .= $baseSql;
                break;
            case "filtered_and_sorted": // (not paged)
                $sql .= "SELECT " . $display_cols;
                $sql .= $baseSql;
                $orderBy = $this->make_order_by($query_parts->sorting_items);
                $sql .= ($orderBy) ? " ORDER By $orderBy" : "";
                break;
            case "filtered_and_paged":
                // make ordering expression from sorting params
                $orderBy = $this->make_order_by($query_parts->sorting_items);
                // get limit and offset parameters for paging
                $first_row = $query_parts->paging_items['first_row'];
                $limit = $query_parts->paging_items['rows_per_page'];
                $last_row = $first_row + $limit;
                // construct query for returning a page of rows
                $sql .= "SELECT * FROM (";
                $sql .= "SELECT ROW_NUMBER() OVER (ORDER By " . $orderBy . ") AS \"#Row\", ";
                $sql .= " " . $display_cols;
                $sql .= $baseSql;
                $sql .= ") AS T ";
                $sql .= "WHERE \"#Row\" >= " . $first_row . " AND \"#Row\" < " . $last_row;

                // Note: an alternative to "Row_Number() Over (Order By x Desc)"
                // is to use "ORDER BY x DESC OFFSET 0 ROWS FETCH NEXT 125 ROWS ONLY;"
                // However, performance will typically be the wame

                break;
        }
        $this->baseSQL = $baseSql;
        return $sql;
    }

    /**
     * Build the Order By clause
     * @param type $sort_items
     * @return type
     */
    private function make_order_by($sort_items) {
        $a = array();
        foreach ($sort_items as $item) {
            $col = str_replace(" ", "_", $item->col);
            if (strpos($col, "#") !== false) {
                $col = "\"" . $col . "\"";
            }
            $a[] = $col . " " . $item->dir;
        }
        $s = implode(', ', $a);
        return $s;
    }

    /**
     * Generate the Where Clause from the predicate specification object
     * (column name, comparison operator, comparison value)
     * @param type $predicate
     * @return type
     */
    private function make_where_item($predicate) {
        $col = str_replace(" ", "_", $predicate->col);
        if (strpos($col, "#") !== false) {
            $col = "\"" . $col . "\"";
        }

        $cmp = $predicate->cmp;
        $val = trim($predicate->val);

        $valNoCommas = str_replace(',', '', $val);

        $str = '';
        switch ($cmp) {
            case "wildcards":
                $val = str_replace('_', '\_', $val);
                $val = str_replace('*', '%', $val);
                $val = str_replace('?', '_', $val);
                $str .= "$col SIMILAR TO '$val'";
                break;
            case "ContainsText":
            case "CTx":
                $val = (substr($val, 0, 1) == '`') ? substr($val, 1) . '%' : '%' . $val . '%';
                $str .= "$col SIMILAR TO '$val'";
                break;
            case "DoesNotContainText":
            case "DNCTx":
                $val = (substr($val, 0, 1) == '`') ? substr($val, 1) . '%' : '%' . $val . '%';
                $str .= "NOT $col SIMILAR TO '$val'";
                break;
            case "MatchesText":
            case "MTx":
                $str .= "$col = '$val'";
                break;
            case "MatchesBlank":
            case "MBTx":
                $str .= "ISNULL($col, '') = ''";
                break;
            case "StartsWithText":
            case "SWTx":
                $val = (substr($val, 0, 1) == '`') ? substr($val, 1) . '%' : $val . '%';
                $str .= "$col SIMILAR TO '$val'";
                break;
            case "Equals":
            case "EQn":
                if (is_numeric($valNoCommas)) {
                    $str .= "$col = $valNoCommas";
                } else {
                    $str .= "$col = '$val'";
                }
                break;
            case "NotEqual":
            case "NEn":
                if (is_numeric($valNoCommas)) {
                    $str .= "NOT $col = $valNoCommas";
                }
                break;
            case "GreaterThan":
            case "GTn":
                if (is_numeric($valNoCommas)) {
                    $str .= "$col > $valNoCommas";
                }
                break;
            case "LessThan":
            case "LTn":
                if (is_numeric($valNoCommas)) {
                    $str .= "$col < $valNoCommas";
                }
                break;
            case "LessThanOrEqualTo":
            case "LTOEn":
                if (is_numeric($valNoCommas)) {
                    $str .= "$col <= $valNoCommas";
                }
                break;
            case "GreaterThanOrEqualTo":
            case "GTOEn":
                if (is_numeric($valNoCommas)) {
                    $str .= "$col >= $valNoCommas";
                }
                break;
            case "MatchesTextOrBlank":
            case "MTxOB":
                $str .= "($col = '$val' OR $col = '')";
                break;
            case "LaterThan":
            case "LTd":
                $str .= "$col > '$val'";
                break;
            case "EarlierThan":
            case "ETd":
                $str .= "$col < '$val'";
                break;
            case "MostRecentWeeks":
            case "MRWd":
                $str .= " $col > CURRENT_TIMESTAMP - Interval '$val weeks' ";
                break;
            default:
                $str .= "true /* '$cmp' unrecognized */";
                break;
        }
        return $str;
    }

    /**
     * Return the root part of the constructed SQL that can affect the number of rows
     * For example: FROM V_Analysis_Job_List_Report_2 WHERE "Tool" LIKE '%MSGFPlus%' AND "Last_Affected" > DATEADD(Week, -1, GETDATE())
     * @return type
     */
    function get_base_sql() {
        return $this->baseSQL;
    }

    // --------------------------------------------------------------------
    // (the following might be factored out of this class if data types are not db specific)
    // --------------------------------------------------------------------

    /**
     * SQL comparison definitions
     * @var type
     */
    private $sqlCompDefs = array(
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
     * Get the allowed comparisons for the given data type
     * @param type $data_type
     * @return mixed
     */
    function get_allowed_comparisons_for_type($data_type) {
        // The postgres_driver returns data types as integers
        // pgsql: See https://github.com/postgres/postgres/blob/master/src/include/catalog/pg_type.dat for type codes
        // pgsql: See https://www.postgresql.org/docs/current/datatype.html for type names
        // pgsql: can also reference https://github.com/postgres/postgres/blob/master/src/backend/catalog/information_schema.sql for some.
        // Some type codes are not part of that because they are extensions/modules
        /*
        114 JSON stored as text
        142 XML content
        790 money/'cash'
        829 MAC address/774 for 8-byte
        869 IP address/netmask (also 650? for cidr - network lowest address)
        */

        $cmps = array();
        switch ($data_type) {
            case 'text':
            case 25:    // pgsql text
            case 'char':
            case 1042:  // pgsql char
            case 'varchar':
            case 1043:  // pgsql varchar
            case 'citext':
            case 16385: // pgsql citext
            case 16389: // pgsql citext
            case 1:     // char
            case -8:    // nchar
            case -10:   // ntext
            case -9:    // nvarchar
            case -1:    // text
            case 12:    // varchar
                foreach ($this->sqlCompDefs as $n => $def) {
                    if (in_array('text', $def['type'])) {
                        $cmps[$n] = $def['label'];
                    }
                }
                break;
            case 'int':
            case 'money':
            case 'numeric':
            case 1700:  // pgsql numeric
            case 'real':
            case 'float':
            case 700:   // pgsql float
            case 16:    // pgsql boolean
            case -5:    // bigint
            case -7:    // bit
            case 3:     // decimal
            case 6:     // float
            case 4:     // int
            case 2:     // numeric
            case 7:     // real
            case 5:     // smallint
            case -6:    // tinyint
            case 'float4':
            case 'bit':
            case 1560:  // pgsql bit
            case 1562:  // pgsql varbit
            case 'int2':
            case 21:    // pgsql int2
            case 'smallint':
            case 'int4':
            case 23:    // pgsql int4
            case 'integer':
            case 23:    // pgsql integer
            case 'int8':
            case 'bigint':
            case 20:    // pgsql bigint/int8
            case 'decimal':
            case 'double precision':
            case 701:   // pgsql double precision
                foreach ($this->sqlCompDefs as $n => $def) {
                    if (in_array('numeric', $def['type'])) {
                        $cmps[$n] = $def['label'];
                    }
                }
                break;
            case 'datetime':
            case 1082:  // pgsql date
            case 1083:  // pgsql time of day
            case 'timestamp':
            case 1114:  // pgsql date and time/'timestamp without time zone'
            case 'timestamptz':
            case 1184:  // pgsql timestamptz
            case 1266:  // pgsql 'time of day with time zone'?
            case 91:    // date
            case 93:    // datetime
            case -154:  // time
            case 1186:  // pgsql interval
                foreach ($this->sqlCompDefs as $n => $def) {
                    if (in_array('datetime', $def['type'])) {
                        $cmps[$n] = $def['label'];
                    }
                }
                break;
            default:
                $cmps = array("(unrecognized type '$data_type')" => "(unrecognized type '$data_type')");
                break;
        }
        return $cmps;
    }
}
?>
