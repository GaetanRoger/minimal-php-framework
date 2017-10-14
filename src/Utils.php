<?php


namespace Gaetanroger\MinimalPhpFramework;


/**
 * Utils class.
 *
 * This static class defines random functions needed by the framework.
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