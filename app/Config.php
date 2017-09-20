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
    private $filePath;
    private $content;
    private $data;


    /**
     * Config constructor.
     * @param $config
     */
    function __construct($config)
    {
        $this->filePath = __DIR__ . "/../config/" . $config . ".ymal";
        $this->content = file_get_contents($this->filePath);
        $this->data = Yaml::parse($this->content);
    }

    /**
     * @param string $key
     * @return string
     */
    public function get($key)
    {
        return $this->data[$key];
    }

    /**
     * @param string $config
     * @return static
     */
    public static function from($config)
    {
        return new static($config);
    }
}