<?php
namespace App\Libraries;

/**
 * Base class for Sproc_mssql and Sproc_sqlsrv
 */
abstract class Sproc_base {

    /**
     * Appends a parameter to the list of arguments required for calling a stored procedure
     * Also updates $input_params to add the field name and argument value
     * Both $args and $input_params are used by method execute in Sproc_mssql and Sproc_sqlsrv
     * @param type $args Stored procedure arguments (ByRef)
     * @param stdClass $input_params Stored procedure arguments (ByRef)
     * @param type $fieldName Field name
     * @param type $value Value to send to the database for this argument
     * @param type $fieldType Field type (varchar, int, float, decimal, char, or text)
     * @param type $direction Direction: input or output (though output is in/out)
     * @param type $size Field size for varchar; use empty string for numeric
     */
    function AddLocalArgument(&$args, &$input_params, $fieldName, $value, $fieldType, $direction, $size) {
        // Append a new stored procedure argument
        $args[] = array(
            'field' => "<local>",
            'name' => $fieldName,
            'type' => $fieldType,
            'dir' => $direction,
            'size' => $size
        );

        // Append a new field value, for example
        // $input_params->Experiment = 'QC_Shew_18_01'
        $input_params->$fieldName = $value;
    }

    /**
     * Call stored procedure given by $sprocName on database connection $conn_id
     * binding arguments to fields in $par as defined by specifications in $args.
     * Return results as fields in $par
     * @param string $sprocName
     * @param resource $conn_id
     * @param array $args
     * @param object $par
     * @throws Exception
     */
    abstract function execute($sprocName, $conn_id, $args, $par);
}
?>
