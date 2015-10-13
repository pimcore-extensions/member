<?php

namespace Member\Plugin;

class Config
{
    /**
     * @var \Zend_Config
     */
    protected static $data;

    /**
     * @param string $key
     * @return mixed
     */
    public static function get($key)
    {
        self::load();

        return self::$data->{$key};
    }

    /**
     * @return string
     */
    public static function getFile()
    {
        return PIMCORE_WEBSITE_VAR . '/plugins/member/config.xml';
    }

    /**
     * Load settings from config file.
     *
     * @return \Zend_Config
     */
    public static function load($force = false)
    {
        if (self::$data && !$force) {
            return self::$data;
        }

        self::$data = new \Zend_Config_Xml(self::getFile());
        return self::$data;
    }
}
