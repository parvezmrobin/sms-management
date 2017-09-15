<?php
/**
 * Created by PhpStorm.
 * User: Parvez
 * Date: 9/15/2017
 * Time: 12:11 PM
 */

namespace App;

use Symfony\Component\Yaml\Yaml;

class Config
{
    public static function get($config, $key)
    {
        return Yaml::parse(file_get_contents(__DIR__ . "/../config/" . $config . ".ymal"))[$key];
    }
}