<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo;

/**
 * Class Configuration
 *
 * @package GreenCape\Robo
 */
class Configuration
{
    /** @var array */
    protected static $settings = [];

    /**
     * @param string $iniFile
     */
    public static function init($iniFile = 'robo.ini')
    {
        self::$settings = [
            'project.source'     => "src",
            'project.ignore'     => null,
            'project.suffices'   => '.php',
            'project.log.dir'    => 'build/logs',
            'project.config.dir' => 'build/config',

            'codestyle.standard'     => "PSR1,PSR2",
            'codestyle.doc.template' => __DIR__ . '/Template/codestyle.html',
            'codestyle.doc.file'     => "docs/codestyle.html",
            'codestyle.log.file'     => "logs/checkstyle.xml"
        ];

        foreach (parse_ini_file($iniFile, true) as $section => $settings) {
            foreach ($settings as $key => $value) {
                if (!empty($value)) {
                    self::$settings["$section.$key"] = $value;
                }
            }
        }
    }

    /**
     * @param null $key
     * @param null $default
     *
     * @return array|null
     */
    public static function get($key = null, $default = null)
    {
        if (empty(self::$settings)) {
            self::init();
        }

        if (empty($key)) {
            return self::$settings;
        }

        if (is_null(self::$settings[$key])) {
            return $default;
        }

        return self::$settings[$key];
    }
}
