<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Task\QA\Tool;

use GreenCape\Robo\Common\ShellCommand;
use GreenCape\Robo\Configuration;
use GreenCape\Robo\Task\QA\Options;

/**
 * Class PhpCs
 *
 * @package GreenCape\Robo\Task\QA\Tool
 */
class PhpCs extends ShellCommand
{
    /**
     * PhpCs constructor.
     */
    public function __construct()
    {
        parent::__construct('vendor/bin/phpcs', '=', '--version');
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

        $this->option('standard', Configuration::get('codestyle.standard'));
        if (!empty($options->suffices)) {
            $this->option('extensions', implode(',', array_map(function ($v) {
                return ltrim($v, '.');
            }, $options->suffices)));
        }
        if (!empty($options->ignore)) {
            $this->option('ignore', implode(',', $options->ignore));
        }
        if ($options->isSavedToFiles) {
            $this->option('report', 'checkstyle');
            $this->option('report-file', $options->logDir . '/checkstyle.xml');
        } else {
            $this->option('report', 'full');
        }
        $this->arg(implode(' ', $options->source));

        return $this;
    }
}
