<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Task\Metrics\Tools;

use GreenCape\Robo\Task\Metrics\Options;
use Robo\Task\Base\Exec;

abstract class AbstractTool
{
    /** @var Options */
    protected $options;

    public function __construct(Options $options)
    {
        $this->options = $options;
    }

    public function getExecutable()
    {
        $separator = $this->getOptionSeparator();
        $process   = new Exec($this->getBinary());
        foreach ($this->getArgs() as $arg => $value) {
            if (is_int($arg)) {
                $process->arg($value);
            } elseif ($value) {
                $process->arg("--{$arg}{$separator}{$value}");
            } else {
                $process->arg("--{$arg}");
            }
        }

        return $process;
    }

    protected function getOptionSeparator()
    {
        return '=';
    }

    abstract protected function getBinary();

    /**
     * @return array
     */
    abstract protected function getArgs();
}
