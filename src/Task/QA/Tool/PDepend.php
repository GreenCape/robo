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
 * Class PDepend
 *
 * @package GreenCape\Robo\Task\QA\Tool
 */
class PDepend extends ShellCommand
{
    /**
     * PDepend constructor.
     */
    public function __construct()
    {
        parent::__construct('vendor/bin/pdepend', '=', '--version');
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
        if (file_exists($options->logDir . '/clover.xml')) {
            $this->option('coverage-report', $options->logDir . '/clover.xml');
        }
        if (file_exists($options->configDir . '/pdepend.xml')) {
            $this->option('configuration', realpath($options->configDir . '/pdepend.xml'));
        }
        if (!empty($options->suffices)) {
            $this->option('suffix', implode(',', array_map(function ($v) {
                return ltrim($v, '.');
            },$options->suffices)));
        }
        if (!empty($options->ignore)) {
            $this->option('ignore', implode(',', $options->ignore));
        }
        if ($options->isSavedToFiles) {
            $this->option('dependency-xml', $options->logDir . '/dependency.xml');
            $this->option('jdepend-chart', $options->logDir . '/jdepend.svg');
            $this->option('jdepend-xml', $options->logDir . '/jdepend.xml');
            $this->option('overview-pyramid', $options->logDir . '/pyramid.svg');
            $this->option('summary-xml', $options->logDir . '/summary.xml');
        }

        $this->arg(implode(',', $options->source));

        return $this;
    }
}
