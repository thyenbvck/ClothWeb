<?php
class DB
{
    public static $instance = NULL;
    public static function getInstance()
    {
        if (!isset(self::$instance))
        {
            // Reads from environment variables when deployed (Railway, Render, cPanel, etc.)
            // Falls back to local XAMPP defaults automatically
            $host = getenv('DB_HOST') ?: 'localhost';
            $port = (int)(getenv('DB_PORT') ?: 3306);
            $user = getenv('DB_USER') ?: 'root';
            $pass = getenv('DB_PASS') ?: '';
            $name = getenv('DB_NAME') ?: 'lastdatabaseweb';

            self::$instance = mysqli_connect($host, $user, $pass, $name, $port);
            if (mysqli_connect_error())
            {
                die("Failed to connect to MySQL: " . mysqli_connect_error());
            }
            mysqli_set_charset(self::$instance, 'utf8mb4');
        }
        return self::$instance;
    }
}