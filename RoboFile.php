<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */

define('PROJECT_ROOT', __DIR__);

class RoboFile extends \Robo\Tasks
{
    use \GreenCape\Robo\Command\MetricsCommands;
}
