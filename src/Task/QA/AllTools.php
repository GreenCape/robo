<?php

namespace GreenCape\Robo\Task\QA;

use GreenCape\Robo\Common\ShellCommand;
use GreenCape\Robo\Task\Base;
use Robo\Result;
use Robo\Task\Base\ParallelExec;

class AllTools extends Base
{
    /** @var  Options */
    protected $commonOptions;

    public function run()
    {
        $parallel = new ParallelExec();
        $info     = [];
        foreach (glob(__DIR__ . '/Tool/*.php') as $toolPath) {
            $tool = $this->getTool($toolPath);
            $tool->options($this->commonOptions);
            $info[] = preg_replace('~^.*?(\w+)$~', '\\1', get_class($tool));
            $parallel->process($tool);
        }
        $startMessage = 'Performing QA analysis using ' . implode(', ', $info);

        $this->doRun($parallel, $startMessage, [
            'verbose'      => $this->commonOptions->isVerbose,
            'quiet'        => $this->commonOptions->isQuiet,
            'ignoreErrors' => true,
        ]);
    }

    /**
     * @param Options $options
     *
     * @return $this
     */
    public function options(Options $options)
    {
        $this->commonOptions = $options;

        return $this;
    }

    /**
     * @param $toolPath
     *
     * @return ShellCommand
     */
    private function getTool($toolPath)
    {
        $className = __NAMESPACE__ . '\\Tool\\' . basename($toolPath, '.php');

        return new $className;
    }
}
