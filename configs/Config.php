<?php


class Config
{
    static function get()
    {
        $config = 'configs/conf.php';
        if(!file_exists($config)) {
            throw new Exception("Cannot find config file");
        }
        $config = require "configs/conf.php";
        return $config;
    }
}
