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
 * Class PhpMetrics
 *
 * @package GreenCape\Robo\Task\QA\Tool
 */
class PhpMetrics extends ShellCommand
{
    /**
     * PhpMetrics constructor.
     */
    public function __construct()
    {
        parent::__construct('vendor/bin/phpmetrics', ' ', '--version');
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
        if (file_exists($options->configDir . '/phpmetrics.yml')) {
            $this->option('config', realpath($options->configDir . '/phpmetrics.yml'));
        }
        if (!empty($options->suffices)) {
            $this->option('extensions', '"' . implode('|', array_map(function ($v) {
                return ltrim($v, '.');
            }, $options->suffices)) . '"');
        }
        if (!empty($options->ignore)) {
            $this->option('excluded-dirs', '"' . implode('|', $options->ignore) . '"');
        }
        if ($options->isSavedToFiles) {
            $this->option('report-html', $options->logDir . '/metrics.html');
            $this->option('chart-bubbles', $options->logDir . '/bubble-chart.svg');
        } else {
            $this->option('report-cli', '');
        }

        $this->arg(implode(' ', $options->source));

        return $this;
    }
}
