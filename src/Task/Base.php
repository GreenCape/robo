<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Task;

use GreenCape\Robo\Common\OutputControl;
use Robo\Contract\TaskInterface;
use Robo\Result;
use Robo\Task\BaseTask;
use Symfony\Component\Console\Output\NullOutput;

/**
 * Class Base
 *
 * @package GreenCape\Robo\Task
 */
abstract class Base extends BaseTask
{
    use OutputControl;

    /**
     * Run a task respecting --quiet and --verbose
     *
     * If $options['quiet'] is true, no output is generated.
     *
     * Otherwise, the start message and the result are printed.
     *
     * If $options['verbose'] is true, the task output is printed, otherwise
     * it is suppressed.
     *
     * If $options['ignoreErrors'] is true, the result will always be 'success'.
     *
     * @param TaskInterface $task
     *        The task to run
     * @param string        $startMessage
     *        The info message to be displayed, when the task is started.
     *        It will be suppressed, if the --quiet option is given.
     * @param array         $options
     *        Options for the handling. Mandatory keys are
     *        'quiet', 'verbose', and 'ignoreErrors',
     *        all having boolean values.
     */
    protected function doRun(TaskInterface $task, $startMessage, $options)
    {
        if ($options['quiet']) {
            $this->runSilently($task);

            return;
        }

        $this->printTaskInfo($startMessage);

        if ($options['verbose']) {
            $result = $task->run();
        } else {
            $result = $this->runSilently($task);
        }

        $this->printTaskResult(
            $result->getExitCode() == 0 || $options['ignoreErrors'],
            "Done " . sprintf("in <fg=yellow>%.3fs</fg=yellow>", $result->getExecutionTime()),
            $result->getMessage()
        );
    }

    /**
     * Compile a set of results into one single result.
     *
     * @param Result[] $results
     *        The results to be combined
     *
     * @return Result
     */
    protected function combineResults($results)
    {
        $exitCode = 0;
        $time     = 0;
        foreach ($results as $result) {
            $this->getOutput()->writeln(trim($result->getMessage()));
            $exitCode = max($exitCode, $result->getExitCode());
            $time += (float)$result->getExecutionTime();
        }

        $message = "Done " . sprintf("in <fg=yellow>%.3fs</fg=yellow>", $time);

        $this->printTaskResult(
            $exitCode == 0,
            $message,
            $message
        );

        return new Result($this, $exitCode, $message);
    }

    /**
     * Get a name of the task making sense to humans.
     *
     * @param string $task
     *        The task (optional). If omitted, $this is used.
     *
     * @return mixed
     */
    protected function getPrintedTaskName($task = null)
    {
        if (empty($task)) {
            $task = $this;
        }

        return preg_replace('~^.*?(\w+)$~', '\\1', get_class($task));
    }

    /**
     * @param TaskInterface $task
     *
     * @return Result
     */
    private function runSilently(TaskInterface $task)
    {
        $this->suppressOutput();
        $result = $task->run();
        $this->restoreOutput();

        return $result;
    }

    /**
     * @param $success
     * @param $successMsg
     * @param $failureMsg
     */
    protected function printTaskResult($success, $successMsg, $failureMsg)
    {
        if ($success) {
            $this->printTaskSuccess($successMsg, $this);
        } else {
            $this->printTaskError($failureMsg, $this);
        }
    }
}
