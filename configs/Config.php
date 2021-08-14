<?php


class Config
{
    static function get()
    {
        $config = 'configs/con.php';
        if(!file_exists($config)) {
            throw new Exception("Cannot find config file");
        }
        $config = require "configs/con.php";
        return $config;
    }
}
