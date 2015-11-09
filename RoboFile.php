<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */

define('PROJECT_ROOT', __DIR__);

class RoboFile extends \Robo\Tasks
{
    use \GreenCape\Robo\Command\TestCommands;
    use \GreenCape\Robo\Command\CodeSnifferCommands;

    public function __construct()
    {
        foreach (parse_ini_file('robo.ini', true) as $section => $settings) {
            foreach ($settings as $key => $value) {
                $this->configure("$section.$key", $value);
            }
        }
    }
}
