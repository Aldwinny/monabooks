<?php
class Str
{


    // Turns strings into uppercase (used in genres and authors)
    public static function toUpperCamelCase($raw)
    {
        $str = ucwords(strtolower($raw), " \t\r\n\f\v.:,");
        return $str;
    }

    // Sanitize strings
    public static function sanitizeString($raw)
    {
        return htmlspecialchars(strip_tags($raw));
    }

    public static function sanitizeEmail($raw)
    {
        return filter_var($raw, FILTER_SANITIZE_EMAIL);
    }

    public static function sanitizeInt($raw)
    {
        return filter_var($raw, FILTER_SANITIZE_NUMBER_INT);
    }

    public static function sanitizeDouble($raw)
    {
        return filter_var($raw, FILTER_SANITIZE_NUMBER_FLOAT);
    }
}
