<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Task\Metrics;

use GreenCape\Robo\Task\Metrics\Tools\AbstractTool;
use GreenCape\Robo\Task\Metrics\Tools\DependencyDetector;
use GreenCape\Robo\Task\Metrics\Tools\CopyPasteDetector;
use GreenCape\Robo\Task\Metrics\Tools\CodeSniffer;
use GreenCape\Robo\Task\Metrics\Tools\LineCounter;
use GreenCape\Robo\Task\Metrics\Tools\MessDetector;
use GreenCape\Robo\Task\Metrics\Tools\MetricsReport;
use Robo\Common\TaskIO;
use Robo\Common\Timer;
use Robo\Task\BaseTask;
use Symfony\Component\Process\Process;

class Metrics extends BaseTask
{
    use Timer;
    use TaskIO;
    use \Robo\Task\FileSystem\loadShortcuts;
    use \Robo\Task\Base\loadTasks;

    /** @var array [tool => Class] */
    private $tools = [
        'phploc'     => 'GreenCape\Robo\Task\Metrics\Tools\LineCounter',
        'phpcpd'     => 'GreenCape\Robo\Task\Metrics\Tools\CopyPasteDetector',
        'phpcs'      => 'GreenCape\Robo\Task\Metrics\Tools\CodeSniffer',
        'pdepend'    => 'GreenCape\Robo\Task\Metrics\Tools\Depend',
        'phpmd'      => 'GreenCape\Robo\Task\Metrics\Tools\MessDetector',
        'phpmetrics' => 'GreenCape\Robo\Task\Metrics\Tools\MetricsReport'
    ];

    /** @var Options */
    private $options;

    /**
     * Report the versions of the available tools
     */
    public function versions()
    {
        $executionTime = 0;
        foreach (array_keys($this->tools) as $tool) {
            $command = $this->binary("{$tool} --version");
            $process = new Process($command);
            $this->startTimer();
            $process->run();
            $this->stopTimer();
            $executionTime += $this->getExecutionTime();
            $text = trim($process->getOutput());
            $this->printTaskInfo($text, $this);
        }

        $this->printTaskSuccess(sprintf('Done in <fg=yellow>%.3fs</fg=yellow>', $executionTime));
    }

    private function binary($tool)
    {
        return PROJECT_ROOT . '/vendor/bin/' . $tool;
    }

    public function run(
        $opts = [
            'srcDir'       => '.',
            'cfgDir'       => 'build/config',
            'logDir'       => 'build/logs',
            'ignoredDirs'  => 'vendor',
            'ignoredFiles' => '',
            'tools'        => 'phploc,phpcpd,phpcs,pdepend,phpmd,phpmetrics',
            'output'       => 'file',
            'verbose'      => true,
        ]
    ) {
        $this->options = new Options($opts);
        $this->ciClean();
        $this->parallelRun();
    }

    private function ciClean()
    {
        if ($this->options->isSavedToFiles) {
            if (is_dir($this->options->logDir)) {
                $this->_cleanDir($this->options->logDir);
            }
            $this->_mkdir($this->options->logDir);
        }
    }

    private function parallelRun()
    {
        $parallel = $this->taskParallelExec();
        foreach ($this->tools as $tool => $optionSeparator) {
            if ($this->options->isEnabled($tool)) {
                $parallel->process($this->getExecutable($tool));
            }
        }
        $parallel->printed($this->options->isOutputPrinted)->run();
    }

    private function getExecutable($tool)
    {
        $className = $this->tools[$tool];
        /** @var AbstractTool $class */
        $class = new $className($this->options);

        return $class->getExecutable();
    }

    protected function getPrintedTaskName($task = null)
    {
        return 'Metrics';
    }

    /**
     * @return AbstractTool
     */
    private function phploc()
    {
        return (new LineCounter($this->options));
    }

    /**
     * @return AbstractTool
     */
    private function phpcpd()
    {
        return (new CopyPasteDetector($this->options));
    }

    /**
     * @return AbstractTool
     */
    private function phpcs()
    {
        return (new CodeSniffer($this->options));
    }

    /**
     * @return AbstractTool
     */
    private function pdepend()
    {
        return (new DependencyDetector($this->options));
    }

    /**
     * @return AbstractTool
     */
    private function phpmd()
    {
        return (new MessDetector($this->options));
    }

    /**
     * @return AbstractTool
     */
    private function phpmetrics()
    {
        return (new MetricsReport($this->options));
    }
}
