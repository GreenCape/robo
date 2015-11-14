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
                if (!empty($value)) {
                    $this->configure("$section.$key", $value);
                }
            }
        }
    }

    /**
     * Show the current configuration settings
     *
     * @param string $subset Restrict the output to configuration values for a specific section
     */
    public function showConfig($subset = null, $options = [])
    {
        $pattern = empty($subset) ? '~^RoboFile\.(.*)$~' : $pattern = '~^RoboFile(\.' . $subset . '\..*)$~i';

        if ($options['no-ansi']) {
            $format = "%-32s %s";
        } else {
            $format = "<fg=yellow>%-32s</fg=yellow> <fg=green>%s</fg=green>";
        }

        $config = new ReflectionProperty('\Robo\Config', 'config');
        $config->setAccessible(true);

        foreach ($config->getValue() as $key => $value) {
            if (!preg_match($pattern, $key, $match)) {
                continue;
            }
            if (!is_scalar($value)) {
                $value = gettype($value);
            }
            $this->say(sprintf($format, $match[1], $value));
        }
    }
}
