<?php
/**
 * @package   GreenCape\Robo
 * @author    Niels Braczek <nbraczek@bsds.de>
 * @copyright 2015 BSDS Braczek Software- und DatenSysteme. All rights reserved.
 * @license   MIT
 */

namespace GreenCape\Robo\Task\CodeSniffer;

use GreenCape\Robo\Configuration;
use Robo\Task\BaseTask;
use Robo\Exception\TaskException;

/**
 * CodeSniffer Base Class
 *
 * @package GreenCape\Robo\Task\CodeSniffer
 */
abstract class Base extends BaseTask
{
    use \Robo\Common\ExecOneCommand;

    /** @var string Command to use */
    protected $command;

    /** @var string Action to use */
    protected $action = '';

    /** @var string Standard to use */
    protected $standard;

    /**
     * Base constructor.
     *
     * @param null $pathToBinary
     */
    public function __construct($pathToBinary = null)
    {
        $this->locateCommand('phpcs', $pathToBinary);
    }

    /**
     * adds `standard-dist` option to composer
     *
     * @return $this
     */
    public function standard($standard)
    {
        $this->standard = $standard;
        return $this;
    }

    public function getCommand()
    {
        $this->option('standard', $this->standard);
        return "{$this->command} {$this->action}{$this->arguments}";
    }

    /**
     * @param $binary
     * @param $pathToBinary
     *
     * @throws TaskException
     */
    protected function locateCommand($binary, $pathToBinary)
    {
        if ($pathToBinary) {
            $this->command = $pathToBinary;
        } elseif (file_exists("vendor/bin/{$binary}")) {
            $this->command = "vendor/bin/{$binary}";
        } elseif (file_exists("{$binary}.phar")) {
            $this->command = "php {$binary}.phar";
        } elseif (is_executable("/usr/bin/{$binary}")) {
            $this->command = "/usr/bin/{$binary}";
        } elseif (is_executable("/usr/local/bin/{$binary}")) {
            $this->command = "/usr/local/bin/{$binary}";
        } else {
            throw new TaskException(__CLASS__, "Unable to locate binary '{$binary}'");
        }
    }

    protected function getDefaultOptions()
    {
        return [
            'standard' => Configuration::get('codestyle.standard', 'PSR1,PSR2'),
        ];
    }
}
