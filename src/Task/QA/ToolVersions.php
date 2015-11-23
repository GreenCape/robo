<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Task\QA;

use GreenCape\Robo\Common\ShellCommand;
use GreenCape\Robo\Task\Base;
use Robo\Result;

/**
 * Class ToolVersions
 *
 * @package GreenCape\Robo\Task\QA
 */
class ToolVersions extends Base
{
    /** @var array Exclude these classes from processing */
    private $skip = [
        'Base',
    ];

    /**
     * @return Result
     */
    public function run()
    {
        $this->printTaskInfo('Available QA Tools');

        $this->suppressOutput();
        $results = [];
        foreach (glob(__DIR__ . '/Tool/*.php') as $toolPath) {
            if (in_array(basename($toolPath, '.php'), $this->skip)) {
                continue;
            }
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
