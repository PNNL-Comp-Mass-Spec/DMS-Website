<?php
namespace App\Libraries;

// --------------------------------------------------------------------
// Form Validation preformatting
// --------------------------------------------------------------------

class ValidationPreformat {
    /**
     * Files to load with validation functions.
     *
     * @var array
     */
    protected $ruleSetFiles = [ \App\Validation\DmsValidationPreformat::class ];

    /**
     * The loaded instances of our validation files.
     *
     * @var array
     */
    protected $ruleSetInstances = [];

    /**
     * Constructor
     */
    function __construct() {
        $this->loadRuleSets();
    }

    /**
     * Preformat input data with supplied rules
     * @param array $data data from POST, via request->getPost()
     * @param mixed $rules
     * @return array
     */
    public function run($data, $rules)
    {
        helper('array');

        // Run through each rule. If we have any field set for
        // this rule, then we need to run them through!
        foreach ($rules as $field => $setup)
        {
            // Blast $setup apart, unless it's already an array.
            $fieldRules = $setup['rules'] ?? $setup;
            $fieldName = $setup['label'] ?? $setup;

            if (is_string($fieldRules))
            {
                $fieldRules = $this->splitRules($fieldRules);
            }

            $values = dot_array_search($fieldName, $data);

            if (is_array($values))
            {
                if ($values === [])
                {
                    // We'll process the values right away if an empty array
                    $values = $this->processRules($field, $setup['label'] ?? $field, $values, $fieldRules, $data);
                }
                else
                {
                    foreach ($values as $key => $value)
                    {
                        // Otherwise, we'll let the loop do the job
                        $values[$key] = $this->processRules($field, $setup['label'] ?? $field, $value, $fieldRules, $data);
                    }
                }
            }
            else
            {
                $values = $this->processRules($field, $setup['label'] ?? $field, $values, $fieldRules, $data);
            }

            $data[$fieldName] = $values;
        }

        return $data;
    }

    /**
     * Runs all of $rules against $field, until one fails, or
     * all of them have been processed. If one fails, it adds
     * the error to $this->errors and moves on to the next,
     * so that we can collect all of the first errors.
     *
     * @param string       $field
     * @param string|null  $label
     * @param string|array $value
     * @param array|null   $rules
     * @param array        $data
     *
     * @return string|array value, preformatted
     */
    protected function processRules(string $field, string $label = null, $value, $rules = null, array $data = null)
    {
        if (is_null($data))
        {
            throw new InvalidArgumentException('You must supply the parameter: data.');
        }

        foreach ($rules as $rule)
        {
            $param  = false;

            if (preg_match('/(.*?)\[(.*)\]/', $rule, $match))
            {
                $rule  = $match[1];
                $param = $match[2];
            }

            // Placeholder for custom errors from the rules.
            $error = null;

            $found = false;

            // Check in our rulesets
            foreach ($this->ruleSetInstances as $set)
            {
                if (! method_exists($set, $rule))
                {
                    continue;
                }

                $found  = true;
                $value = $param === false ? $set->$rule($value, $error) : $set->$rule($value, $param, $data, $error);

                break;
            }

            // If the rule wasn't found anywhere, we just continue; maybe log a warning.
            if (! $found)
            {
                continue;
            }

            //// Set the error message if we didn't survive.
            //if ($passed === false)
            //{
            //    // if the $value is an array, convert it to as string representation
            //    if (is_array($value))
            //    {
            //        $value = '[' . implode(', ', $value) . ']';
            //    }
            //
            //    $this->errors[$field] = is_null($error)
            //        ? $this->getErrorMessage($rule, $field, $label, $param, $value)
            //        : $error; // @phpstan-ignore-line
            //
            //    return false;
            //}
        }

        return $value;
    }

    /**
     * Loads all of the rulesets classes that have been defined in the
     * Config\Validation and stores them locally so we can use them.
     */
    protected function loadRuleSets()
    {
        if (empty($this->ruleSetFiles))
        {
            throw ValidationException::forNoRuleSets();
        }

        foreach ($this->ruleSetFiles as $file)
        {
            $this->ruleSetInstances[] = new $file();
        }
    }

    /**
     * Split rules string by pipe operator.
     *
     * @param string $rules
     *
     * @return array
     */
    protected function splitRules(string $rules): array
    {
        $nonEscapeBracket = '((?<!\\\\)(?:\\\\\\\\)*[\[\]])';
        $pipeNotInBracket = sprintf(
            '/\|(?=(?:[^\[\]]*%s[^\[\]]*%s)*(?![^\[\]]*%s))/',
            $nonEscapeBracket,
            $nonEscapeBracket,
            $nonEscapeBracket
        );

        $_rules = preg_split($pipeNotInBracket, $rules);

        return array_unique($_rules);
    }
}
?>
