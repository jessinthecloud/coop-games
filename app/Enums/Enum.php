<?php
namespace App\Enums;

abstract class Enum
{
    /**
     * Allow returning enum name by value
     *
     * @param  [mixed] $val value of the enum
     *
     * @return string      name of the enum
     *
     * @throws \ErrorException
     * @throws \ReflectionException
     */
    public static function toString($val) : ?string
    {
        $enum = (new \ReflectionClass(get_called_class()))->getConstants();
        $enum = array_flip($enum);
        if (!isset($enum[$val])) {
            throw new \ErrorException("Enum does not exist");
        }
        return $enum[$val] ?? null;
    }

    public static function isValidName($name, $strict = false): bool
    {
        $constants = (new \ReflectionClass(get_called_class()))->getConstants();

        if ($strict) {
            return array_key_exists($name, $constants);
        }

        $keys = array_map('strtolower', array_keys($constants));
        return in_array(strtolower($name), $keys);
    }

    public static function isValidValue($value, $strict = true): bool
    {
        $values = array_values((new \ReflectionClass(get_called_class()))->getConstants());
        return in_array($value, $values, $strict);
    }
}
