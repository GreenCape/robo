<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Common;

use Robo\Config;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Trait OutputControl
 *
 * @package GreenCape\Robo\Common
 */
trait OutputControl
{
    /** @var  OutputInterface */
    private $output;

    /**
     * Supress all output
     */
    protected function suppressOutput()
    {
        $config = new \ReflectionProperty('\\Robo\\Config', 'config');
        $config->setAccessible(true);
        $values = $config->getValue('\\Robo\\Config');
        $this->output = $values['output'];

        Config::setOutput(new NullOutput());
        ob_start();
    }

    /**
     * Resume output
     */
    protected function restoreOutput()
    {
        ob_get_clean();
        Config::setOutput($this->output);
    }
}
