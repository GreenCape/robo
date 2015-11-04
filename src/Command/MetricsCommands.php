<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Command;

use GreenCape\Robo\Task\Metrics\loadTasks;

trait MetricsCommands
{
    use loadTasks;

    public function metrics()
    {
        $this->taskMetrics()->run();
    }

    /**
     * Report the versions of the available tools
     */
    public function metricsVersions()
    {
        $this->taskMetrics()->versions();
    }
}
