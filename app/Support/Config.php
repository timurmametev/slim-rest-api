<?php

declare(strict_types=1);

namespace App\Support;

class Config
{
    /**
     * @var array
     */
    private $config = [];

    /**
     * Config constructor.
     *
     * @param string $dir
     * @param string $env
     * @param string $root
     */
    public function __construct(string $dir, string $env, string $root)
    {
        if (!is_dir($dir)) return;

        $config = (array)parse_ini_file($dir . DIRECTORY_SEPARATOR . 'app.ini', false);

        $environmentConfigFile = $dir . DIRECTORY_SEPARATOR . 'app.' . $env . '.ini';
        if (is_readable($environmentConfigFile)) {
            $config = array_replace_recursive($config, (array)parse_ini_file($environmentConfigFile, false));
        }

        foreach ($config as $name => $value) {
            $this->config[$name] = $value;
        }
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function get(string $name)
    {
        return array_key_exists($name, $this->config) ? $this->config[$name] : null;
    }
}