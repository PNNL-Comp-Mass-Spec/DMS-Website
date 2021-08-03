<?php

namespace Config;

/**
 * Extra Configuration of a config file, using dynamic properties.
 */
class ConfigFromEnvironmentFile
{
    public static function getClassNameNoNamespace(object $object)
    {        
        $prefix      = get_class($object);
        $slashAt     = strrpos($prefix, '\\');
        $shortPrefix = substr($prefix, $slashAt === false ? 0 : $slashAt + 1);
        return $shortPrefix;
    }

    public static function getConfigFromEnvironmentSpecificFile(object $object)
    {
        if (!($object instanceof \CodeIgniter\Config\BaseConfig))
        {
            return;
        }

        $environment = $_ENV['CI_ENVIRONMENT'];
        $shortClass = static::getClassNameNoNamespace($object);
        $path = APPPATH . 'Config/' . $environment . '/' . $shortClass . '.php';
        $data = [];

        if (is_file($path))
        {
            $data = require $path;
        }

        if (!($data))
        {
            return;
        }

        foreach ($data as $property => $value)
        {
            $object->$property = $value;
        }
    }
}
