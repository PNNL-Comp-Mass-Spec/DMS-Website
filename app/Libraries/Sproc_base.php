<?php
namespace App\Libraries;

/**
 * Base class for Sproc_sqlsrv and Sproc_sqlsrv
 */
abstract class Sproc_base {

    /**
     * Appends a parameter to the list of arguments required for calling a stored procedure
     * Also updates $input_params to add the field name and argument value
     * Both $args and $input_params are used by method execute in Sproc_mssql and Sproc_sqlsrv
     * @param array $args Stored procedure arguments (ByRef)
     * @param \stdClass $input_params Stored procedure arguments (ByRef)
     * @param string $fieldName Field name
     * @param mixed $value Value to send to the database for this argument
     * @param string $fieldType Field type (varchar, int, float, decimal, char, or text)
     * @param string $direction Direction: input or output (though output is in/out)
     * @param int|string $size Field size for varchar; use empty string for numeric
     */
    function AddLocalArgument(array &$args, \stdClass &$input_params, string $fieldName, $value, string $fieldType, string $direction, $size) {
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
     * @param resource|object|string $conn_id
     * @param array $args
     * @param object $input_params
     * @param array $formFields
     * @throws \Exception
     */
    abstract function execute($sprocName, $conn_id, $args, $input_params, $formFields);
}
?>
