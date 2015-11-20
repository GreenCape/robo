<?php

namespace GreenCape\Robo\Common;

use Robo\Config;
use Symfony\Component\Console\Output\NullOutput;

trait OutputControl
{
    private $output;

    protected function suppressOutput()
    {
        $config = new \ReflectionProperty('\\Robo\\Config', 'config');
        $config->setAccessible(true);
        $values = $config->getValue('\\Robo\\Config');
        $this->output = $values['output'];

        Config::setOutput(new NullOutput());
        ob_start();
    }

    protected function restoreOutput()
    {
        ob_get_clean();
        Config::setOutput($this->output);
    }
}
