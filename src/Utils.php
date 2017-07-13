<?php


namespace GrBaseFramework;


/**
 * Class Utils
 *
 * @author Gaetan
 * @date   12/07/2017
 */
class Utils
{
    /**
     * Convert a string from CamelCase to snake_case.
     *
     * @param string $string
     * @return string
     */
    static function camelCaseToSnakeCase(string $string): string
    {
        return
            strtolower(
                preg_replace(
                    '/(?<!^)[A-Z]/',
                    '_$0',
                    $string
                )
            );
    }
}