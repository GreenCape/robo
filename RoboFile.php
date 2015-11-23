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
    use \GreenCape\Robo\Command\QACommands;

    /**
     * Show the current configuration settings
     *
     * @param string $subset Restrict the output to configuration values for a specific section
     */
    public function showConfig($subset = null, $options = [])
    {
        $pattern = empty($subset) ? '~^RoboFile\.(.*)$~' : $pattern = '~^RoboFile(\.' . $subset . '\..*)$~i';

        if ($options['no-ansi']) {
            $formatA = $formatB = "%-32s %s";
        } else {
            $formatA = "<fg=yellow>%-32s</fg=yellow> <fg=green>%s</fg=green>";
            $formatB = "<fg=yellow>%-32s</fg=yellow> %s";
        }

        $config = new ReflectionProperty('\Robo\Config', 'config');
        $config->setAccessible(true);

        foreach ($config->getValue() as $key => $value) {
            if (!preg_match($pattern, $key, $match)) {
                continue;
            }
            $format = $formatA;
            if (!is_scalar($value)) {
                $value = gettype($value);
                $format = $formatB;
            }
            if (empty($value)) {
                $value = 'not set';
                $format = $formatB;
            }
            $this->say(sprintf($format, $match[1], $value));
        }
    }
}
