<?php

namespace Src\DB;

use mysqli;
use Src\Traits\SingletonTrait;



class Manager
{
    use SingletonTrait;
    private ?mysqli $mysqli = null;
    private function __construct() {}

    public function init(array $config): void
    {
        // подключениее
        if ($this->mysqli === null) {
            $this->mysqli = new mysqli(
                $config['host'],
                $config['user'],
                $config['pass'],
                $config['name'],
                $config['port']
            );
            $this->mysqli->set_charset($config['charset']);
        }
    }

    public function getConnection(): mysqli
    {
        return $this->mysqli;
    }
} 