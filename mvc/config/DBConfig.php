<?php
namespace mvc\config;

class DBConfig
{
    public function read()
    {
        return [
            'host' => 'localhost',
            'username' => 'root',
            'password' => '',
            'dbname' => 'mvc',
        ];
    }

    public function write()
    {
        return [
            'host' => 'localhost',
            'username' => 'root',
            'password' => '',
            'dbname' => 'mvc',
        ];
    }
}