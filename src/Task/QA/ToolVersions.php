<?php

namespace GreenCape\Robo\Task\QA;

use GreenCape\Robo\Common\ShellCommand;
use GreenCape\Robo\Task\Base;
use Robo\Result;

class ToolVersions extends Base
{
    public function run()
    {
        $this->printTaskInfo('Available QA Tools');

        $this->suppressOutput();
        $results = [];
        foreach (glob(__DIR__ . '/Tool/*.php') as $toolPath) {
            $result = $this->getTool($toolPath)->version();
            $results[] = new Result(
                $this,
                $result->getExitCode(),
                preg_replace(
                    '~^([^,\s]+)(.*?)(\d+\.\d+\S+)(.*)?$~',
                    '<info>\\1</info>\\2<fg=yellow>\\3</fg=yellow>\\4',
                    trim($result->getMessage())
                ),
                $result->getData()
            );
        }
        $this->restoreOutput();

        return $this->combineResults($results);
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
