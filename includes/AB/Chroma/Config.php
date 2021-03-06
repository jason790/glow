<?php
/**
 * Interface to configuration options.
 *
 * @category  Glow
 * @package   Glow
 * @author    Aaron Bieber <aaron@aaronbieber.com>
 * @copyright 2016 All Rights Reserved
 * @license   GNU GPLv3
 * @version   GIT: $Id$
 * @link      http://github.com/aaronbieber/glow
 */
namespace AB\Chroma;

class Config
{
    private static $instance = null;
    public $options = [];

    public function __construct()
    {
        $this->load();
    }

    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    private function load()
    {
        $config_yaml = file_get_contents('config/config.yml');
        $this->options = \yaml_parse($config_yaml);
    }

    public function get($key)
    {
        if (isset($this->options[$key])) {
            return $this->options[$key];
        } else {
            return false;
        }
    }

    public function getAll()
    {
        return $this->options;
    }
}
