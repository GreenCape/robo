<?php
namespace GreenCape\Robo;

/**
 * Configuration
 */
class Configuration
{
    protected static $settings = [];

    public static function init($iniFile = 'robo.ini')
    {
        self::$settings = [
            'project.source' => "src",
            'project.ignore' => null,

            'codestyle.standard'     => "PSR1,PSR2",
            'codestyle.doc.template' => __DIR__ . '/Template/codestyle.html',
            'codestyle.doc.file'     => "docs/codestyle.html",
            'codestyle.ignore'       => null,
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
