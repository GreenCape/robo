<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Task\QA\Tool;

use GreenCape\Robo\Common\ShellCommand;
use GreenCape\Robo\Task\QA\Options;

/**
 * Class PhpHistory
 *
 * @package GreenCape\Robo\Task\QA\Tool
 */
class PhpHistory extends ShellCommand
{
    /**
     * PhpHistory constructor.
     */
    public function __construct()
    {
        parent::__construct('vendor/bin/phploc', ' ', '--version');
    }

    /**
     * @param Options|array $options
     *
     * @return $this
     */
    public function options($options)
    {
        if (!($options instanceof Options)) {
            return parent::options($options);
        }

        $this->resetArgs();
        if ($options->isSavedToFiles) {
            $this->option('log-csv', $options->logDir . '/history.csv');
        }
        $this->option('git-repository', '.');
        $this->arg(implode(' ', $options->source));

        return $this;
    }
}
