<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Common;

use GreenCape\Robo\Task\Base;
use GreenCape\Robo\Task\QA\Options;
use Robo\Common\CommandArguments;
use Robo\Common\TaskIO;
use Robo\Contract\CommandInterface;
use Robo\Result;
use Robo\Task\Base\Exec;

/**
 * Class ShellCommand
 *
 * @package GreenCape\Robo\Task\QA
 */
class ShellCommand extends Base implements CommandInterface
{
    use TaskIO;
    use CommandArguments;

    /** @var string Name of the shell command. */
    protected $binary;

    /** @var string Option separator, usually '=' or ' '. Defaults to '='. */
    protected $optionSeparator;

    /** @var string Option to force version output, defaults to '--version'. */
    protected $versionOption;

    /**
     * ShellCommand constructor.
     *
     * @param string $binary          Name of the shell command.
     * @param string $optionSeparator Option separator, usually '=' or ' '. Defaults to '='.
     * @param string $versionOption   Option to force version output, defaults to '--version'.
     */
    public function __construct($binary, $optionSeparator = '=', $versionOption = '--version')
    {
        $this->binary          = $binary;
        $this->optionSeparator = $optionSeparator;
        $this->versionOption   = $versionOption;
    }

    /**
     * Returns command that can be executed.
     * This method is used to pass generated command from one task to another.
     *
     * @return string
     */
    public function getCommand()
    {
        return trim($this->binary . $this->arguments);
    }

    /**
     * Pass option to executable.
     * Options are prefixed with `--`, value can be provided in second parameter
     *
     * @param string $option The option name
     * @param string $value  The option value
     *
     * @return $this
     */
    public function option($option, $value = null)
    {
        if (!empty($option) && $option[0] != '-') {
            $option = "--$option";
        }
        $argument = [];
        if (!empty($option)) {
            $argument[] = $option;
        }
        if (!empty($value)) {
            $argument[] = $value;
        }
        if (!empty($argument)) {
            $this->arguments .= ' ' . implode($this->optionSeparator, $argument);
        }

        return $this;
    }

    /**
     * Pass multiple options to executable.
     *
     * @param Options|array $options A list of options
     *
     * @return $this
     */
    public function options($options)
    {
        if (($options instanceof Options)) {
            throw new \LogicException(get_class($this) . ' must handle Option object');
        }

        foreach ((array)$options as $arg => $value) {
            if (is_int($arg)) {
                $this->option(null, $value);
            } elseif (!empty($value)) {
                $this->option($arg, $value);
            } else {
                $this->option($arg);
            }
        }

        return $this;
    }

    /**
     * Run the command with the version option.
     *
     * @return Result
     */
    public function version()
    {
        return (new Exec($this->binary))
            ->arg($this->versionOption)
            ->run();
    }

    /**
     * Run the command with the current options.
     *
     * @return Result
     */
    public function run()
    {
        $this->printTaskInfo(sprintf('Running <info>%s</info>', $this->getCommand()));

        $executable = new Exec($this->getCommand());

        $this->suppressOutput();
        $result = $executable->run();
        $this->restoreOutput();

        return $this->combineResults([$result]);
    }

    /**
     * Remove the current argument list.
     *
     * @return $this
     */
    public function resetArgs()
    {
        $this->arguments = '';

        return $this;
    }
}
