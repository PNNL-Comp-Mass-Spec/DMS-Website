<?php

/**
 * Helpers for general access to model config database files
 */

if ( ! function_exists('get_model_config_db_list'))
{
    /**
     * Return array of non-disabled config files in the config folder
     * @param string $file_filter
     * @return array of matching file names
     */
    function get_model_config_db_list($file_filter = '')
    {
        $config_files = array();
        $disabled_files = array();
        helper('string');
        $mainPath = config('App')->model_config_path;
        $instancePath = config('App')->model_config_instance_path;

        // If $instancePath is defined/valid, check for files there first
        if (IsNotWhitespace($instancePath) && is_dir($instancePath)) {
            $handle = opendir($instancePath);
            if ($handle) {
                while (false !== ($file = readdir($handle))) {
                    if (preg_match('/\.disabled$/', $file)) {
                        $disabled_files[] = substr($file, 0, -strlen(".disabled"));
                    } elseif (preg_match($file_filter, $file)) {
                        $config_files[] = $file;
                    }
                }
                closedir($handle);
            }
        }

        $disabled_files_map = array_flip($disabled_files);
        $overridden_files_map = array_flip($config_files);

        $handle = opendir($mainPath);
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
                if (array_key_exists($file, $disabled_files_map) || array_key_exists($file, $overridden_files_map)) {
                    continue;
                } elseif (preg_match($file_filter, $file)) {
                    $config_files[] = $file;
                }
            }
            closedir($handle);
        }

        return $config_files;
    }
}

if ( ! function_exists('get_model_config_db_path'))
{
    /**
     * Looks for the specified model config DB file
     * 
     * @param $dbFileName
     * @return object: fields 'path', 'exists', 'dirPath', 'disabled': path to $dbFileName, if file exists, model config directory path
     */
    function get_model_config_db_path($dbFileName)
    {
        helper('string');
        $mainPath = config('App')->model_config_path;
        $instancePath = config('App')->model_config_instance_path;

        $data = new \stdClass();
        $data->exists = false;
        $data->disabled = false;
        $data->dirPath = $instancePath;
        $data->path = $instancePath . $dbFileName;

        // If $instancePath is whitespace/not defined, then always use $mainPath
        if (IsNullOrWhiteSpace($instancePath)) {
            $data->path = $mainPath . $dbFileName;
            $data->exists = file_exists($data->path);
            return $data;
        }

        // Test if the file exists in the instance model config directory
        $testPath = $instancePath . $dbFileName;
        if (file_exists($testPath)) {
            $data->path = $testPath;
            $data->exists = true;
            return $data;
        }

        // Test if the model is disabled in the instance model config directory
        $testPath .= '.disabled';
        if (file_exists($testPath)) {
            $data->path = $testPath;
            $data->exists = false;
            $data->disabled = true;
            return $data;
        }

        // Test if the model exists in the main config directory
        $testPathMain = $mainPath . $dbFileName;
        if (file_exists($testPathMain)) {
            $data->path = $testPathMain;
            $data->exists = true;
        }

        return $data;
    }
}