<?php

namespace GreenCape\Robo\Task;

use GreenCape\Robo\Common\OutputControl;
use Robo\Contract\TaskInterface;
use Robo\Result;
use Robo\Task\BaseTask;
use Symfony\Component\Console\Output\NullOutput;

abstract class Base extends BaseTask
{
    use OutputControl;

    /**
     * @param TaskInterface $task
     * @param               $startMessage
     * @param               $options
     */
    protected function doRun(TaskInterface $task, $startMessage, $options)
    {
        if ($options['quiet']) {
            $this->suppressOutput();
            $task->run();
            $this->restoreOutput();
        } else {
            $this->printTaskInfo($startMessage);

            if ($options['verbose']) {
                $result = $task->run();
            } else {
                $this->suppressOutput();
                $result = $task->run();
                $this->restoreOutput();
            }

            if ($result->getExitCode() == 0 || $options['ignoreErrors']) {
                $message = "Done " . sprintf("in <fg=yellow>%.3fs</fg=yellow>", $result->getExecutionTime());
                $this->printTaskSuccess($message);
            } else {
                $this->printTaskError($result->getMessage());
            }
        }
    }

    /**
     * @param Result[] $results
     *
     * @return Result
     */
    protected function combineResults($results)
    {
        $exitCode = 0;
        $time     = 0;
        foreach ($results as $result) {
            $text = trim($result->getMessage());
            if (!empty($text)) {
                $this->getOutput()->writeln("$text");
            }
            $exitCode = max($exitCode, $result->getExitCode());
            $time += (float)$result->getExecutionTime();
        }

        if ($exitCode == 0) {
            $message = "Done " . sprintf("in <fg=yellow>%.3fs</fg=yellow>", $time);
            $this->printTaskSuccess($message, $this);
        } else {
            $message = "An error occurred.";
            $this->printTaskError($message, $this);
        }

        return new Result($this, $exitCode, $message);
    }

    protected function getPrintedTaskName($task = null)
    {
        if (empty($task)) {
            $task = $this;
        }
        return preg_replace('~^.*?(\w+)$~', '\\1', get_class($task));
    }
}
