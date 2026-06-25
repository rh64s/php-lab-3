<?php

namespace DebugTools;

class Debug {
    public static function log($object, string $name = '', bool $die = false): void
    {
        if ($name) echo "<h4>$name</h4>";
        echo "<pre>";
        print_r($object);
        echo "</pre>";
        if ($die) die();
        return;
    }

    public static function info($message): void
    {
        $path = app()->settings->app['devLog'];
        if (!is_array($message) && !is_string($message)) {
            $message = print_r($message, true);
        }
        file_put_contents($path, "\n", FILE_APPEND | LOCK_EX);
        file_put_contents($path, $message, FILE_APPEND | LOCK_EX);
    }
}